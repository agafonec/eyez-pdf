<?php

namespace App\Http\Controllers;

use App\Models\Store;
use Carbon\Carbon;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;
use App\Services\Opretail\OpretailApi;

class IndexController extends Controller
{
    protected OpretailApi $currentReport;
    protected OpretailApi $previousReport;
    public string $reportType;

    public function __construct()
    {
    }

    /**
     * @param Request $request
     */
    protected function getReportData(Request $request, Store $store)
    {
        $this->reportType = 'hours';
        $opretail = $this->user()->opretailCredentials;

        if ($date = $request->input('date')) {
            $startTime = Carbon::parse($date)->startOfDay();
            $endTime = Carbon::parse($date)->endOfDay();
        } else if ($request->has('dateTo') && $request->has('dateTo')) {
            $dateFrom = $request->input('dateFrom');
            $dateTo = $request->input('dateTo');

            $startTime = Carbon::parse($dateFrom);
            $endTime = Carbon::parse($dateTo);

            $this->reportType = $startTime->diffInDays($endTime) > 0 ? 'days' : 'hours';

            $startTime = $startTime->startOfDay();
            $endTime = $endTime->endOfDay();
        } else {
            $startTime = Carbon::now()->startOfDay();
            $endTime = Carbon::now()->endOfDay();
        }

        $currentReport = new OpretailApi($opretail);
        $this->currentReport = $currentReport->getReport(
            $store,
            $startTime,
            $endTime,
            $this->reportType
        );

        $newDateStart = Carbon::parse($startTime)->subDays(1);
        $newDateEnd = Carbon::parse($endTime)->subDays(1);

        $previousReport = new OpretailApi($opretail);
        $this->previousReport = $previousReport->getReport(
            $store,
            $newDateStart->startOfDay(),
            $newDateEnd->endOfDay(),
            $this->reportType
        );

        $this->summary = $store->cached('summary') ?? $currentReport->getSummary($store);
        $this->avgWalkIn = $this->avgWalkIn($currentReport);
    }

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
        return $avg;
    }

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
            'stores' => $this->user()->stores
        ]);
    }

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
