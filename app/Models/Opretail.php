<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

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
    public function store()
    {
        return $this->hasMany(Store::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
