<?php

namespace App\Imports;

use App\Models\Order;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Database\Eloquent\Model;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithBatchInserts;
use Maatwebsite\Excel\Concerns\WithChunkReading;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class OrdersImport implements ToModel, WithHeadingRow, WithBatchInserts
{
    public function __construct(
        public int $storeId
    ) {}

    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {
        $order = Order::where([
            'store_id' => $this->storeId,
            'order_id'     => $row['order_id']
        ])->first();
        if (!$order) {
            return new Order([
                'store_id' => $this->storeId,
                'order_id'     => $row['order_id'],
                'order_date'    => $row['order_date'],
                'items_count' => $row['items_count'],
                'order_total' => $row['order_total'],
            ]);
        } else {
            return [];
        }

    }

    public function batchSize(): int
    {
        return 100;
    }
}
