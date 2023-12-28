<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HourlyPassengerFlow extends Model
{
    use HasFactory;

    protected $fillable = [
        'store_id',
        'passengerFlow',
        'time',
        'updated_at'
    ];

    public function store()
    {
        return $this->belongsTo(Store::class);
    }
}
