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

    public function __construct()
    {
        $startTime = '2023-10-18 00:00:00';
        $endTime = '2023-10-18 23:59:59';
        $this->currentReport = new OpretailApi($startTime, $endTime, 11025);

        $newDateStart = Carbon::parse($startTime)->subDays(1);
        $newDateEnd = Carbon::parse($endTime)->subDays(1);
        $this->previousReport = new OpretailApi($newDateStart->startOfDay(), $newDateEnd->endOfDay(), 11025);
    }

    public function show() {

        \Log::info('Opretail data', ['opretail' => $this->currentReport]);
        \Log::info('Previous Opretail data', ['opretail' => $this->previousReport]);

        return Inertia::render('Home', [
            'storeName' => 'Astral',
            'storeData' => $this->currentReport,
            'prevStoreData' => $this->previousReport,
        ]);
    }
}
