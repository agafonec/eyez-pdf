<?php


namespace App\Services\CustomersFlow;


use App\Contracts\CustomersFlowInterface;
use App\Http\Controllers\Controller;
use App\Models\AgeGenderFlow;
use App\Models\HourlyPassengerFlow;
use App\Models\Store;
use App\Traits\HasDateMap;
use Carbon\Carbon;
use Illuminate\Http\Request;

class CustomersFlow extends Controller implements CustomersFlowInterface
{
    use HasDateMap;
    public string $reportType;
    public array|null $summary;
    public float|null $avgWalkIn;
    public array $storeSalesReport;

    public function getReportData(Request $request, Store|array $stores)
    {
        $this->reportType = 'hours';

        // Transform query date to date from/to parameters.
        $dateRange = $this->getDateRange($request);

        if ($this->reportType !== 'days') {
            $this->summary = $this->getSummary($stores);
        }

        $previousDateStart = Carbon::parse($dateRange->start)
            ->subDays($dateRange->diffInDays)
            ->startOfDay();
        $previousDateEnd = Carbon::parse($dateRange->end)
            ->subDays($dateRange->diffInDays)
            ->endOfDay();

        return (object)[
            'reportType' => $this->reportType,
            'currentReport' => $this->getReport($stores, $dateRange->start, $dateRange->end),
            'previousReport' => $this->getReport($stores, $previousDateStart, $previousDateEnd),
            'summary' => $this->summary,
            'avgWalkIn' => $this->avgWalkIn($stores, $dateRange->start),
        ];
    }

    /**
     * @param $storeIds
     * @param $dateFrom
     * @param $dateTo
     * @return mixed
     */
    public function getWalkInCount($storeIds, $dateFrom, $dateTo)
    {
        return HourlyPassengerFlow::whereIn('store_id', $storeIds)
            ->whereBetween('time', [$dateFrom, $dateTo])
            ->sum('passengerFlow');
    }

    /**
     * @param $stores
     * @return \array[][]
     */
    public function getSummary(Store|array $stores)
    {
        $storeIds = $this->getStoreIds($stores);

        return [
            "week" => [
                "current" => [
                    "title" => "השבוע",
                    "value" => $this->getWalkInCount(
                        $storeIds,
                        Carbon::now()->startOfWeek(Carbon::SUNDAY)->startOfDay(),
                        Carbon::now()->endOfDay()
                    )
                ],
                "previous" => [
                    "title" => 'שבוע שעבר',
                    "value" => $this->getWalkInCount(
                        $storeIds,
                        Carbon::now()->startOfWeek(Carbon::SUNDAY)->subWeeks(1)->startOfDay(),
                        Carbon::now()->subWeek()->endOfDay()
                    ),
                ]
            ],
            "month" => [
                "current" => [
                    "title" => 'החודש',
                    "value" => $this->getWalkInCount(
                        $storeIds,
                        Carbon::now()->startOfMonth()->startOfDay(),
                        Carbon::now()->endOfDay()
                    )
                ],
                "previous" => [
                    "title" => 'חודש שעבר',
                    "value" => $this->getWalkInCount(
                        $storeIds,
                        Carbon::now()->startOfMonth()->subMonth()->startOfDay(),
                        Carbon::now()->subMonth()->endOfDay()
                    )
                ]
            ],
            "year" => [
                "current" => [
                    "title" => 'השנה',
                    "value" => $this->getWalkInCount(
                        $storeIds,
                        Carbon::now()->startOfYear()->startOfDay(),
                        Carbon::now()->endOfDay()
                    )
                ],
                "previous" => [
                    "title" => 'שנה שעברה',
                    "value" => $this->getWalkInCount(
                        $storeIds,
                        Carbon::now()->startOfYear()->startOfDay(),
                        Carbon::now()->subYear()->endOfDay()
                    )
                ],
            ],
        ];
    }

    /**
     * @param $stores
     * @param $dateFrom
     * @param $dateTo
     * @return object
     */
    public function getReport($stores, $dateFrom, $dateTo)
    {
        $storeIds = $this->getStoreIds($stores);

        $totalWalkIn = $this->getWalkInCount($storeIds, $dateFrom, $dateTo);

        return (object)[
            'genderData' => $this->getGenderData($storeIds, $dateFrom, $dateTo, $totalWalkIn),
            'ageData' => $this->getAgeData($storeIds, $dateFrom, $dateTo, $totalWalkIn),
            'hourlyWalkIn' => $this->getHourlyWalkIn($storeIds, $dateFrom, $dateTo),
            'stores' => $stores,
            'dateFrom' => $dateFrom,
            'dateTo' => $dateTo,
            'reportType' => $this->reportType,
            'walkInCount' => $totalWalkIn
        ];
    }

