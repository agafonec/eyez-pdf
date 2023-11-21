<?php

namespace App\Http\Controllers;

use App\Models\Store;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Services\Opretail\OpretailApi;

class IndexController extends Controller
{
    protected OpretailApi $currentReport;
    protected OpretailApi $previousReport;
    public string $reportType;
    public array|null $summary;
    public float|null $avgWalkIn;
    public array $storeSalesReport;

    public function __construct()
    {
        $this->summary = null;
        $this->avgWalkIn = null;
    }

    /**
     * @param Request $request
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Foundation\Application|\Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector|\Inertia\Response
     */
    public function show(Request $request)
    {
        if (!$this->user()->opretailCredentials) {
            return redirect('profile');
        }

        $store = $request->has('storeId')
            ? Store::where('dep_id', $request->query('storeId'))->first()
            : $this->user()->opretailCredentials->stores->last();

        $this->getReportData($request, $store);
        return Inertia::render('Home', [
            'currentStore' => $store,
            'reportType' => $this->reportType,
            'storeData' => $this->currentReport,
            'prevStoreData' => $this->previousReport,
            'summary' => $this->summary,
            'avgWalkIn' => $this->avgWalkIn,
            'stores' => $this->user()->stores,
            'storeSales' => $this->storeSalesReport
        ]);
    }

    /**
     * @param Request $request
     */
    protected function getReportData(Request $request, Store $store)
    {
        $this->reportType = 'hours';
        $opretail = $this->user()->opretailCredentials;

        // Transform query date to date from/to parameters.
        $dateRange = $this->getDateRange($request);

        $currentReport = new OpretailApi($opretail);
        $this->currentReport = $currentReport->getReport(
            $store,
            $dateRange->start,
            $dateRange->end,
            $this->reportType
        );

        $newDateStart = Carbon::parse($dateRange->start)->subDays($dateRange->diffInDays);
        $newDateEnd = Carbon::parse($dateRange->end)->subDays($dateRange->diffInDays);

        $previousReport = new OpretailApi($opretail);
        $this->previousReport = $previousReport->getReport(
            $store,
            $newDateStart->startOfDay(),
            $newDateEnd->endOfDay(),
            $this->reportType
        );

        if ($this->reportType !== 'days') {
            $this->summary = $store->cached('summary') ?? $currentReport->getSummary($store);
            $this->avgWalkIn = $this->avgWalkIn($currentReport);
        }

        $this->storeSalesReport = [
            "itemsSold" => [
                "current" => [
                    "title" => 'סה״כ פריטים',
                    "value" => $store->totalItemsSold($dateRange->start, $dateRange->end)
                ],
                "previous" => [
                    "title" => 'last period',
                    "value" => $store->totalItemsSold($newDateStart->startOfDay(), $newDateEnd->endOfDay())
                ]
            ],
            "totalSales" => [
                "current" => [
                    "title" => 'סה"כ מכירה',
                    "value" => $store->totalSales($dateRange->start, $dateRange->end)
                ],
                "previous" => [
                    "title" => 'last period',
                    "value" => $store->totalSales($newDateStart->startOfDay(), $newDateEnd->endOfDay())
                ]
            ],
            "atv" => [
                "current" => [
                    "title" => 'טרקטורון',
                    "value" => $store->getATV($dateRange->start, $dateRange->end)
                ],
                "previous" => [
                    "title" => 'last period',
                    "value" => $store->getATV($newDateStart->startOfDay(), $newDateEnd->endOfDay())
                ]
            ],
            "closeRate" => [
                "current" => [
                    "title" => 'קונה/רוכש (%)',
                    "value" => $store->closeRate($dateRange->start, $dateRange->end, $this->currentReport?->walkInCount)
                ],
                "previous" => [
                    "title" => 'last period',
                    "value" => $store->closeRate($newDateStart->startOfDay(), $newDateEnd->endOfDay(), $this->previousReport?->walkInCount)
                ]
            ]
        ];
    }

    /**
     * @param Request $request
     * @return \stdClass
     */
    private function getDateRange(Request $request)
    {
        $dateRange = new \stdClass();
        $dateRange->diffInDays = 1;
        if ($date = $request->input('date')) {
            $dateRange->start = Carbon::parse($date)->startOfDay();
            $dateRange->end = Carbon::parse($date)->endOfDay();
        } else if ($request->has('dateTo') && $request->has('dateTo')) {
            $dateFrom = $request->input('dateFrom');
            $dateTo = $request->input('dateTo');

            $startTime = Carbon::parse($dateFrom);
            $endTime = Carbon::parse($dateTo);
            $dateRange->diffInDays = $startTime->diffInDays($endTime);
            $this->reportType = $dateRange->diffInDays > 1 ? 'days' : 'hours';

            $dateRange->start = $startTime->startOfDay();
            $dateRange->end  = $endTime->endOfDay();
        } else {
            $dateRange->start = Carbon::now()->startOfDay();
            $dateRange->end = Carbon::now()->endOfDay();
        }

        return $dateRange;
    }

    /**
     * @param OpretailApi $opretailApi
     * @return float
     */
    private function avgWalkIn(OpretailApi $opretailApi)
    {
        $opretail = $this->user()?->opretailCredentials;
        if ($avg = $opretail->cached('avgWalkIn')) {
            return $avg;
        }

        $walkInCount = $opretailApi->getWalkInCount(
            Carbon::now()->subDays(31)->startOfDay(),
            Carbon::now()->subDays(1)->endOfDay()
        );

        if ($workdays = $opretail?->workdays) {
            // Set the end date as today
            $endDate = Carbon::today();
            $count = 0;
            for ($i = 1; $i < 31; $i++) {
                $currentDate = $endDate->copy()->subDays($i);

                if ( !in_array($currentDate->dayOfWeek, $workdays) ) {
                    $count++;
                }
            }

            $avg = $walkInCount / $count;
        } else {

            $avg = $walkInCount / 30;
        }
        $opretail->cache('avgWalkIn', $avg, 60);
        return round($avg);
    }

    /**
     * @param Request $request
     * @return string[]
     */
    public function clearStoreCache(Request $request)
    {
        $store = $request->has('storeId')
            ? Store::where('dep_id', $request->json('storeId'))->first()
            : $this->user()->opretailCredentials->stores->last();
        $opretail = $this->user()?->opretailCredentials;

        $store->forgetCached('summary');
        $opretail->forgetCached('avgWalkIn');

        return ['message' => 'Cache successfully cleaned. Reloading the page..'];
    }
}
