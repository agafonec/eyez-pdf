<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Inertia\Inertia;

class LogsController extends Controller
{
    public function userSessions(Request $request, User $user)
    {
        $logs = $user->logs()
            ->where('type', 'session')
            ->latest('created_at')
            ->paginate(20);

        return Inertia::render('Logs', [
            'logs' => $logs,
            'user' => $user
        ]);
    }
}
