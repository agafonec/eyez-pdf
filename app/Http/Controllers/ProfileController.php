<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use App\Models\Opretail;
use App\Models\Store;
use App\Models\User;
use App\Services\Opretail\OpretailApi;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;
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
        $profile = [
            'mustVerifyEmail' => $request->user() instanceof MustVerifyEmail,
            'roles' => $this->user()->getRoleNames(),
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
     * Update the other user's profile information from admin.
     *
     * @param Request $request
     * @return RedirectResponse
     */
    public function updateOther(Request $request): RedirectResponse
    {
        $validator = $this->validateUser($request);

        if ($validator->fails()) {
            return ['errors' => $validator->errors()];
        }

        $user = User::find($request->json('user.id'));
        $user->fill([
            'name' => $request->json('name'),
            'email' => $request->json('email')
        ]);

        if ($user->isDirty('email')) {
            $user->email_verified_at = null;
        }

        $user->save();

        return Redirect::to("/users/{$user->id}");
    }

    /**
     * Used for validatin user when trying to update other user from admin account.
     *
     * @param Request $request
     * @return \Illuminate\Validation\Validator
     */
    public function validateUser(Request $request)
    {
        // Define validation rules
        $rules = [
            'name' => 'required|string',
            'email' => 'required|email',
            // Add more validation rules as needed
        ];

        // Validate the request data
        return Validator::make($request->all(), $rules);
    }

    /**
     * @param Request $request
     * @return array
     */
    public function opretailUpdate(Request $request)
    {
        $validator = $this->validateOpretail($request->json('form'));

        if ($validator->fails()) {
            return ['errors' => $validator->errors()];
        }

        $user = $request->json('user.id') ? User::find($request->json('user.id')) : $this->user();

        $opretailData = $request->json('form');

        if ($opretail = $user->opretailCredentials) {
            $opretail->update($opretailData);
        } else {
            if ($request->json('user.id')) {
                $opretailData['user_id'] = $request->json('user.id');
            }

            $opretail = Opretail::create($opretailData);
        }

        $opretailApi = new OpretailApi($opretail);
        if ($opretailApi->getToken() === 'error') {
            return ['errors' => 'Bad token generated. please check the credentials.'];
        }

        $stores = $opretailApi->getStores();
        if (!array_key_exists( 'errors', $stores)) {
            foreach ($stores as $store) {
                Store::firstOrCreate(
                    ['dep_id' => $store['id'], 'user_id' => $user->id],
                    [
                        'user_id' => $user->id,
                        'name' => $store['name'],
                        'store_id' => empty($store['shopId']) ? null : $store['shopId'],
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

    /**
     * @param Request $request
     * @return array
     */
    public function updateSettings(Request $request)
    {
        $user = $request->json('user.id') ? User::find($request->json('user.id')) : $this->user();

        $settings = $user->settings ?? [];
        $settings['workdays'] = $request->json('workdays');
        $settings['hideAgeDescription'] = $request->json('hideAgeDescription');
        $settings['ageGroups'] = $request->json('ageGroups');
        $settings['hiddenStores'] = $request->json('hiddenStores');

        $user->settings = $settings;
        $user->save();
        return [
            'errors' => false,
            'message' => 'Settings successfully saved.'
        ];
    }

    /**
     * @param Request $request
     * @return array
     */
    public function generateApiToken(Request $request)
    {
        $user = $request->json('user.id') ? User::find($request->json('user.id')) : $this->user();

        $user->tokens()->delete();

        if ($token = $user->createToken('eyez_api_key')) {
            $tokenText = explode('|', $token->plainTextToken)[1];

            $user->eyez_api_key = $tokenText;
            $user->save();
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

    public function getUserRoles(Request $request)
    {
        return $this->user()->isAdmin;
    }
}
