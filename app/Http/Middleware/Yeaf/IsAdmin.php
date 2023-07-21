<?php

namespace App\Http\Middleware\Yeaf;

use App\Models\Role;
use App\Providers\RouteServiceProvider;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class IsAdmin
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

        if (! Auth::check()) {
            return redirect()->route('login');
        }

        $user = Auth::user();
//        //redirect non admin users to the dashboard page
//        if ($user->access_type != 'A' && $user->access_type != 'S') {
//            return redirect(RouteServiceProvider::HOME);
//        }
        if (! $user->hasRole(Role::SUPER_ADMIN) && ! $user->hasRole(Role::YEAF_ADMIN)) {
            return redirect(RouteServiceProvider::HOME);
        }

        return $next($request);
    }
}
