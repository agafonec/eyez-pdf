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
        'orgId'
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
        $cache_key = "{$this->user_id}.$key";

        try {
            cache()->forget($cache_key);
            // TTL is in seconds since L5.8
            cache()->put($cache_key, $value, $minutes instanceof \DateTime ? $minutes : $minutes * 60);
        } catch (\Exception $e) {
        }

        return $this;
    }
}
