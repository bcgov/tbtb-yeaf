<?php

namespace App\Http\Middleware;

use App\Models\User;
use App\Providers\RouteServiceProvider;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Stevenmaguire\OAuth2\Client\Provider\Keycloak;

class ApiAuth
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @param  string|null  ...$roles
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next, ...$roles)
    {
        $roles = empty($roles) ? [null] : $roles;

        $auth_api = $this->appLogin($request);
        if (! $auth_api) {
            return redirect()->route('login');
        }

        $user = Auth::user();
        //redirect non admin users to the dashboard page
        if ($user->access_type != 'A' && $user->access_type != 'S') {
            return redirect(RouteServiceProvider::HOME);
        }

        return $next($request);
    }

    public function appLogin(Request $request)
    {
        $provider = new Keycloak([
            'authServerUrl' => env('KEYCLOAK_SERVER_URL'),
            'realm' => env('KEYCLOAK_REALM'),
            'clientId' => env('KEYCLOAK_CLIENT_ID'),
            'clientSecret' => env('KEYCLOAK_CLIENT_SECRET'),
            'redirectUri' => env('KEYCLOAK_REDIRECT_URI'),
        ]);

        if (! $request->has('code')) {
            // If we don't have an authorization code then get one
            $authUrl = $provider->getAuthorizationUrl();
            $request->session()->put('oauth2state', $provider->getState());

            return false;

        // Check given state against previously stored one to mitigate CSRF attack
        } elseif (! $request->has('state') || ($request->state !== $request->session()->get('oauth2state'))) {
            $request->session()->forget('oauth2state');
            //Invalid state, make sure HTTP sessions are enabled
            return false;
        } else {
            // Try to get an access token (using the authorization coe grant)
            try {
                $token = $provider->getAccessToken('authorization_code', [
                    'code' => $request->code,
                ]);
            } catch (\Exception $e) {
                return false;
            }

            // Optional: Now you have a token you can look up a users profile data
            try {
                // We got an access token, let's now get the user's details
                $idir_user = $provider->getResourceOwner($token);
                $idir_user = $idir_user->toArray();
            } catch (\Exception $e) {
                return false;
            }

            $user = User::where('idir_user_guid', 'ilike', $idir_user['idir_user_guid'])->first();

            //if it is a new IDIR user, register the user first
            if (is_null($user)) {
//                $this->newUser($idir_user);

                return false;

            //if the user has been disabled
            } elseif ($user->disabled === true) {
                return false;
            }

            //else the user has access
            Auth::login($user);

            return true;
        }
    }
}
