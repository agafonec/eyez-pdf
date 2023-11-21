<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'store_id',
        'order_id',
        'order_date',
        'items_count',
        'order_total',
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function store()
    {
        return $this->belongsTo(Store::class);
    }

    public function createOrder(Store $store, object $data)
    {
        \Log::info('Data', ['data'=> $data]);
        $this->store_id = $store->getID();
        $this->order_id = $data->order_id;
        $this->order_date = $data->order_date;
        $this->items_count = $data->items_count;
        $this->order_total = $data->order_total;

        $this->save();
    }
}
