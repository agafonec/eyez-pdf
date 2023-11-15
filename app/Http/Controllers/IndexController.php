<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Services\Opretail\OpretailApi;

class IndexController extends Controller
{
    protected OpretailApi $currentReport;
    protected OpretailApi $previousReport;
    public string $reportType;

    public function __construct(Request $request)
    {
        $this->reportType = 'hours';
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

        $this->currentReport = new OpretailApi($startTime, $endTime, 11025, $this->reportType);

        $newDateStart = Carbon::parse($startTime)->subDays(1);
        $newDateEnd = Carbon::parse($endTime)->subDays(1);
        $this->previousReport = new OpretailApi($newDateStart->startOfDay(), $newDateEnd->endOfDay(), 11025, $this->reportType);
    }

    public function show() {

        return Inertia::render('Home', [
            'storeName' => 'Astral',
            'reportType' => $this->reportType,
            'storeData' => $this->currentReport,
            'prevStoreData' => $this->previousReport,
        ]);
    }
}
