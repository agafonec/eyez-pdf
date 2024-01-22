<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\Store
 *
 * @property int|null $dep_id
 */

class Store extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'name',
        'dep_id',
        'store_id',
        'organize_id'
    ];

    public function opretail()
    {
        return $this->belongsTo(Opretail::class, 'user_id', 'user_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function opretailCredentials()
    {
        if ($this->user->hasRole('child_user')) {
            return $this->user->parentUser->opretail();
        } else {
            return $this->opretail();
        }
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function getID()
    {
        return $this->id;
    }

    public function ordersSummary()
    {
        return $this->hasMany(OrdersSummary::class);
    }

    public function orders()
    {
        return $this->hasMany(Order::class);
    }

    public function passengerFlow()
    {
        return $this->hasMany(HourlyPassengerFlow::class);
    }

    /**
     * @param $dateFrom
     * @param $dateTo
     * @return mixed
     */
    public function getWalkInCount($stores, $dateFrom, $dateTo)
    {
        return $this->passengerFlow->whereIn('store_id', $stores)
            ->whereBetween('time', [$dateFrom, $dateTo])
            ->sum('passengerFlow');
    }

    public function ageGroups()
    {
        return $this->hasMany(AgeGroup::class);
    }

    public function ageGenderFlow()
    {
        return $this->hasMany(AgeGenderFlow::class);
    }

    public function cache($key, $value, $minutes = 30)
    {
        $cache_key = "store.{$this->dep_id}.$key";

        try {
            cache()->forget($cache_key);
            // TTL is in seconds since L5.8
            cache()->put($cache_key, $value, $minutes instanceof \DateTime ? $minutes : $minutes * 60);
        } catch (\Exception $e) {
        }

        return $this;
    }

    /**
     * @param $key
     * @param null $default
     * @return mixed
     */
    public function cached($key, $default = null)
    {
        try {
            return cache("store.{$this->dep_id}.$key", $default);
        } catch (\Exception $e) {
        }
    }

    /**
     * @param $key
     * @return $this
     */
    public function forgetCached($key)
    {
        try {
            cache()->forget("store.{$this->dep_id}.$key");
        } catch (\Exception $e) {
        }

        return $this;
    }

    /**
     * @param null $dateFrom
     * @param null $dateTo
     * @return int
     */
    public function totalOrders($dateFrom = null, $dateTo = null, $average = false)
    {
        $dateFrom = $dateFrom ?? Carbon::now()->startOfDay();
        $dateTo = $dateTo ?? Carbon::now()->endOfDay();

        if ($average) {
            $from = Carbon::parse($dateFrom)->subDays(1)->startOfMonth()->startOfDay();
            $to = Carbon::now()->month !== Carbon::parse($dateTo)->month
                ? Carbon::parse($dateTo)->endOfMonth()->endOfDay()
                : Carbon::now()->subDays(1)->endOfDay();

            $totalOrders = $this->orders()
                ->whereBetween('order_date', [$from, $to])
                ->count();
            return round($this->getAvarageValue($from, $to, $totalOrders), 0);
        } else {
            return $this->orders()
                ->whereBetween('order_date', [$dateFrom, $dateTo])
                ->count();
        }
    }

    /**
     * @param null $dateFrom
     * @param null $dateTo
     * @return int|mixed
     */
    public function totalSales($dateFrom = null, $dateTo = null, $average = false)
    {
        $dateFrom = $dateFrom ?? Carbon::now()->startOfDay();
        $dateTo = $dateTo ?? Carbon::now()->endOfDay();

        if ($average) {
            $from = Carbon::parse($dateFrom)->subDays(1)->startOfMonth()->startOfDay();
            $to = Carbon::now()->month !== Carbon::parse($dateTo)->month
                ? Carbon::parse($dateTo)->endOfMonth()->endOfDay()
                : Carbon::now()->subDays(1)->endOfDay();

            $totalSales = $this->orders()
                ->whereBetween('order_date', [$from, $to])
                ->sum('order_total');

            return round($this->getAvarageValue($from, $to, $totalSales), 0);
        } else {
            return round($this->orders()
                ->whereBetween('order_date', [$dateFrom, $dateTo])
                ->sum('order_total'), 0);
        }
    }

    /**
     * @param null $dateFrom
     * @param null $dateTo
     * @return int|mixed
     */
    public function totalItemsSold($dateFrom = null, $dateTo = null, $average = false)
    {
        $dateFrom = $dateFrom ?? Carbon::now()->startOfDay();
        $dateTo = $dateTo ?? Carbon::now()->endOfDay();

        if ($average) {
            $from = Carbon::parse($dateFrom)->subDays(1)->startOfMonth()->startOfDay();
            $to = Carbon::now()->month !== Carbon::parse($dateTo)->month
                ? Carbon::parse($dateTo)->endOfMonth()->endOfDay()
                : Carbon::now()->subDays(1)->endOfDay();

            $itemsCount = $this->orders()
                ->whereBetween('order_date', [$from, $to])
                ->sum('items_count');

            return round($this->getAvarageValue($from, $to, $itemsCount), 0);
        } else {
            return $this->orders()
                ->whereBetween('order_date', [$dateFrom, $dateTo])
                ->sum('items_count');
        }
    }

    /**
     * @param null $dateFrom
     * @param null $dateTo
     * @return float|int
     */
    public function getATV($dateFrom = null, $dateTo = null, $average = false)
    {
        if ($average) {
            $from = Carbon::parse($dateFrom)->subDays(1)->startOfMonth()->startOfDay();
            $to = Carbon::now()->month !== Carbon::parse($dateTo)->month
                ? Carbon::parse($dateTo)->endOfMonth()->endOfDay()
                : Carbon::now()->subDays(1)->endOfDay();

            $totalSales = $this->totalSales($from, $to);
            $totalOrders = $this->totalOrders($from, $to);

            return $totalOrders ? round($totalSales / $totalOrders, 0) : 0;
        }

        $totalSales = $this->totalSales($dateFrom, $dateTo);
        $totalOrders = $this->totalOrders($dateFrom, $dateTo);

        return $totalOrders ? round($totalSales / $totalOrders, 0) : 0;
    }

    /**
     * @param $walkInCount
     * @param null $dateFrom
     * @param null $dateTo
     * @param false $average
     * @return float|int
     */
    public function closeRate($walkInCount, $dateFrom = null, $dateTo = null, $average = false)
    {
        $dateFrom = $dateFrom ?? Carbon::now()->startOfDay();
        $dateTo = $dateTo ?? Carbon::now()->endOfDay();
        if ($average) {
            $dateFrom = $dateFrom->startOfMonth()->startOfDay();
            $dateTo = $dateTo->endOfMonth()->endOfDay();
            $totalOrders = $this->totalOrders($dateFrom, $dateTo);

            return $walkInCount ? round($totalOrders / $walkInCount * 100, 0) : 0;
        }

        $totalOrders = $this->totalOrders($dateFrom, $dateTo);

        return $walkInCount ? round($totalOrders / $walkInCount * 100, 0) : 0;
    }

    /**
     * @param null $dateFrom
     * @param null $dateTo
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function ordersByDate($dateFrom = null, $dateTo = null)
    {
        $dateFrom = $dateFrom ?? Carbon::now()->startOfDay();
        $dateTo = $dateTo ?? Carbon::now()->endOfDay();

        return $this->orders()
            ->whereBetween('order_date', [$dateFrom, $dateTo]);
    }

    /**
     * @param $dateFrom
     * @param $dateTo
     * @param $value
     * @return float
     */
    public function getAvarageValue($dateFrom, $dateTo, $value)
    {
        $workdays = $this->opretail?->settings['workdays'] ?? [];

        // Set the end date as today
        $startDate = Carbon::parse($dateFrom);
        $endDate = Carbon::now()->month !== Carbon::parse($dateTo)->month
            ? Carbon::parse($dateTo)->endOfMonth()->endOfDay()
            : Carbon::now()->subDays(1)->endOfDay();
        $diffInDays = $endDate->diffInDays($startDate);

        $count = 0;
        for ($i = 0; $i <= $diffInDays; $i++) {
            $currentDate = $endDate->copy()->subDays($i);

            if (!in_array($currentDate->dayOfWeek, $workdays)) {
                $count++;
            }
        }

        $avg = $count === 0 ? $value : $value / $count;

        return round($avg, 1);
    }

    /**
     * @return Carbon
     */
    public function firstAvailableDate()
    {
        $firstAvailableDate = $this->orders()->orderBy('order_date')->pluck('order_date')->first()
            ?? $this->passengerFlow()->orderBy('time')->pluck('time')->first()
            ?? Carbon::now();
        return Carbon::parse($firstAvailableDate);
    }
}
