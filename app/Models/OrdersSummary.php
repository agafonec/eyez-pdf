<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrdersSummary extends Model
{
    use HasFactory;

    protected $fillable = [
        'summary_date',
        'orders_count',
        'orders_total',
        'walk_in_count',
        'walk_in_rate',
        'last_updated'
    ];

    public function store()
    {
        return $this->belongsTo(Store::class);
    }
}
