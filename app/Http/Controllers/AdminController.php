<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Validator;
use Inertia\Inertia;
use Illuminate\Support\Facades\Crypt;

class AdminController extends Controller
{
    public function users(Request $request)
    {
        $users = User::paginate(10); // Adjust the number per page as needed

        return Inertia::render('Admin/Users', [
            'users' => $users
        ]);
    }

    /**
     * @param Request $request
     * @param User $user
     * @return \Inertia\Response
     */
    public function singleUser(Request $request, User $user)
    {
        $profile = [
            'currentUser' => $user,
            'roles' => $this->user()->getRoleNames(),
            'status' => session('status'),
            'eyezApiToken' => $user?->eyez_api_key ?? ''
        ];
        if ($user?->opretailCredentials) {
            $profile['opretail'] = $user?->opretailCredentials;
        } else {
            $profile['opretail']['errors'] = $this->validateOpretail([])->errors();
        }

        return Inertia::render('Profile/Edit', $profile);
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
     * @param User $user
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroyProfile(Request $request, User $user)
    {
        $user->opretailCredentials?->allStoresOrders()->delete();
        $user->opretailCredentials()->delete();
        $user->stores()->delete();
        $user->delete();

        return Redirect::to('/users');
    }
}
