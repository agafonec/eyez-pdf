<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AgeGenderFlow extends Model
{
    use HasFactory;

    protected $fillable = [
        'store_id',
        'gender',
        'age_group_id',
        'people_count',
        'date',
        'updated_at'
    ];

    public function store()
    {
        return $this->belongsTo(Store::class);
    }

    public function ageGroup()
    {
        return $this->belongsTo(AgeGroup::class);
    }
}
