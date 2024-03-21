<?php


namespace App\Services\Opretail;

use App\Models\Opretail;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Http;
use App\Services\Opretail\OpretailHelpers as OpretailHelpers;

class OpretailApi
{
    use OpretailHelpers, OpretailAuth;

    public $genderData;
    public $ageData;
    public $hourlyWalkIn;

    public function __construct(
        private Opretail $opretailCredentials,
        public Model|array $stores = [],
        public null|string $dateFrom = null,
        public null|string $dateTo = null,
        public string $reportType = 'hours'
    ) {
        $this->generateToken($opretailCredentials);
    }

    public function user()
    {
        return auth()->user() ?? request()->user() ?? null;
    }

    /**
     * @param $stores
     * @param $dateFrom
     * @param $dateTo
     * @param $reportType
     * @return $this
     */
    public function getReport($stores, $dateFrom, $dateTo, $reportType)
    {
        $this->dateFrom = $dateFrom;
        $this->dateTo = $dateTo;
        $this->stores = $stores;
        $this->reportType = $reportType;

        $this->walkInCount = $this->getWalkInCount();
        $this->getAgeGenderData();
        $this->getStoreData();

        return $this;
    }

    public function getSummary($stores)
    {
        $this->stores = $stores;

        $summary = [
            "week" => [
                "current" => [
                    "title" => "השבוע",
                    "value" => $this->getWalkInCount(
                        Carbon::now()->startOfWeek(Carbon::SUNDAY)->startOfDay(),
                        Carbon::now()->endOfDay()
                    )
                ],
                "previous" => [
                    "title" => 'שבוע שעבר',
                    "value" => $this->getWalkInCount(
                        Carbon::now()->startOfWeek(Carbon::SUNDAY)->subWeeks(1)->startOfDay(),
                        Carbon::now()->subWeek()->endOfDay()
                    ),
                ]
            ],
            "month" => [
                "current" => [
                    "title" => 'החודש',
                    "value" => $this->getWalkInCount(
                        Carbon::now()->startOfMonth()->startOfDay(),
                        Carbon::now()->endOfDay()
                    )
                ],
                "previous" => [
                    "title" => 'חודש שעבר',
                    "value" => $this->getWalkInCount(
                        Carbon::now()->startOfMonth()->subMonth()->startOfDay(),
                        Carbon::now()->subMonth()->endOfDay()
                    )
                ]
            ],
            "year" => [
                "current" => [
                    "title" => 'השנה',
                    "value" => $this->getWalkInCount(
                        Carbon::now()->startOfYear()->startOfDay(),
                        Carbon::now()->endOfDay()
                    )
                ],
                "previous" => [
                    "title" => 'שנה שעברה',
                    "value" => $this->getWalkInCount(
                        Carbon::now()->startOfYear()->startOfDay(),
                        Carbon::now()->subYear()->endOfDay()
                    )
                ],
            ],
        ];

        if (!is_array($stores))
            $stores->cache('summary', $summary, 60);

        return $summary;
    }

    /**
     * @param null $dateFrom
     * @param null $dateTo
     * @return array|\Illuminate\Http\JsonResponse|mixed
     */
    public function getWalkInCount($dateFrom = null, $dateTo = null)
    {
        if (!is_array($this->stores)) {
            $data = [
                "depId" => $this->stores->dep_id,
                "startTime" => date('Y-m-d H:i:s', strtotime($dateFrom ?? $this->dateFrom)),
                "endTime" => date('Y-m-d H:i:s', strtotime($dateTo ?? $this->dateTo))
            ];

            return $this->getSingleWalkInCount($data);
        } else {
            $response = 0;

            foreach ($this->stores as $store) {
                $data = [
                    "depId" => $store,
                    "startTime" => date('Y-m-d H:i:s', strtotime($dateFrom ?? $this->dateFrom)),
                    "endTime" => date('Y-m-d H:i:s', strtotime($dateTo ?? $this->dateTo))
                ];
                $count = $this->getSingleWalkInCount($data);

                if (!isset($count['error'])) {
                    $response += $count;
                } else {
                    return $count;
                }
            }

            return $response;
        }
    }

    /**
     * @param $data
     * @return array|mixed|string[]
     */
    protected function getSingleWalkInCount($data)
    {
        $params = $this->getRqParams('open.shopweb.passengerFlow.getPassengerIndicatorData', $data, "POST");

        $response = Http::withHeaders(["authenticator" => $this->token])
            ->accept('application/x-www-form-urlencoded')
            ->get($this->host . '?' . http_build_query((array)$params));

        if ($response->successful()) {
            return $response->json('data.passengerFlow');
        } else {
            $response->json('stat');
            \Log::info('Opretail error:: getSingleWalkInCount::', ['error' => $response->json('stat')] );

            return response()->json(['error' => 'Opretail API request failed'], $response->status());
        }
    }

