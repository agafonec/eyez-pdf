<?php


namespace App\Contracts;


use App\Models\Store;
use App\Models\User;
use App\Traits\HasDateMap;
use Illuminate\Http\Request;

interface CustomersFlowInterface
{
    public function setUser(User $user);
    public function getReportData(Request $request, Store|array $stores);
    public function getWalkInCount($dateRange, $stores);
}
