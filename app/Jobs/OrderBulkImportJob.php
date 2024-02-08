<?php

namespace App\Jobs;

use App\Models\Order;
use App\Models\Store;
use App\Traits\HasStoreDateFilter;
use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class OrderBulkImportJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels, HasStoreDateFilter;

    /**
     * Create a new job instance.
     */
    public function __construct(public Store $store, public $ordersData)
    {
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        \Log::info('handle OrderBulk import');

        foreach ($this->ordersData as $data) {
            $data = (object) $data;

            $carbonDate = Carbon::parse($data->order_date);
            $limitedDates = $this->modifyDate($data->order_date, $this->store);

            if (
                $carbonDate->lessThanOrEqualTo($limitedDates['endDate'])
                && $carbonDate->greaterThanOrEqualTo($limitedDates['startDate'])
                && $this->store->workingDay($data->order_date)
            ) {
                Order::firstOrCreate(
                    [
                        "store_id" => $this->store->getID(),
                        "order_id" => $data->order_id
                    ],
                    [
                        "order_date" => $data->order_date,
                        "items_count" => $data->items_count,
                        "order_total" => $data->order_total,
                    ]
                );
            }
        }
    }
}