    /**
     * @return array|\Illuminate\Http\JsonResponse|mixed
     */
    public function getStores()
    {
        $params = $this->getRqParams('open.shopweb.departments.getDeptListByPage', [], "POST");

        $response = Http::withHeaders(["authenticator" => $this->token])
            ->accept('application/x-www-form-urlencoded')
            ->get($this->host . '?' . http_build_query((array)$params));

        if ($response->successful()) {

            return $response->json('data.records');
        } else {
            \Log::info('Opretail error:: getStores:: ', ['error' => $response->json('stat')]);

            return response()->json(['error' => 'Opretail API request failed'], $response->status());
        }
    }

    /**
     * @return array|\Illuminate\Http\JsonResponse|mixed
     */
    public function getStoreData()
    {
        if (!is_array($this->stores)) {
            $storeIds = "S_{$this->stores->dep_id}";
        } else {
            $ids = implode(',S_', $this->stores);
            $storeIds = "S_{$ids}";
        }

        \Log::info('getStoreData', [
            "startTime" => date('Y-m-d H:i:s', strtotime($this->dateFrom)),
            "endTime" => date('Y-m-d H:i:s', strtotime($this->dateTo)),
            "startHour" => (int) date('H', strtotime($this->dateFrom)),
            "endHour" => (int) date('H', strtotime($this->dateTo))
        ]);
        $data = [
            "id" => $storeIds,
            "startTime" => date('Y-m-d H:i:s', strtotime($this->dateFrom)),
            "endTime" => date('Y-m-d H:i:s', strtotime($this->dateTo)),
            "timeType" => 1,
            "startHour" => (int) date('H', strtotime($this->dateFrom)),
            "endHour" => (int) date('H', strtotime($this->dateTo))
        ];

        $params = $this->getRqParams('open.passengerflow.getManyShopsPassengerIndicatorData', $data, "POST");

        $response = Http::withHeaders(["authenticator" =>$this->token])
            ->accept('application/x-www-form-urlencoded')
            ->get($this->host . '?' . http_build_query((array)$params));

        if ($response->successful()) {
            $storeData = $response->json('data');
            $hourlyWalkIn = [];

            foreach ($storeData as $data) {
                $hourlyWalkIn = array_merge($hourlyWalkIn, $data['dataList']);
            }
            $this->hourlyWalkIn = $this->mapHourlyWalkIn($hourlyWalkIn);

            return $response->json();
        } else {
            \Log::info('Opretail error:: getStoreData ::', ['error' => $response->json('stat'), 'request' => $data]);

            return response()->json(['error' => 'Opretail API request failed'], $response->status());
        }
    }

    /**
     * @return \Illuminate\Http\JsonResponse
     */
    public function getAgeGenderData()
    {
        if (!is_array($this->stores)) {
            $storeIds = "S_{$this->stores->dep_id}";
        } else {
            $ids = implode(',S_', $this->stores);
            $storeIds = "S_{$ids}";
        }

        $endHour = (int) date('H', strtotime($this->dateTo));
        $endHour = $endHour === 0 ? 24 : $endHour;
        \Log::info('getAgeGenderData', [
            "stime" => date('Y-m-d H:i:s', strtotime($this->dateFrom)),
            "etime" => date('Y-m-d H:i:s', strtotime($this->dateTo)),
            "startHour" => (int) date('H', strtotime($this->dateFrom)),
            "endHour" => $endHour
        ]);
        $data = [
            "id" => $storeIds,
            "stime" => date('Y-m-d H:i:s', strtotime($this->dateFrom)),
            "etime" => date('Y-m-d H:i:s', strtotime($this->dateTo)),
            "startHour" => (int) date('H', strtotime($this->dateFrom)),
            "endHour" => $endHour
        ];
        // alternative data
        // $params = $this->getRqParams('open.shopweb.passengerFlow.flowGroup.getFlowGroupDistribution', $data, "POST");

        $params = $this->getRqParams('open.shopweb.passengerFlow.flowGroup.getAgeGroupAndGenderDistribution', $data, "GET");

        $response = Http::withHeaders(["authenticator" =>$this->token])
            ->accept('application/x-www-form-urlencoded')
            ->get($this->host . '?' . http_build_query((array)$params));


        if ($response->successful()) {
            $this->genderData = $this->mapGender($response->json('data.genderDistribution'));
            $this->ageData = $this->mapAge($response->json('data.ageDistribution'));

            return response()->json(['errors' => false, 'data' => $response->json('data')], 200);
        } else {
            \Log::info('Opretail error :: getAgeGenderData ::', ['error' => $response->json(), 'request' => $data]);

            return response()->json(['errors' => true, 'message' => 'Opretail API request failed'], $response->status());
        }
    }
}
