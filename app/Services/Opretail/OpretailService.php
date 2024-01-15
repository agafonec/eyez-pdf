<?php


namespace App\Services\Opretail;


use App\Contracts\CustomersFlowInterface;
use App\Http\Controllers\Controller;
use App\Models\Store;
use App\Models\User;
use App\Traits\HasDateMap;
use Carbon\Carbon;
use Illuminate\Http\Request;

class OpretailService extends Controller implements CustomersFlowInterface
{
    use HasDateMap;
    protected OpretailApi $currentReport;
    protected OpretailApi $previousReport;
    public string $reportType;
    public array|null $summary;
    public float|null $avgWalkIn;
    public array $storeSalesReport;
    private User $user;

    /**
     * @param User $user
     * @return $this
     */
    public function setUser(User $user)
    {
        $this->user = $user;

        return $this;
    }

    /**
     * @param Request $request
     */
    public function getReportData(Request $request, Store|array $stores)
    {
        $this->reportType = 'hours';
        $opretail = $this->user?->opretailCredentials;

        // Transform query date to date from/to parameters.
        $dateRange = $this->getDateRange($request);

        $currentReport = new OpretailApi($opretail);
        $this->currentReport = $currentReport->getReport(
            $stores,
            $dateRange->start,
            $dateRange->end,
            $this->reportType
        );

        $newDateStart = Carbon::parse($dateRange->start)->subDays($dateRange->diffInDays);
        $newDateEnd = Carbon::parse($dateRange->end)->subDays($dateRange->diffInDays);

        $previousReport = new OpretailApi($opretail);
        $this->previousReport = $previousReport->getReport(
            $stores,
            $newDateStart->startOfDay(),
            $newDateEnd->endOfDay(),
            $this->reportType
        );

        $this->avgWalkIn = $this->avgWalkIn($currentReport, $dateRange->start);

        if ($this->reportType !== 'days') {
            if (!is_array($stores)) {
                $this->summary = $stores->cached('summary') ?? $currentReport->getSummary($stores);
            } else {
                $this->summary = $currentReport->getSummary($stores);
            }
        }

        return (object)[
            'reportType' => $this->reportType,
            'currentReport' => $this->currentReport,
            'previousReport' => $this->previousReport,
            'summary' => $this->summary,
            'avgWalkIn' => $this->avgWalkIn,
        ];
    }

    public function getWalkInCount($dateRange, $stores)
    {
        return (new OpretailApi(
            $this->user->opretailCredentials,
            $stores,
            Carbon::parse($dateRange->start)->startOfMonth()->startOfDay(),
            Carbon::parse($dateRange->start)->endOfMonth()->endOfDay(),
        ))->getWalkInCount();
    }

    /**
     * @param OpretailApi $opretailApi
     * @return float
     */
    private function avgWalkIn(OpretailApi $opretailApi, $dateFrom)
    {
        $from = Carbon::parse($dateFrom)->subDays(1)->startOfMonth()->startOfDay();
        $to = Carbon::now()->month !== Carbon::parse($dateFrom)->subDays(1)->month
            ? Carbon::parse($dateFrom)->subDays(1)->endOfMonth()->endOfDay()
            : Carbon::now()->subDays(1)->endOfDay();

        $walkInCount = $opretailApi->getWalkInCount(
            $from->startOfDay(),
            $to->endOfDay()
        );

        $workdays = $this->user?->settings['workdays'] ?? [];

        // Set the end date as today
        $diffInDays = $to->diffInDays($from);

        $count = 0;
        for ($i = 0; $i <= $diffInDays; $i++) {
            $currentDate = $to->copy()->subDays($i);

            if ( !in_array($currentDate->dayOfWeek, $workdays) ) {
                $count++;
            }
        }
        $avg = $count === 0 ? $walkInCount : $walkInCount / $count;

        return round($avg, 0);
    }
}
