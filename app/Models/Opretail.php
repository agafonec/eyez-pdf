<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
/**
 * App\Models\Opretail
 *
 * @property int|null $user_id
 */

class Opretail extends Model
{
    use HasFactory;

    protected $fillable = [
        'username',
        'password',
        'secret_key',
        '_akey',
        '_aid',
        'enterpriseId',
        'orgId',
        'workdays',
    ];

    protected $casts = [
        'workdays' => 'array',
    ];

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
            cache()->forget("store.{$this->dep_id}.$key");
        } catch (\Exception $e) {
        }

        return $this;
    }
}
