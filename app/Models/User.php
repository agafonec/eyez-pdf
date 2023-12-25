<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, HasRoles;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'eyez_api_key',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];


    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function opretailCredentials()
    {
        return $this->hasOne(Opretail::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function stores()
    {
        return $this->hasMany('\App\Models\Store', 'user_id', 'id');
    }

    /**
     * @param $key
     * @param $value
     * @param int $minutes
     * @return $this
     */
    public function cache($key, $value, $minutes = 30)
    {
        $cache_key = "user.{$this->id}.$key";

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
            return cache("user.{$this->id}.$key", $default);
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
            cache()->forget("user.{$this->id}.$key");
        } catch (\Exception $e) {
        }

        return $this;
    }
}
