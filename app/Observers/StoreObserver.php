<?php

namespace App\Observers;

use App\Models\Store;
use Illuminate\Support\Facades\Auth;

class StoreObserver
{
    public function creating(Store $store) {
        $user = Auth::user();

        if ($user && !$user->hasRole('admin')) {
            $store->user_id = $user->id;
        }
    }
}
