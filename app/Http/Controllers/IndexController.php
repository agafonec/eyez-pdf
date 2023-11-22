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
                    "title" => 'תקופה קודמת',
                    "value" => $store->totalItemsSold($newDateStart->startOfDay(), $newDateEnd->endOfDay())
                ]
            ],
            "totalSales" => [
                "current" => [
                    "title" => 'סה"כ מכירה',
                    "value" => $store->totalSales($dateRange->start, $dateRange->end)
                ],
                "previous" => [
                    "title" => 'תקופה קודמת',
                    "value" => $store->totalSales($newDateStart->startOfDay(), $newDateEnd->endOfDay())
                ]
            ],
            "atv" => [
                "current" => [
                    "title" => 'ממוצע עסקה',
                    "value" => $store->getATV($dateRange->start, $dateRange->end)
                ],
                "previous" => [
                    "title" => 'תקופה קודמת',
                    "value" => $store->getATV($newDateStart->startOfDay(), $newDateEnd->endOfDay())
                ]
            ],
            "closeRate" => [
                "current" => [
                    "title" => 'יחס המרה',
                    "value" => $store->closeRate($dateRange->start, $dateRange->end, $this->currentReport?->walkInCount)
                ],
                "previous" => [
                    "title" => 'תקופה קודמת',
                    "value" => $store->closeRate($newDateStart->startOfDay(), $newDateEnd->endOfDay(), $this->previousReport?->walkInCount)
                ]
            ]
        ];
    }

    /**
     * @param OpretailApi $opretailApi
     * @return float
     */
    private function avgWalkIn(OpretailApi $opretailApi)
    {
        $opretail = $this->user()?->opretailCredentials;
        if ($avg = $opretail->cached('avgWalkIn')) {
            return  round($avg, 0);
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
