<?php

namespace App\Http\Controllers;

use App\Jobs\SyncOpretailJob;
use App\Models\Store;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Bus;
use Inertia\Inertia;

class SyncOpretailController extends Controller
{
    /**
     * @param Request $request
     * @return \Inertia\Response
     */
    public function view(Request $request)
    {
        $user = $this->user();

        if ($user && $user->hasRole('admin') || $user->hasRole('main_user')) {
            $hiddenStores = $user?->settings['hiddenStores'] ?? [];
            $stores = $user->stores?->toArray();
            $filteredStores = array_filter($stores, fn($store) => !in_array((int)$store['dep_id'], $hiddenStores));

            $inertiaParams = [
                'stores' => $filteredStores,
            ];
        } else {
            $inertiaParams = [
                'errors' => true,
                'messages' => 'You are not allowed to sync stores. Contact support or the main account manager.'
            ];
        }

        return Inertia::render('Profile/SyncOpretail', $inertiaParams);
    }

    /**
     * @param Request $request
     * @param Store $store
     * @return \Illuminate\Http\JsonResponse
     * @throws \Throwable
     */
    public function start(Request $request, Store $store)
    {
        $startDate  = $request->json('startDate');
        $startDate  = Carbon::parse($startDate);
        $diffInDays = Carbon::now()->diffInDays($startDate);

        $batch = Bus::batch([])->onQueue('syncopretail')->dispatch();
        \Log::info("=================- STARTED SYNC FOR STORE {$store->id} ======================");

        for ($i = 0; $i <= $diffInDays; $i++) {
            $currentDate = $startDate->copy()->addDays($i)->startOfDay();

            \Log::info('started the sync process', [
                'i' => $i,
                'currentDate' => $currentDate
            ]);

            for ($j = 0; $j < 24; $j++) {
                $currentHour = $currentDate->copy()->addHours($j);

                $batch->add(
                    (new SyncOpretailJob(
                        $store,
                        $currentHour,
                        'create'
                    ))->delay(now()->addMilliseconds( $i*$j * 300))
                );
            }
        }

        $store->user->cache('syncBatchId', $batch->id, 360);

        return response()->json(['batchId' => $batch->id]);
    }

    public function getProgress(Request $request, User $user, $batchId)
    {
        $batch = Bus::findBatch($batchId);

        $batchArray = $batch->toArray();
        $jobsProcessed = $batchArray['processedJobs'] + $batchArray['pendingJobs'];

        if ($batchArray['pendingJobs'] === 0 || $jobsProcessed === $batchArray['totalJobs']) {
            $user->forgetCached('syncBatchId');
        }

        return response()->json([
            'progress' => $batch,
        ]);
    }
}
