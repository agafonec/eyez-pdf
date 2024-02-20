<?php

namespace App\Console\Commands;

use App\Jobs\SyncOpretailJob;
use App\Models\Store;
use Carbon\Carbon;
use Illuminate\Console\Command;

class SyncAllStores extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'eyez:sync-all-stores';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Syncing all stores with opretail';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $stores = Store::all();
        \Log::info('Syncing stores', ['s' => $stores]);
        foreach ($stores as $store) {
            \Log::info("=================- STARTED SYNC FOR STORE {$store->id} ======================");

            SyncOpretailJob::dispatch(
                $store,
                Carbon::now()->setTimezone('Asia/Jerusalem'),
                'update'
            )->onQueue('syncopretail');
        }
    }
}
