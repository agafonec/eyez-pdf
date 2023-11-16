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
            'stores' => $this->user()->stores
        ]);
    }
}
