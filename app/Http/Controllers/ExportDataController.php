<?php

namespace App\Http\Controllers;

use App\Exports\ReportExport;
use App\Models\Store;
use App\Traits\HasDateMap;
use App\Traits\HasStoreDateFilter;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class ExportDataController extends Controller
{
    use HasDateMap;
    use HasStoreDateFilter;
    public string $reportType;

    /**
     * @param Request $request
     * @param Store $store
     * @return \Symfony\Component\HttpFoundation\BinaryFileResponse
     */
    public function exportReport(Request $request, Store $store)
    {
        $date = Carbon::parse($request->query('dateTo'))->format('Y-m-d');
        $dateFrom = Carbon::parse($request->query('dateFrom'))->setTimezone('Asia/Jerusalem');
        $dateTo = Carbon::parse($request->query('dateTo'))->setTimezone('Asia/Jerusalem');
        $diffInDays = $dateFrom->diffInDays($dateTo);

        $daysoff = $store->settings['daysoff'] ?? [];

        $dataToExport = [];

        for ($i = 0; $i <= $diffInDays; $i++) {
            $result = [];
            $currentDate = $dateFrom->copy()->addDays($i);
            if (!in_array($currentDate->dayOfWeek, $daysoff)) {

                $date = $currentDate->format('Y-m-d');
                $limitedDates = $this->modifyDate($date, $store);

                \Log::info('Limited dates for export file', ['d' => $limitedDates]);

                $ageGenderFlow = $this->getWalkInReport($store, $date, $limitedDates);
                $salesReport = $this->getSalesReport($store, $date, $limitedDates);

                $result = $this->mapTableFields($result, $ageGenderFlow);
                $result = $this->mapWalkInCount($result, $ageGenderFlow);
                $result = $this->mapSalesReport($result, $salesReport);

                $dataToExport[$date] = array_values($result);
            }
        }

        if ($diffInDays > 0) {
            $dataToExport['summary'] = $this->getSummaryReport($dataToExport);
        }

        $fileName = "report_$date.xlsx";

        return Excel::download(new ReportExport($dataToExport, $store), $fileName);
    }

    /**
     * @param $dataToExport
     * @return array
     */
    public function getSummaryReport($dataToExport)
    {
        $reports = array_values($dataToExport);
        $mergedReports = array_merge(...$reports);

        $collection = collect($mergedReports);
        $groupedData = $collection->groupBy('time');

        $summaryReport = [];
        foreach ($groupedData as $key => $value) {
            $collection = collect($value);
            $summedObject = new \stdClass();
            $summedObject->time = $key;

            $properties = $collection->flatMap(function ($item) {
                return array_keys($item);
            })->unique();

            foreach ($properties as $property) {
                if ($property !== 'time') {
                    $summedObject->{$property} = $collection->sum($property);
                }
            }

            $averageItemsPerOrder = $summedObject->ordersCount ? round($summedObject->itemsCount / $summedObject->ordersCount, 1) : 0;
            $averageItemPrice = $summedObject->itemsCount ? round($summedObject->totalSales / $summedObject->itemsCount, 0) : 0;
            $conversion = $summedObject->walkInCount ? round($summedObject->ordersCount / $summedObject->walkInCount * 100, 0) : 0;
            $atv = $summedObject->ordersCount ? round($summedObject->totalSales / $summedObject->ordersCount, 0) : 0;

            $summedObject->averageItemsPerOrder = $averageItemsPerOrder;
            $summedObject->averageItemPrice = (int) $averageItemPrice;
            $summedObject->conversion = $conversion;
            $summedObject->atv = (int) $atv;

            $summaryReport[$key] = $summedObject;
        }

        return $summaryReport;
    }

    /**
     * @param $store
     * @param $selectedDate
     * @return mixed
     */
    public function getWalkInReport($store, $selectedDate, $limitedDates)
    {
        $ageGenderFlow = $store->ageGenderFlow()
            ->whereDate('date', '=', $selectedDate)
            ->whereBetween('date', [$limitedDates['startDate'], $limitedDates['endDate']])
            ->with('ageGroup')
            ->get();

        return $ageGenderFlow->map(function ($item, $key) {
            return [
                'time' => Carbon::parse($item->date)->subHour()->format('H:i') . '-' . Carbon::parse($item->date)->format('H:i'),
                'gender' => $item->gender,
                'walkInCount' => $item->people_count,
                'ageGroup' => $item->ageGroup->groupName,
            ];
        });
    }

    /**
     * @param $result
     * @param $ageGenderFlow
     */
    protected function mapWalkInCount($result, $ageGenderFlow)
    {
        foreach ($ageGenderFlow as $entry) {
            $time = $entry["time"];
            $walkInCount = $entry["walkInCount"];
            $gender = $entry["gender"];
            $ageGroup = $entry["ageGroup"];

            $result[$time]['walkInCount'] += $walkInCount;
            $result[$time][$gender] += $walkInCount;
            $result[$time][$ageGroup] += $walkInCount;
            $result[$time]["{$gender}_{$ageGroup}"] += $walkInCount;
        }

        return $result;
    }

    /**
     * @param $store
     * @param $selectedDate
     * @param $limitedDates
     * @return mixed
     */
    public function getSalesReport($store, $selectedDate, $limitedDates)
    {
        $orders = $store->orders()
            ->whereDate('order_date', '=', $selectedDate)
            ->whereBetween('order_date', [$limitedDates['startDate'], $limitedDates['endDate']])
            ->get();

        return $orders->map(function ($item, $key) {
            $date = Carbon::parse($item->order_date)->setMinute(0);
            $orderDate = $date->format('H:i') . '-' . $date->copy()->addHour()->format('H:i');

            return [
                'orderId' => $item->order_id,
                'orderDate' => $orderDate,
                'itemsCount' => $item->items_count,
                'orderTotal' => $item->order_total,
            ];
        });
    }

    /**
     * @param $result
     * @param $salesReport
     * @return array
     */
    protected function mapSalesReport($result, $salesReport)
    {
        foreach ($salesReport as $entry) {
            $time = $entry["orderDate"];
            $itemsCount = $entry["itemsCount"];
            $orderTotal = $entry["orderTotal"];

            if (isset($result[$time])) {
                $result[$time]['ordersCount'] += 1;
                $result[$time]['itemsCount'] += $itemsCount === 0 ? 1 : $itemsCount;
                $result[$time]['totalSales'] += $orderTotal;
            }
        }

        foreach ($result as $time => $value) {
            $averageItemsPerOrder = $value['ordersCount'] ? round($value['itemsCount'] / $value['ordersCount'], 1) : 0;
            $averageItemPrice = $value['itemsCount'] ? round($value['totalSales'] / $value['itemsCount'], 0) : 0;
            $conversion = $value['walkInCount'] ? round($value['ordersCount'] / $value['walkInCount'] * 100, 0) : 0;
            $atv = $value['ordersCount'] ? round($value['totalSales'] / $value['ordersCount'], 0) : 0;

            $result[$time]['averageItemsPerOrder'] = $averageItemsPerOrder;
            $result[$time]['averageItemPrice'] = (int) $averageItemPrice;
            $result[$time]['conversion'] = $conversion;
            $result[$time]['atv'] = (int) $atv;
        }


        return $result;
    }

    /**
     * @param $result
     * @param $ageGenderFlow
     * @return mixed
     */
    protected function mapTableFields($result, $ageGenderFlow)
    {
        foreach ($ageGenderFlow as $entry) {
            $time = $entry["time"];

            if (!isset($result[$time])) {
                $result[$time] = $this->emptyTableObject($time);
            }
        }

        return $result;
    }

    /**
     * @param $time
     * @return array
     */
    protected function emptyTableObject($time)
    {
        return [
            'time' => $time,
            'walkInCount' => 0,
            'female' => 0,
            'male' => 0,
            'ordersCount' => 0,
            'conversion' => 0,
            'totalSales' => 0,
            'atv' => 0,
            'itemsCount' => 0,
            'averageItemsPerOrder' => 1,
            'averageItemPrice' => 0,
            'earlyYouth' => 0,
            'youth' => 0,
            'middleAge' => 0,
            'middleOld' => 0,
            'elderly' => 0,
            'male_earlyYouth' => 0,
            'male_youth' => 0,
            'male_middleAge' => 0,
            'male_middleOld' => 0,
            'male_elderly' => 0,
            'female_earlyYouth' => 0,
            'female_youth' => 0,
            'female_middleAge' => 0,
            'female_middleOld' => 0,
            'female_elderly' => 0,
        ];
    }
}
