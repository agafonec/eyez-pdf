<?php

namespace App\Http\Controllers;

use App\Contracts\CustomersFlowInterface;
use App\Models\Store;
use App\Models\User;
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
        $user = $this->user();

        return $this->getDashboard($request, $user);
    }

    /**
     * @param Request $request
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Foundation\Application|\Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector|\Inertia\Response
     */
    public function adminShow(Request $request, User $user)
    {
        return $this->getDashboard($request, $user);
    }

    /**
     * @param Request $request
     * @param User $user
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Foundation\Application|\Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector|\Inertia\Response
     */
    private function getDashboard(Request $request, User $user)
    {
        if (!$user->opretailCredentials || $user->opretailCredentials?->stores?->count() === 0 ) {
            return redirect('profile');
        }

        $settings = $user?->settings ?? [];
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
                $currentStore = $user->opretailCredentials->stores->whereNotIn('dep_id', $hiddenStores)->last();
            } else {
                $currentStore = $user->opretailCredentials->stores->last();
            }
        }

        $reports = $this->customersFlow->setUser($user)->getReportData($request, $currentStore);
        $this->storeSalesReport = $this->getSalesReport(
            $user,
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
            'stores' => $user->stores,
            'storeSales' => $this->storeSalesReport,
            'settings' => $settings,
            'roles' => $this->user()->getRoleNames(),
        ];

        return Inertia::render('Home', $homeParams);
    }

    /**
     * @param $user
     * @param $stores
     * @param $dateRange
     * @param $walkInCount
     * @return \array[][]
     */
    public function getSalesReport($user, $stores, $dateRange, $walkInCount)
    {
        $instance = is_array($stores) ? $user->opretailCredentials : $stores;

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
                    "title" => 'הכנסות',
                    "value" => $totalSales
                ],
                "previous" => [
                    "title" => 'ממוצע',
                    "value" => $avarageTotalSales
                ]
            ],
            "totalSalesCount" => [
                "current" => [
                    "title" => 'כמות עסקאות',
                    "value" => $instance->totalOrders($dateRange->start, $dateRange->end)
                ],
                "previous" => [
                    "title" => 'ממוצע',
                    "value" => $instance->totalOrders($dateRange->start, $dateRange->end, true)
                ]
            ],
            "atv" => [
                "current" => [
                    "title" => 'ממוצע עסקה',
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
                        $this->customersFlow->setUser($user)->getWalkInCount((object)[
                            'start' => Carbon::parse($dateRange->start)->startOfMonth()->startOfDay()->addSecond(),
                            'end' => Carbon::parse($dateRange->start)->endOfMonth()->endOfDay()->addSecond()
                        ], $stores),
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
