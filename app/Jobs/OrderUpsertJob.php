<?php

namespace App\Jobs;

use App\Models\OrdersSummary;
use App\Models\Store;
use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class OrderUpsertJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     */
    public function __construct(public Store $store, public $data)
    {
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        \Log::info('handle OrderUpserJob');
        $summaryDate = Carbon::parse($this->data->summary_date);

        if ($orderSummary = $this->store->ordersSummary()->whereDate('summary_date', $summaryDate)->first()) {
            $orderSummary->orders_count = $orderSummary->orders_count + $this->data->orders_count;
            $orderSummary->orders_total = $orderSummary->orders_total + $this->data->orders_total;
        } else {
            $orderSummary = new OrdersSummary();
            $orderSummary->store_id = $this->store->getID();
            $orderSummary->orders_count = $this->data->orders_count;
            $orderSummary->orders_total = $this->data->orders_total;
            $orderSummary->summary_date = $summaryDate->format('Y-m-d H:i:s');
        }

        $orderSummary->save();
    }
}
