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
            'storeSales' => $this->storeSalesReport,
            'settings' => $this->user()?->opretailCredentials?->settings
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

        $this->avgWalkIn = $this->avgWalkIn($currentReport, $dateRange->start);
        if ($this->reportType !== 'days') {
            $this->summary = $store->cached('summary') ?? $currentReport->getSummary($store);
        }

        $itemsSold = $store->totalItemsSold($dateRange->start, $dateRange->end);
        $avarageItemsSold = $store->totalItemsSold($dateRange->start, $dateRange->end, true);

        $totalSales = $store->totalSales($dateRange->start, $dateRange->end);
        $avarageTotalSales = $store->totalSales($dateRange->start, $dateRange->end, true);
        $this->storeSalesReport = [
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
                    "title" => 'סה"כ מכירה',
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
                    "value" => $store->totalOrders($dateRange->start, $dateRange->end)
                ],
                "previous" => [
                    "title" => 'ממוצע',
                    "value" => $store->totalOrders($dateRange->start, $dateRange->end, true)
                ]
            ],
            "atv" => [
                "current" => [
                    "title" => 'שווי עסקה',
                    "value" => $store->getATV($dateRange->start, $dateRange->end)
                ],
                "previous" => [
                    "title" => 'ממוצע',
                    "value" => $store->getATV($dateRange->start, $dateRange->end, true)
                ]
            ],
            "closeRate" => [
                "current" => [
                    "title" => 'יחס המרה',
                    "value" => $store->closeRate($this->currentReport?->walkInCount, $dateRange->start, $dateRange->end)
                ],
                "previous" => [
                    "title" => 'ממוצע',
                    "value" => $store->closeRate($this->avgWalkIn, $dateRange->start, $dateRange->end, true)
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
        $opretail = $this->user()?->opretailCredentials;
//        if ($avg = $opretail->cached('avgWalkIn')) {
//            return  round($avg, 0);
//        }

        $walkInCount = $opretailApi->getWalkInCount(
            Carbon::parse($dateFrom)->subDays(1)->startOfMonth()->startOfDay(),
            Carbon::parse($dateFrom)->subDays(1)->endOfDay()
        );

        \Log::info('opretail settings', ['settings' => $opretail?->settings]);
        if (isset($opretail?->settings['workdays']) && $workdays = $opretail?->settings['workdays']) {
            // Set the end date as today
            $endDate = Carbon::today();
            $count = 0;
            for ($i = 1; $i < 26; $i++) {
                $currentDate = $endDate->copy()->subDays($i);

                if ( !in_array($currentDate->dayOfWeek, $workdays) ) {
                    $count++;
                }
            }
            $avg = $walkInCount / $count;
        } else {
            $avg = $walkInCount / 25;
        }
//        $opretail->cache('avgWalkIn', $avg, 60);
        return round($avg, 0);
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