    /**
     * @param $storeIds
     * @param $dateFrom
     * @param $dateTo
     * @return mixed
     */
    public function getHourlyWalkIn($storeIds, $dateFrom, $dateTo)
    {
        return HourlyPassengerFlow::whereIn('store_id', $storeIds)
            ->whereBetween('time', [$dateFrom, $dateTo])
            ->get()
            ->map(function ($item) {
                return [
                    "date" => date('Y-m-d', strtotime($item['time'])),
                    "time" => date('H:i', strtotime($item['time'])),
                    "passengerFlow" => $item['passengerFlow'],
                ];
            });
    }

    /**
     * @param $storeIds
     * @param $dateFrom
     * @param $dateTo
     * @param $total
     * @return mixed
     */
    public function getGenderData($storeIds, $dateFrom, $dateTo, $total)
    {
        $genderData = AgeGenderFlow::select('gender', \DB::raw('SUM(people_count) as total_people_count'))
            ->whereIn('store_id', $storeIds)
            ->whereBetween('date', [$dateFrom, $dateTo])
            ->groupBy('gender')
            ->get();

        $genderGrouped = [];
        if ($genderData) {
            foreach ($genderData as $genderSingle) {
                $percentage = $total !== 0 ? ($genderSingle['total_people_count'] / $total) * 100 : 0;
                $genderGrouped[$genderSingle['gender']] = [
                    'count' => $genderSingle['total_people_count'],
                    'percentage' => round($percentage, 0)
                ];
            }
        }

        return $genderGrouped;
    }

    public function getAgeData($storeIds, $dateFrom, $dateTo, $total)
    {
        $ageData = AgeGenderFlow::select('age_group_id', \DB::raw('SUM(people_count) as total_people_count'))
            ->whereIn('store_id', $storeIds)
            ->whereBetween('date', [$dateFrom, $dateTo])
            ->with('ageGroup')
            ->groupBy('age_group_id')
            ->get()
            ->toArray();

        $groupedAgeData = [];
        foreach ($ageData as $ageSingle) {
            $group = false;

            if ($ageSingle['age_group']['group_id'] === 0) {
                $group = 'earlyYouth';
            } else if ($ageSingle['age_group']['group_id'] === 1) {
                $group = 'youth';
            } else if ($ageSingle['age_group']['group_id'] === 2) {
                $group = 'middleAge';
            } else if ($ageSingle['age_group']['group_id'] === 3) {
                $group = 'middleOld';
            } else if ($ageSingle['age_group']['group_id'] === 4) {
                $group = 'elderly';
            }

            if ($group) {
                $percent = $total !== 0 ? ($ageSingle['total_people_count'] / $total) * 100 : 0;
                $groupedAgeData[$group] = [
                    'count' => $ageSingle['total_people_count'],
                    'percentage' => round($percent, 0),
                    'description' => $ageSingle['age_group']['ageTo'] ? "{$ageSingle['age_group']['ageFrom']} - {$ageSingle['age_group']['ageTo']}" : "> {$ageSingle['age_group']['ageFrom']}",
                ];
            }
        }

        return $groupedAgeData;
    }

    /**
     * @param Store|array $stores
     * @return array
     */
    public function getStoreIds(Store|array $stores)
    {
         if (!is_array($stores)) {
            $storeIds = [$stores->id];
        } else {
             $storeIds = $this->user()
                 ->stores()
                 ->whereIn('dep_id', $stores)
                 ->get()
                 ->pluck('id')
                 ->toArray();
        }

        return $storeIds;
    }

    /**
     * @param OpretailApi $opretailApi
     * @return float
     */
    private function avgWalkIn($stores, $dateFrom)
    {
        $storeIds = $this->getStoreIds($stores);

        $from = Carbon::parse($dateFrom)->subDays(1)->startOfMonth()->startOfDay();
        $to = Carbon::now()->month !== Carbon::parse($dateFrom)->subDays(1)->month
            ? Carbon::parse($dateFrom)->subDays(1)->endOfMonth()->endOfDay()
            : Carbon::now()->subDays(1)->endOfDay();

        $walkInCount = $this->getWalkInCount(
            $storeIds,
            $from,
            $to
        );

        $workdays = $this->user()?->settings['workdays'] ?? [];

        $diffInDays = $to->diffInDays($from);

        $count = 0;
        for ($i = 0; $i <= $diffInDays; $i++) {
            $currentDate = $to->copy()->subDays($i);

            if ( !in_array($currentDate->dayOfWeek, $workdays) ) {
                $count++;
            }
        }
        $avg = $count === 0 ? $walkInCount : $walkInCount / $count;

        return round($avg, 0);
    }
}
