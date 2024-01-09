<?php


namespace App\Contracts;


use App\Models\Store;
use App\Traits\HasDateMap;
use Illuminate\Http\Request;

interface CustomersFlowInterface
{
    public function getReportData(Request $request, Store|array $stores);
}
