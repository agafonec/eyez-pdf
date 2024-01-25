<?php

namespace App\Imports;

use App\Models\Order;
use App\Models\Store;
use App\Models\User;
use App\Traits\HasStoreDateFilter;
use Carbon\Carbon;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithBatchInserts;
use Maatwebsite\Excel\Concerns\WithChunkReading;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\RegistersEventListeners;
use Maatwebsite\Excel\Events\AfterImport;

class OrdersImport implements ToModel, WithHeadingRow, WithBatchInserts, WithEvents
{
    use RegistersEventListeners, HasStoreDateFilter;
    public User $user;
    public Store $store;

    public function __construct(
        public int $storeId
    ) {
        $this->store = Store::find($this->storeId);
    }

    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {
        if ($this->store) {
            $order = Order::where([
                'store_id' => $this->storeId,
                'order_id' => $row['order_id']
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
                $orderDate = $datetime->format('Y-m-d H:i:s');
                $carbonDate = Carbon::parse($orderDate);

                $limitedDates = $this->modifyDate($orderDate, $this->store);

                if (
                    $carbonDate->lessThanOrEqualTo($limitedDates['endDate'])
                    && $carbonDate->greaterThanOrEqualTo($limitedDates['startDate'])
                    && $this->store->workingDay($orderDate)
                ) {

                    return new Order([
                        'store_id' => $this->storeId,
                        'order_id'    => $row['order_id'],
                        'order_date' => $orderDate,
                        'items_count' => $row['items_count'] ?? 0,
                        'order_total' => $row['order_total'] ?? 0,
                    ]);
                }
            }
        }

        return null;
    }

    public function batchSize(): int
    {
        return 100;
    }

    public static function afterImport(AfterImport $event)
    {
        $user = Auth::user();
        $user->cache('last_import', 'completed', 1440);
    }
}
