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
        $date = Carbon::parse($request->dateTo)->format('Y-m-d');

        $limitedDates = $this->modifyDate($date, $store);

        $result = [];
        $ageGenderFlow = $this->getWalkInReport($store, $date);
        $salesReport = $this->getSalesReport($store, $date, $limitedDates);

        $result = $this->mapTableFields($result, $ageGenderFlow);
        $result = $this->mapWalkInCount($result, $ageGenderFlow);
        $result = $this->mapSalesReport($result, $salesReport);

        $dataToExport = array_values($result);

        $fileName = "report_$date.xlsx";

        return Excel::download(new ReportExport($dataToExport), $fileName);
    }

    /**
     * @param $store
     * @param $selectedDate
     * @return mixed
     */
    public function getWalkInReport($store, $selectedDate)
    {
        $ageGenderFlow = $store->ageGenderFlow()
            ->whereDate('date', '=', $selectedDate)
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

            $result[$time]['ordersCount'] += 1;
            $result[$time]['itemsCount'] += $itemsCount === 0 ? 1 : $itemsCount;
            $result[$time]['totalSales'] += $orderTotal;
        }

        foreach ($result as $time => $value) {
            $averageItemsPerOrder = round($value['itemsCount'] / $value['ordersCount'], 1);
            $averageItemPrice = round($value['totalSales'] / $value['itemsCount'], 0);
            $conversion = $value['walkInCount'] ? round($value['ordersCount'] / $value['walkInCount'] * 100, 0) : 0;
            $atv = round($value['totalSales'] / $value['ordersCount'], 0);

            $result[$time]['averageItemsPerOrder'] = $averageItemsPerOrder;
            $result[$time]['averageItemPrice'] = (int) $averageItemPrice;
            $result[$time]['conversion'] = $conversion . '%';
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
                $result[$time] = [
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
                    'elderly' => 0,
                    'male_earlyYouth' => 0,
                    'male_youth' => 0,
                    'male_middleAge' => 0,
                    'male_elderly' => 0,
                    'female_earlyYouth' => 0,
                    'female_youth' => 0,
                    'female_middleAge' => 0,
                    'female_elderly' => 0,
                ];
            }
        }

        return $result;
    }
}
