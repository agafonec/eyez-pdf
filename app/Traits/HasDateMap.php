<?php

namespace App\Traits;

use Carbon\Carbon;
use Illuminate\Http\Request;

/**
 * Class HasCache.
 */
trait HasDateMap
{
    /**
     * @param Request $request
     * @return \stdClass
     */
    private function getDateRange(Request $request)
    {
        $dateRange = new \stdClass();
        $this->reportType = 'hours';
        $dateRange->diffInDays = 1;
        if ($date = $request->input('date')) {
            $dateRange->start = Carbon::parse($date)->startOfDay();
            $dateRange->end = Carbon::parse($date)->endOfDay();
        } else if ($request->has('dateFrom') && $request->has('dateTo')) {
            $dateFrom = $request->input('dateFrom');
            $dateTo = $request->input('dateTo');

            $startTime = Carbon::parse($dateFrom);
            $endTime = Carbon::parse($dateTo);
            $dateRange->diffInDays = $startTime->diffInDays($endTime);
            $dateRange->diffInDays = $dateRange->diffInDays === 0 ? 1 : $dateRange->diffInDays;
            $this->reportType = $dateRange->diffInDays > 1 ? 'days' : 'hours';

            $dateRange->start = $startTime->startOfDay();
            $dateRange->end  = $endTime->endOfDay();
        } else {
            $dateRange->start = Carbon::now()->startOfDay();
            $dateRange->end = Carbon::now()->endOfDay();
        }

        return $dateRange;
    }
}
