<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AgeGroup extends Model
{
    use HasFactory;

    protected $fillable = [
        'store_id',
        'group_id',
        'ageFrom',
        'ageTo',
    ];

    public function store()
    {
        return $this->belongsTo(Store::class);
    }

    public function ageGenderFlow()
    {
        return $this->belongsTo(AgeGenderFlow::class);
    }
}
