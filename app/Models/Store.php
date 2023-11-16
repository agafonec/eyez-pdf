<?php

namespace App\Models;

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
        'name',
        'dep_id',
        'store_id',
        'organize_id'
    ];

    public function opretail()
    {
        return $this->belongsTo(Opretail::class);
    }

    public function ordersSummary()
    {
        return $this->hasMany(OrdersSummary::class);
    }

    public function cache($key, $value, $minutes = 30)
    {
        $cache_key = "{$this->dep_id}.$key";

        try {
            cache()->forget($cache_key);
            // TTL is in seconds since L5.8
            cache()->put($cache_key, $value, $minutes instanceof \DateTime ? $minutes : $minutes * 60);
        } catch (\Exception $e) {
        }

        return $this;
    }
}
