<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use App\Models\Opretail;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Validator;
use Inertia\Inertia;
use Inertia\Response;

class ProfileController extends Controller
{
    /**
     * Display the user's profile form.
     */
    public function edit(Request $request): Response
    {
        return Inertia::render('Profile/Edit', [
            'mustVerifyEmail' => $request->user() instanceof MustVerifyEmail,
            'status' => session('status'),
        ]);
    }

    /**
     * Update the user's profile information.
     */
    public function update(ProfileUpdateRequest $request): RedirectResponse
    {
        $request->user()->fill($request->validated());

        if ($request->user()->isDirty('email')) {
            $request->user()->email_verified_at = null;
        }

        $request->user()->save();

        return Redirect::route('profile.edit');
    }

    /**
     * @param Request $request
     * @return array
     */
    public function opretailUpdate(Request $request)
    {
        $user = Auth::user();

        \Log::info('opretail update', [
            'user_id' => $user->id,
            'request' => $request->toArray()
        ]);

        $validator = $this->validateOpretail($request);

        if ($validator->fails()) {
            \Log::info('validator errors', ['errors' => $validator->errors()]);
            return ['errors' => $validator->errors()];
        }
        if ($opretail = Opretail::where('user_id', $user->id)->first()) {
            $opretail->update($request->toArray());
        } else {
            $opretail = Opretail::create($request->toArray());
        }
        return $opretail;
    }

    /**
     * @param Request $request
     * @return \Illuminate\Validation\Validator
     */
    private function validateOpretail(Request $request)
    {
        // Define validation rules
        $rules = [
            'username' => 'required|string',
            'password' => 'required|string',
            'secret_key' => 'required|string',
            '_akey' => 'required|string',
            '_aid' => 'required|string',
            'enterpriseId' => 'required|integer',
            'orgId' => 'required|integer'
            // Add more validation rules as needed
        ];

        // Validate the request data
        return Validator::make($request->all(), $rules);
    }

    /**
     * Delete the user's account.
     */
    public function destroy(Request $request): RedirectResponse
    {
        $request->validate([
            'password' => ['required', 'current_password'],
        ]);

        $user = $request->user();

        Auth::logout();

        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return Redirect::to('/');
    }
}
