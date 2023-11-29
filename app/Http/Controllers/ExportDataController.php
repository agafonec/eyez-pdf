<?php

namespace App\Http\Controllers;

use App\Models\Store;
use App\Traits\HasDateMap;
use Illuminate\Http\Request;

class ExportDataController extends Controller
{
    use HasDateMap;
    public string $reportType;

    public function getReportHistory(Request $request, Store|string $store)
    {
        $dateRange = $this->getDateRange($request);
        $instance = is_string($store) ? $this->user()->opretailCredentials : $store;
        $orders = $instance->ordersByDate($dateRange->start, $dateRange->end)
                    ->select('store_id', 'order_date', 'items_count', 'order_total')
                    ->get();

        return ['errors' => false, 'orders' => $orders];
    }
}
