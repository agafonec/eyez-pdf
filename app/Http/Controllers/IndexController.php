<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Services\Opretail\OpretailApi;

class IndexController extends Controller
{
    //
    protected OpretailApi $currentReport;
    protected OpretailApi $previousReport;

    public function __construct(Request $request)
    {
        if ($date = $request->input('date')) {
            $startTime = Carbon::parse($date)->startOfDay();
            $endTime =  Carbon::parse($date)->endOfDay();
        } else {
            $startTime = Carbon::now()->startOfDay();
            $endTime = Carbon::now()->endOfDay();
        }

        $this->currentReport = new OpretailApi($startTime, $endTime, 11025);

        $newDateStart = Carbon::parse($startTime)->subDays(1);
        $newDateEnd = Carbon::parse($endTime)->subDays(1);
        $this->previousReport = new OpretailApi($newDateStart->startOfDay(), $newDateEnd->endOfDay(), 11025);
    }

    public function show() {
        return Inertia::render('Home', [
            'storeName' => 'Astral',
            'storeData' => $this->currentReport,
            'prevStoreData' => $this->previousReport,
        ]);
    }
}
