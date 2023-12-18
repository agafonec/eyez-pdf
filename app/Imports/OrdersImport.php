<?php

namespace App\Imports;

use App\Models\Order;
use Carbon\Carbon;
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
            if (isset($row['order_time'])) {
                $dateString = str_contains($row['order_date'], '-') ? str_replace('-', '/', $row['order_date']) : $row['order_date'];
                $dateString = "{$dateString} {$row['order_time']}";
                $datetime = new \DateTime($dateString, new \DateTimeZone('Asia/Jerusalem'));
            } else {
                $dateString = str_contains($row['order_date'], '/') ? str_replace('/', '-', $row['order_date']) : $row['order_date'];
                $datetime = new \DateTime($dateString, new \DateTimeZone('Asia/Jerusalem'));
            }
            return new Order([
                'store_id' => $this->storeId,
                'order_id'    => $row['order_id'],
                'order_date'  => $datetime->format('Y-m-d H:i:s'),
                'items_count' => $row['items_count'] ?? 0,
                'order_total' => $row['order_total'] ?? 0,
            ]);
        } else {
            return null;
        }

    }

    public function batchSize(): int
    {
        return 100;
    }
}
