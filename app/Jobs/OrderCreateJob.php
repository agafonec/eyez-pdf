<?php

namespace App\Jobs;

use App\Models\Order;
use App\Models\Store;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class OrderCreateJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     */
    public function __construct(public Store $store, public object $data)
    {
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        \Log::info('handle OrderCreate');

        $order = new Order();

        $order->createOrder($this->store, $this->data);
    }
}
