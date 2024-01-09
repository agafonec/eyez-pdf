<?php

namespace App\Http\Controllers;

use App\Jobs\SyncOpretailJob;
use App\Models\Store;
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

        for ($i = 0; $i <= $diffInDays; $i++) {
            $currentDate = $startDate->copy()->addDays($i);

            $batch->add(
                new SyncOpretailJob(
                    $store,
                    $currentDate,
                    'update'
                )
            );
        }

        return response()->json(['batchId' => $batch->id]);
    }

    public function getProgress(Request $request, $batchId)
    {
        $batch = Bus::findBatch($batchId);

        return response()->json([
            'progress' => $batch,
        ]);
    }
}
