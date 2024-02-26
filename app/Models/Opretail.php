<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Crypt;

/**
 * App\Models\Opretail
 *
 * @property int|null $user_id
 */

class Opretail extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'username',
        'password',
        'secret_key',
        '_akey',
        '_aid',
        'enterpriseId',
        'orgId',
    ];

    public function setPasswordAttribute($value)
    {
        $this->attributes['password'] = Crypt::encrypt($value);
    }

    public function getPasswordAttribute($value)
    {
        return Crypt::decrypt($value);
    }
    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function stores()
    {
        return $this->hasMany(Store::class, 'user_id', 'user_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * @param $key
     * @param $value
     * @param int $minutes
     * @return $this
     */
    public function cache($key, $value, $minutes = 30)
    {
        $cache_key = "opretail.{$this->user_id}.$key";

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
            return cache("opretail.{$this->user_id}.$key", $default);
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
            cache()->forget("opretail.{$this->user_id}.$key");
        } catch (\Exception $e) {
        }

        return $this;
    }

    /**
     * @return mixed[]
     */
    public function getStoreIdsArray()
    {
        return $this->stores()->pluck('id')->toArray();
    }

    /**
     * @param $dateFrom
     * @param $dateTo
     * @param array $stores
     * @param string $dateParamName
     * @return string
     */
    protected function getQuery($dateFrom, $dateTo, $dateParamName = 'date')
    {
        $stores = $this->getStoreIdsArray();
        $query = '';
        foreach ($stores as $store) {
            $storeObject = Store::find($store);
            $singleQuery = $storeObject->getDateQuery($dateFrom, $dateTo, $dateParamName);

            $query .= empty($query)
                ? "({$singleQuery})"
                : "OR ({$singleQuery})";
        }

        return $query;
    }

    /**
     * @param $dateFrom
     * @param $dateTo
     * @param $store
     * @param string $dateParamName
     * @return string
     */
    public function getDateQuery($dateFrom, $dateTo, $dateParamName = 'date')
    {
        $startDate  = Carbon::parse($dateFrom);
        $diffInDays = Carbon::parse($dateTo)->diffInDays($startDate);

        $query = '';
        for ($i = 0; $i <= $diffInDays; $i++) {
            $currentDate = $startDate->copy()->addDays($i);
            $limitedDates = $this->modifyDate($currentDate, $this);
            $query .= empty($query)
                ? "({$dateParamName} BETWEEN '{$limitedDates['startDate']}' AND '{$limitedDates['endDate']}')"
                : " OR ({$dateParamName} BETWEEN '{$limitedDates['startDate']}' AND '{$limitedDates['endDate']}')";
        }

        $query .= " AND store_id = {$this->id}";

        \Log::info('store date query', ['q' => $query]);
        return $query;
    }

    /**
     * @return mixed
     */
    public function allStoresOrders()
    {
        return Order::whereIn('store_id', $this->getStoreIdsArray());
    }

    /**
     * @return mixed
     */
    public function allStoresPassengerData()
    {
        return HourlyPassengerFlow::whereIn('store_id', $this->getStoreIdsArray());
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

            $query = $this->getQuery($from, $to, 'order_date');

            $totalOrders = $this->allStoresOrders()
                ->whereRaw($query)
                ->count();
            return round($this->getAvarageValue($from, $to, $totalOrders), 0);
        } else {
            $query = $this->getQuery($dateFrom, $dateTo, 'order_date');

            return $this->allStoresOrders()
                ->whereRaw($query)
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

            $query = $this->getQuery($from, $to, 'order_date');

            $totalSales = $this->allStoresOrders()
                ->whereRaw($query)
                ->sum('order_total');

            return round($this->getAvarageValue($from, $to, $totalSales), 0);
        } else {
            $query = $this->getQuery($dateFrom, $dateTo, 'order_date');

            return round($this->allStoresOrders()
                ->whereRaw($query)
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

            $query = $this->getQuery($from, $to, 'order_date');

            $itemsCount = $this->allStoresOrders()
                ->whereRaw($query)
                ->sum('items_count');

            return round($this->getAvarageValue($from, $to, $itemsCount), 0);
        } else {
            $query = $this->getQuery($dateFrom, $dateTo, 'order_date');

            return $this->allStoresOrders()
                ->whereRaw($query)
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
            $totalOrders = $this->totalOrders($dateFrom, $dateTo, true);

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

        $query = $this->getQuery($dateFrom, $dateTo, 'order_date');

        return $this->allStoresOrders()
            ->whereRaw($query);
    }

    /**
     * @param $dateFrom
     * @param $dateTo
     * @param $value
     * @return float
     */
    public function getAvarageValue($dateFrom, $dateTo, $value)
    {
        $workdays = $this->user?->settings['workdays'] ?? [];
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
        $firstAvailableDate = $this->allStoresOrders()->orderBy('order_date')->pluck('order_date')->first()
            ?? $this->allStoresPassengerData()->orderBy('time')->pluck('time')->first()
            ?? Carbon::now();
        return Carbon::parse($firstAvailableDate);
    }
}
