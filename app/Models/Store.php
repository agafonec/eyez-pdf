<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

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
}
