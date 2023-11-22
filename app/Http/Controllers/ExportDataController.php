<?php

namespace App\Http\Controllers;

use App\Models\Store;
use App\Traits\HasDateMap;
use Illuminate\Http\Request;

class ExportDataController extends Controller
{
    use HasDateMap;
    public string $reportType;

    public function getReportHistory(Request $request, Store $store)
    {
        $dateRange = $this->getDateRange($request);
        $orders = $store->ordersByDate($dateRange->start, $dateRange->end)
                    ->select('order_date', 'items_count', 'order_total')
                    ->get();

        return ['errors' => false, 'orders' => $orders];
    }
}
