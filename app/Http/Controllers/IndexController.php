<?php

namespace App\Http\Controllers;

use App\Contracts\CustomersFlowInterface;
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

    public function __construct(
        protected CustomersFlowInterface $customersFlow,
    )
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

        // TODO replace with interface
        $reports = $this->customersFlow->getReportData($request, $currentStore);
        $this->storeSalesReport = $this->getSalesReport(
            $currentStore,
            $this->getDateRange($request),
            $reports->currentReport?->walkInCount,
        );
        $homeParams = [
            'currentStore' => is_array($currentStore) ? implode(',', $currentStore) : $currentStore,
            'reportType' => $reports->reportType,
            'storeData' => $reports->currentReport,
            'prevStoreData' => $reports->previousReport,
            'summary' => $reports->summary,
            'avgWalkIn' => $reports->avgWalkIn,
            'stores' => $this->user()->stores,
            'storeSales' => $this->storeSalesReport,
            'settings' => $settings
        ];

        return Inertia::render('Home', $homeParams);
    }

    public function getSalesReport($stores, $dateRange, $walkInCount)
    {
        // TODO replace with interface
        $report = new OpretailApi(
            $this->user()->opretailCredentials,
            $stores,
            Carbon::parse($dateRange->start)->startOfMonth()->startOfDay(),
            Carbon::parse($dateRange->start)->endOfMonth()->endOfDay(),
        );

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
                    "value" => $instance->closeRate(
                        $report->getWalkInCount(),
                        Carbon::parse($dateRange->start)->startOfMonth()->startOfDay(),
                        Carbon::parse($dateRange->start)->endOfMonth()->endOfDay(),
                        true
                    )
                ]
            ]
        ];
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
