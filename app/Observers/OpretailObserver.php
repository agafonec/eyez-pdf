<?php

namespace App\Observers;

use App\Models\Opretail;
use Illuminate\Support\Facades\Auth;

class OpretailObserver
{
    public function creating(Opretail $opretail) {
        $user = Auth::user();

        if ($user && !$user->hasRole('admin')) {
            $opretail->user_id = $user->id;
        }
    }
}
