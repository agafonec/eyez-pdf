<?php

namespace App\Http\Controllers;

use App\Models\Store;
use App\Traits\HasDateMap;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Services\Opretail\OpretailApi;

class IndexController extends Controller
{
    use HasDateMap;
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

        $settings = $this->user()?->settings ?? [];
        $hiddenStores = $settings['hiddenStores'] ?? [];
        // It can be one or multiple stores.
        if ($request->has('stores')) {
            $query = $request->query('stores');

            if (str_contains($query, ',')) {
                $currentStore = explode(',', $query);
                $currentStore = array_filter($currentStore, fn($storeId) => !in_array((int)$storeId, $hiddenStores));
            } else {
                $currentStore = Store::where('dep_id', $query)->first();
            }
        } else {
            if ($hiddenStores) {
                $currentStore = $this->user()->opretailCredentials->stores->whereNotIn('dep_id', $hiddenStores)->last();
            } else {
                $currentStore = $this->user()->opretailCredentials->stores->last();
            }
        }

        $this->getReportData($request, $currentStore);
        $homeParams = [
            'currentStore' => is_array($currentStore) ? implode(',', $currentStore) : $currentStore,
            'reportType' => $this->reportType,
            'storeData' => $this->currentReport,
            'prevStoreData' => $this->previousReport,
            'summary' => $this->summary,
            'avgWalkIn' => $this->avgWalkIn,
            'stores' => $this->user()->stores,
            'storeSales' => $this->storeSalesReport,
            'settings' => $settings
        ];

        return Inertia::render('Home', $homeParams);
    }

    /**
     * @param Request $request
     */
    protected function getReportData(Request $request, Store|array $stores)
    {
        $this->reportType = 'hours';
        $opretail = $this->user()->opretailCredentials;

        // Transform query date to date from/to parameters.
        $dateRange = $this->getDateRange($request);

        $currentReport = new OpretailApi($opretail);
        $this->currentReport = $currentReport->getReport(
            $stores,
            $dateRange->start,
            $dateRange->end,
            $this->reportType
        );

        $newDateStart = Carbon::parse($dateRange->start)->subDays($dateRange->diffInDays);
        $newDateEnd = Carbon::parse($dateRange->end)->subDays($dateRange->diffInDays);

        $previousReport = new OpretailApi($opretail);
        $this->previousReport = $previousReport->getReport(
            $stores,
            $newDateStart->startOfDay(),
            $newDateEnd->endOfDay(),
            $this->reportType
        );

        $this->avgWalkIn = $this->avgWalkIn($currentReport, $dateRange->start);

        if ($this->reportType !== 'days') {
            if (!is_array($stores)) {
                $this->summary = $stores->cached('summary') ?? $currentReport->getSummary($stores);
            } else {
                $this->summary = $currentReport->getSummary($stores);
            }
        }

        $this->storeSalesReport = $this->getSalesReport(
            $stores,
            $dateRange,
            $this->currentReport?->walkInCount,
            $this->avgWalkIn
        );
    }

    public function getSalesReport($stores, $dateRange, $walkInCount, $avgWalkIn)
    {
        $instance = is_array($stores) ? $this->user()->opretailCredentials : $stores;

        $itemsSold = $instance->totalItemsSold($dateRange->start, $dateRange->end);
        $avarageItemsSold = $instance->totalItemsSold($dateRange->start, $dateRange->end, true);

        $totalSales = $instance->totalSales($dateRange->start, $dateRange->end);
        $avarageTotalSales = $instance->totalSales($dateRange->start, $dateRange->end, true);

        return  [
            "productPrice" => [
                "current" => [
                    "title" => 'ממוצע שווי פריט',
                    "value" => $itemsSold > 0 ? round($totalSales/$itemsSold) : 0
                ],
                "previous" => [
                    "title" => 'ממוצע',
                    "value" => $avarageItemsSold > 0 ? round($avarageTotalSales/$avarageItemsSold) : 0
                ]
            ],
            "itemsSold" => [
                "current" => [
                    "title" => 'פריטים שנמכרו',
                    "value" => $itemsSold
                ],
                "previous" => [
                    "title" => 'ממוצע',
                    "value" => $avarageItemsSold
                ]
            ],
            "totalSales" => [
                "current" => [
                    "title" => 'מכירות',
                    "value" => $totalSales
                ],
                "previous" => [
                    "title" => 'ממוצע',
                    "value" => $avarageTotalSales
                ]
            ],
            "totalSalesCount" => [
                "current" => [
                    "title" => 'עסקאות',
                    "value" => $instance->totalOrders($dateRange->start, $dateRange->end)
                ],
                "previous" => [
                    "title" => 'ממוצע',
                    "value" => $instance->totalOrders($dateRange->start, $dateRange->end, true)
                ]
            ],
            "atv" => [
                "current" => [
                    "title" => 'שווי עסקה',
                    "value" => $instance->getATV($dateRange->start, $dateRange->end)
                ],
                "previous" => [
                    "title" => 'ממוצע',
                    "value" => $instance->getATV($dateRange->start, $dateRange->end, true)
                ]
            ],
            "closeRate" => [
                "current" => [
                    "title" => 'יחס המרה',
                    "value" => $instance->closeRate($walkInCount, $dateRange->start, $dateRange->end)
                ],
                "previous" => [
                    "title" => 'ממוצע',
                    "value" => $instance->closeRate($avgWalkIn, $dateRange->start, $dateRange->end, true)
                ]
            ]
        ];
    }

    /**
     * @param OpretailApi $opretailApi
     * @return float
     */
    private function avgWalkIn(OpretailApi $opretailApi, $dateFrom)
    {
        $from = Carbon::parse($dateFrom)->subDays(1)->startOfMonth()->startOfDay();
        $to = Carbon::now()->month !== Carbon::parse($dateFrom)->subDays(1)->month
            ? Carbon::parse($dateFrom)->subDays(1)->endOfMonth()->endOfDay()
            : Carbon::now()->subDays(1)->endOfDay();

        $walkInCount = $opretailApi->getWalkInCount(
            $from->startOfDay(),
            $to->endOfDay()
        );

        $workdays = $this->user()?->settings['workdays'] ?? [];

        // Set the end date as today
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

    /**
     * @param Request $request
     * @return string[]
     */
    public function clearStoreCache(Request $request)
    {
        $store = $request->has('storeId') && !is_null($request->json('storeId'))
            ? Store::where('dep_id', $request->json('storeId'))->first()
            : $this->user()->opretailCredentials->stores->last();
        $opretail = $this->user()?->opretailCredentials;

        $store->forgetCached('summary');
        $opretail->forgetCached('avgWalkIn');

        return ['message' => 'Cache successfully cleaned. Reloading the page..'];
    }
}
