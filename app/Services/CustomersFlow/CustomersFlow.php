<?php


namespace App\Services\CustomersFlow;


use App\Contracts\CustomersFlowInterface;
use App\Http\Controllers\Controller;
use App\Models\AgeGenderFlow;
use App\Models\HourlyPassengerFlow;
use App\Models\Store;
use App\Models\User;
use App\Traits\HasDateMap;
use App\Traits\HasStoreDateFilter;
use Carbon\Carbon;
use Illuminate\Http\Request;

class CustomersFlow extends Controller implements CustomersFlowInterface
{
    use HasDateMap, HasStoreDateFilter;
    public string $reportType;
    public array|null $summary;
    public array $storeSalesReport;
    public User $user;

    public function setUser(User $user)
    {
        $this->user = $user;
        return $this;
    }

    public function getReportData(Request $request, Store|array $stores)
    {
        $this->reportType = 'hours';

        // Transform query date to date from/to parameters.
        $dateRange = $this->getDateRange($request);

        $this->summary = $this->reportType !== 'days' ? $this->getSummary($stores) : null;
        $previousDateStart = Carbon::parse($dateRange->start)
            ->subDays($dateRange->diffInDays)
            ->startOfDay()->addSecond();
        $previousDateEnd = Carbon::parse($dateRange->end)
            ->subDays($dateRange->diffInDays)
            ->endOfDay()->addSecond();

        return (object)[
            'reportType' => $this->reportType,
            'currentReport' => $this->getReport($stores, $dateRange->start->addSecond(), $dateRange->end->addSecond()),
            'previousReport' => $this->getReport($stores, $previousDateStart, $previousDateEnd),
            'summary' => $this->summary,
            'avgWalkIn' => $this->avgWalkIn($stores, $dateRange->start),
        ];
    }

    /**
     * @param $dateRange
     * @param $stores
     * @return mixed
     */
    public function getWalkInCount($dateRange, $stores)
    {
        $storeIds = $this->getStoreIds($stores);

        $query = $this->getQuery($dateRange->start, $dateRange->end, $storeIds, 'time');

        return HourlyPassengerFlow::whereRaw($query)
            ->sum('passengerFlow');
    }

    /**
     * @param $stores
     * @return \array[][]
     */
    public function getSummary(Store|array $stores)
    {
        return [
            "week" => [
                "current" => [
                    "title" => "השבוע",
                    "value" => $this->getWalkInCount(
                        (object)[
                            'start' => Carbon::now()->startOfWeek(Carbon::SUNDAY)->startOfDay()->addSecond(),
                            'end' => Carbon::now()->endOfDay()->addSecond()
                        ],
                        $stores,
                    )
                ],
                "previous" => [
                    "title" => 'שבוע שעבר',
                    "value" => $this->getWalkInCount(
                        (object)[
                            'start' => Carbon::now()->startOfWeek(Carbon::SUNDAY)->subWeeks(1)->startOfDay()->addSecond(),
                            'end' => Carbon::now()->subWeek()->endOfDay()->addSecond()
                        ],
                        $stores
                    ),
                ]
            ],
            "month" => [
                "current" => [
                    "title" => 'החודש',
                    "value" => $this->getWalkInCount(
                        (object)[
                            'start' => Carbon::now()->startOfMonth()->startOfDay()->addSecond(),
                            'end' => Carbon::now()->endOfDay()->addSecond()
                        ],
                        $stores,
                    )
                ],
                "previous" => [
                    "title" => 'חודש שעבר',
                    "value" => $this->getWalkInCount(
                        (object)[
                            'start' => Carbon::now()->startOfMonth()->subMonth()->startOfDay()->addSecond(),
                            'end' => Carbon::now()->subMonth()->endOfDay()->addSecond()
                        ],
                        $stores
                    )
                ]
            ],
            "year" => [
                "current" => [
                    "title" => 'השנה',
                    "value" => $this->getWalkInCount(
                        (object)[
                            'start' => Carbon::now()->startOfYear()->startOfDay()->addSecond(),
                            'end' => Carbon::now()->endOfDay()->addSecond()
                        ],
                        $stores
                    )
                ],
                "previous" => [
                    "title" => 'שנה שעברה',
                    "value" => $this->getWalkInCount(
                        (object)[
                            'start' => Carbon::now()->subYear()->startOfYear()->startOfDay()->addSecond(),
                            'end' => Carbon::now()->subYear()->endOfDay()->addSecond()
                        ],
                        $stores
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

        $dateQuery = $this->getQuery($dateFrom, $dateTo, $storeIds);

        $totalWalkIn = AgeGenderFlow::whereRaw($dateQuery)
            ->sum('people_count');

        $dateRange = (object) [
            'start' => $dateFrom,
            'end' => $dateTo
        ];
        return (object)[
            'genderData' => $this->getGenderData($storeIds, $dateFrom, $dateTo, $totalWalkIn),
            'ageData' => $this->getAgeData($storeIds, $dateFrom, $dateTo, $totalWalkIn),
            'hourlyWalkIn' => $this->getHourlyWalkIn($storeIds, $dateFrom, $dateTo),
            'stores' => $stores,
            'dateFrom' => $dateFrom,
            'dateTo' => $dateTo,
            'reportType' => $this->reportType,
            'walkInCount' => $this->getWalkInCount($dateRange, $stores)
        ];
    }

    /**
     * @param $dateFrom
     * @param $dateTo
     * @param array $stores
     * @param string $dateParamName
     * @return string
     */
    protected function getQuery($dateFrom, $dateTo, array $stores, $dateParamName = 'date')
    {
        $query = '';
        foreach ($stores as $store) {
            $storeObject = Store::find($store);
            $singleQuery = $storeObject->getDateQuery($dateFrom, $dateTo, $dateParamName);

            $query .= empty($query)
                ? "({$singleQuery})"
                : "OR ({$singleQuery})";
        }

        return $query;
    }

    /**
     * @param $storeIds
     * @param $dateFrom
     * @param $dateTo
     * @return mixed
     */
    public function getHourlyWalkIn($storeIds, $dateFrom, $dateTo)
    {
        $dateQuery = $this->getQuery($dateFrom, $dateTo, $storeIds, 'time');

        return HourlyPassengerFlow::whereRaw($dateQuery)
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
        $query = $this->getQuery($dateFrom, $dateTo, $storeIds);

        $genderData = AgeGenderFlow::select('gender', \DB::raw('SUM(people_count) as total_people_count'))
            ->whereRaw($query)
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
        $query = $this->getQuery($dateFrom, $dateTo, $storeIds);

        $ageData = AgeGenderFlow::select('age_group_id', \DB::raw('SUM(people_count) as total_people_count'))
            ->whereRaw($query)
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
             $storeIds = $this->user
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
        $from = Carbon::parse($dateFrom)->subDays(1)->startOfMonth()->startOfDay();
        $to = Carbon::now()->month !== Carbon::parse($dateFrom)->subDays(1)->month
            ? Carbon::parse($dateFrom)->subDays(1)->endOfMonth()->endOfDay()
            : Carbon::now()->subDays(1)->endOfDay();
        $dateRange = (object) [
            'start' => $from,
            'end' => $to
        ];
        $walkInCount = $this->getWalkInCount(
            $dateRange,
            $stores
        );

        $workdays = $this->user?->settings['workdays'] ?? [];

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
