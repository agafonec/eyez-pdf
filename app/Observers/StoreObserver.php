<?php

namespace App\Observers;

use App\Models\Store;
use Illuminate\Support\Facades\Auth;

class StoreObserver
{
    public function creating(Store $store) {
        $user = Auth::user();

        if ($user) {
            $store->user_id = $user->id;
        }
    }
}
