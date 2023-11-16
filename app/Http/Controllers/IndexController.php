<?php

namespace App\Http\Controllers;

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
    protected function getReportData(Request $request)
    {
        $this->reportType = 'hours';
        $opretail = $this->user()->opretailCredentials;
        $store = $opretail->stores->last();

        \Log::info('store', ['store' => $store]);
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
//        $this->currentReport->summary = [
//            "today" => (new OpretailApi(Carbon::now()->startOfDay(), Carbon::now()->endOfDay(), 11025, $this->reportType))->getWalkInCount(),
//            "lastMonth" => (new OpretailApi(Carbon::now()->subMonths(1)->startOfDay(), Carbon::now()->endOfDay(), 11025, $this->reportType))->getWalkInCount(),
//            "lastYear" => (new OpretailApi(Carbon::now()->subYears(1)->startOfDay(), Carbon::now()->endOfDay(), 11025, $this->reportType))->getWalkInCount(),
//        ];

        $newDateStart = Carbon::parse($startTime)->subDays(1);
        $newDateEnd = Carbon::parse($endTime)->subDays(1);

        $previousReport = new OpretailApi($opretail);
        $this->previousReport = $previousReport->getReport(
            $store,
            $newDateStart->startOfDay(),
            $newDateEnd->endOfDay(),
            $this->reportType
        );

//        $this->previousReport->summary = [
//            "today" => (new OpretailApi(Carbon::now()->startOfDay(), Carbon::now()->endOfDay(), 11025, $this->reportType))->getWalkInCount(),
//            "lastMonth" => (new OpretailApi(Carbon::now()->subMonths(1)->startOfDay(), Carbon::now()->endOfDay(), 11025, $this->reportType))->getWalkInCount(),
//            "lastYear" => (new OpretailApi(Carbon::now()->subYears(1)->startOfDay(), Carbon::now()->endOfDay(), 11025, $this->reportType))->getWalkInCount(),
//        ];
    }

    public function show(Request $request)
    {
        if (!$this->user()->opretailCredentials) {
            return redirect('profile');
        }

        $this->getReportData($request);
        return Inertia::render('Home', [
            'storeName' => 'Astral',
            'reportType' => $this->reportType,
            'storeData' => $this->currentReport,
            'prevStoreData' => $this->previousReport,
        ]);
    }
}
