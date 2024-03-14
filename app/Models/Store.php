<?php

namespace App\Models;

use App\Traits\HasStoreDateFilter;
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
    use HasFactory, HasStoreDateFilter;

    protected $fillable = [
        'user_id',
        'name',
        'dep_id',
        'store_id',
        'organize_id'
    ];

    protected $casts = [
        'settings' => 'json'
    ];

    public function opretail()
    {
        return $this->belongsTo(Opretail::class, 'user_id', 'user_id');
    }

    /**
     * @return array|mixed
     */
    public function getSchedule()
    {
        if ($this->settings && $this->settings['workdays']) {
            $workdays = $this->settings['workdays'];

            foreach ($this->settings['workdays'] as $key => $workday) {
                $workdays[$key]['timeStart'] = Carbon::parse($workday['timeStart'])->setTimezone('Asia/Jerusalem')->format('H:i:s');
                $workdays[$key]['timeEnd'] = Carbon::parse($workday['timeEnd'])->setTimezone('Asia/Jerusalem')->format('H:i:s');
            }

            return $workdays;
        } else {
            return $this->settings;
        }
    }

    /**
     * @param $date
     * @return bool
     */
    public function workingDay($date)
    {
        $carbonDate = Carbon::parse($date);

        return !($this->settings && isset($this->settings['daysoff']))
            || !in_array($carbonDate->dayOfWeek, $this->settings['daysoff']);
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


    public function applyTimeRangeConstraints($query, $dateParamName = 'date')
    {
        $workdays = $this->settings['workdays'] ?? [];
        $daysOff = $this->settings['daysoff'] ??[];

        $query->where(function ($query) use ($dateParamName, $workdays, $daysOff) {
            $iteration = 0;

            if (count($daysOff) > 0) {
                $daysOffString = implode(',', $daysOff);
                $query->where(function ($q) use ($dateParamName, $daysOffString) {
                    $q->whereRaw("DAYOFWEEK($dateParamName) NOT IN ({$daysOffString})");
                });
            }

            foreach($workdays as $workday) {
                $dayOfWeek = (int)$workday['dayOfWeek'] + 1;
                $start = Carbon::parse($workday['timeStart'])
                    ->setTimezone('Asia/Jerusalem')
                    ->setMinute(0)
                    ->setSecond(1)
                    ->format('H:i:s');
                $end = Carbon::parse($workday['timeEnd'])
                    ->setTimezone('Asia/Jerusalem')
                    ->setMinute(0)
                    ->setSecond(1)
                    ->format('H:i:s');

                if ($iteration === 0) {
                    $query->where(function ($q) use ($dateParamName, $dayOfWeek, $start, $end) {
                        $q->whereRaw("DAYOFWEEK($dateParamName) = {$dayOfWeek}")
                        ->whereRaw("TIME({$dateParamName}) BETWEEN '{$start}' AND '{$end}'");
                    });

                } else {
                    $query->orWhere(function ($q) use ($dateParamName, $dayOfWeek, $start, $end) {
                        $q->whereRaw("DAYOFWEEK($dateParamName) = {$dayOfWeek}")
                        ->whereRaw("TIME({$dateParamName}) BETWEEN '{$start}' AND '{$end}'");
                    });
                }

                $iteration++;
            }
        });
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
     * @param $dateFrom
     * @param $dateTo
     * @return mixed
     */
    public function getWalkInCount($dateFrom, $dateTo)
    {
//        $query = $this->getDateQuery($dateFrom, $dateTo, 'time');
        $store = $this;
        return $this->passengerFlow()
            ->whereBetween('time', [$dateFrom, $dateTo])
            ->where(function ($query) use ($store) {
                $store->applyTimeRangeConstraints($query, 'time');
            })
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
        $store = $this;

        if ($average) {
            $from = $this->firstAvailableDate()->startOfDay();
            $to = Carbon::now()->endOfDay();

            $totalOrders = $this->orders()
                ->whereBetween('order_date', [$from, $to])
                ->where(function ($query) use ($store) {
                    $store->applyTimeRangeConstraints($query, 'order_date');
                })
                ->count();
            return round($this->getAvarageValue($from, $to, $totalOrders), 0);
        } else {
            return $this->orders()
                ->whereBetween('order_date', [$dateFrom, $dateTo])
                ->where(function ($query) use ($store) {
                    $store->applyTimeRangeConstraints($query, 'order_date');
                })
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
        $store = $this;

        if ($average) {
            $from = $this->firstAvailableDate()->startOfDay();
            $to = Carbon::now()->endOfDay();

            $totalSales = $this->orders()
                ->whereBetween('order_date', [$from, $to])
                ->where(function ($query) use ($store) {
                    $store->applyTimeRangeConstraints($query, 'order_date');
                })
                ->sum('order_total');

            return round($this->getAvarageValue($from, $to, $totalSales), 0);
        } else {
            return round($this->orders()
                ->whereBetween('order_date', [$dateFrom, $dateTo])
                ->where(function ($query) use ($store) {
                    $store->applyTimeRangeConstraints($query, 'order_date');
                })
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
        $store = $this;

        if ($average) {
            $from = $this->firstAvailableDate()->startOfDay();
            $to = Carbon::now()->endOfDay();

            $itemsCount = $this->orders()
                ->whereBetween('order_date', [$from, $to])
                ->where(function ($query) use ($store) {
                    $store->applyTimeRangeConstraints($query, 'order_date');
                })
                ->sum('items_count');

            return round($this->getAvarageValue($from, $to, $itemsCount), 0);
        } else {
            return $this->orders()
                ->whereBetween('order_date', [$dateFrom, $dateTo])
                ->where(function ($query) use ($store) {
                    $store->applyTimeRangeConstraints($query, 'order_date');
                })
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
            $from = $this->firstAvailableDate()->startOfDay();
            $to = Carbon::now()->endOfDay();

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
            $from = $this->firstAvailableDate()->startOfDay();
            $to = Carbon::now()->endOfDay();
            $totalOrders = $this->totalOrders($from, $to);

            $generalWalkInCount = $this->getWalkInCount($from, $to);

            return $generalWalkInCount !== 0 ? round($totalOrders / $generalWalkInCount * 100, 0) : 0;
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
        $store = $this;

        return $this->orders()
            ->whereBetween('order_date', [$dateFrom, $dateTo])
            ->where(function ($query) use ($store) {
                $store->applyTimeRangeConstraints($query, 'order_date');
            });
    }

    /**
     * @param $dateFrom
     * @param $dateTo
     * @param $value
     * @return float
     */
    public function getAvarageValue($dateFrom, $dateTo, $value)
    {
        $daysoff = $this->settings['daysoff'] ?? [];

        // Set the end date as today
        $startDate = Carbon::parse($dateFrom);
        $endDate = Carbon::now()->month !== Carbon::parse($dateTo)->month
            ? Carbon::parse($dateTo)->endOfMonth()->endOfDay()
            : Carbon::now()->subDays(1)->endOfDay();
        $diffInDays = $endDate->diffInDays($startDate);

        $count = 0;
        for ($i = 0; $i <= $diffInDays; $i++) {
            $currentDate = $endDate->copy()->subDays($i);

            if (!in_array($currentDate->dayOfWeek, $daysoff)) {
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

        $startOfMonth = Carbon::now()->startOfMonth()->startOfDay();
        $firstAvailableDate = Carbon::parse($firstAvailableDate);
        $firstAvailableDate = $firstAvailableDate->greaterThanOrEqualTo($startOfMonth)
            ? $firstAvailableDate : $startOfMonth;

        return Carbon::parse($firstAvailableDate);
    }
}
