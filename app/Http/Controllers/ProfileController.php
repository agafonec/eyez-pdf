<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use App\Models\Opretail;
use App\Models\Store;
use App\Services\Opretail\OpretailApi;
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
        \Log::info($this->user()?->eyez_api_key);
        $profile = [
            'mustVerifyEmail' => $request->user() instanceof MustVerifyEmail,
            'status' => session('status'),
            'eyezApiToken' => $this->user()?->eyez_api_key ?? ''
        ];
        if ($this->user()?->opretailCredentials) {
            $profile['opretail'] = $this->user()?->opretailCredentials;
        } else {
            $profile['opretail']['errors'] = $this->validateOpretail([])->errors();
        }

        return Inertia::render('Profile/Edit', $profile);
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
        $validator = $this->validateOpretail($request);

        if ($validator->fails()) {
            return ['errors' => $validator->errors()];
        }
        if ($opretail = Opretail::where('user_id', $this->user()->id)->first()) {
            $opretail->update($request->toArray());
        } else {
            $opretail = Opretail::create($request->toArray());
        }

        $opretailApi = new OpretailApi($this->user()->opretailCredentials);
        if ($opretailApi->getToken() === 'error') {
            return ['errors' => 'Bad token generated. please check the credentials.'];
        }

        $stores = $opretailApi->getStores();

        if (!array_key_exists( 'errors', $stores)) {
            foreach ($stores as $store) {
                Store::firstOrCreate(
                    ['dep_id' => $store['id']],
                    [
                        'name' => $store['name'],
                        'store_id' => $store['shopId'],
                        'organize_id' => $store['organizeId']
                    ]
                );
            }
        }

        return $opretail;
    }

    /**
     * @param Request|array $request
     * @return \Illuminate\Validation\Validator
     */
    private function validateOpretail(Request|array $request)
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

        $toValidate = is_array($request) ? $request : $request->all();
        // Validate the request data
        return Validator::make($toValidate, $rules);
    }

    public function updateSettings(Request $request)
    {
        if ($opretail = Opretail::where('user_id', $this->user()->id)->first()) {
            $settings = $opretail->settings ?? (object)[];
            $settings->workdays = $request->json('workdays');
            $settings->ageGroups = $request->json('ageGroups');

            $opretail->settings = $settings;
            $opretail->save();
            return [
                'errors' => false,
                'message' => 'Settings successfully saved.'
            ];
        } else {
            return [
                'errors' => true,
                'message' => 'You have to connect opretail account first.'
            ];
        }
    }

    /**
     * @param Request $request
     * @return array
     */
    public function generateApiToken(Request $request)
    {
        $this->user()->tokens()->delete();

        if ($token = $this->user()->createToken('eyez_api_key')) {
            $tokenText = explode('|', $token->plainTextToken)[1];

            $this->user()->eyez_api_key = $tokenText;
            $this->user()->save();
            return ['errors' => false, 'message' => 'Token succcesfully generated. refresh the page to see token.'];
        } else {
            return ['errors' => true, 'message' => 'Something went wrong during tokene generation'];
        }
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
