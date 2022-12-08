<?php

namespace App\Http\Controllers\Twp;

use App\Http\Requests\StaffEditRequest;
use App\Models\Role;
use App\Models\Twp\Reason;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Inertia\Inertia;

class MaintenanceController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Inertia\Response::render
     */
    public function staffList(Request $request): \Inertia\Response
    {
        $staff = User::whereHas('roles', function ($q) {
        return $q->whereIn('name', [Role::TWP_ADMIN, Role::TWP_USER]);
        }
        )->orderBy('created_at', 'desc')->get();

        return Inertia::render('Twp/Maintenance', ['status' => true, 'results' => $staff, 'page' => 'staff']);
    }

    /**
     * Display a listing of the resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\User  $user
     * @return \Inertia\Response::render
     */
    public function staffShow(Request $request, User $user): \Inertia\Response
    {
        return Inertia::render('Twp/Maintenance', ['status' => true, 'results' => $user, 'page' => 'staff-edit']);
    }

    /**
     * Display a listing of the resource.
     *
     * @param  \App\Http\Requests\StaffEditRequest  $request
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\RedirectResponse::render
     */
    public function staffEdit(StaffEditRequest $request, User $user): \Illuminate\Http\RedirectResponse
    {
        if (Auth::user()->hasRole(Role::TWP_ADMIN) && Auth::user()->disabled === false) {
            $user->tele = $request->tele;
            $user->access_type = $request->access_type;
            $user->disabled = $request->disabled;
            $user->save();

            //reset roles
            $roles = Role::whereIn('name', [Role::TWP_ADMIN, Role::TWP_USER])->get();
            foreach ($roles as $role) {
                $user->roles()->detach($role);
            }

            //add user role
            $role = Role::where('name', Role::TWP_USER)->first();
            $user->roles()->attach($role);

            //if admin add admin role
            if ($request->access_type == 'A') {
                $role = Role::where('name', Role::TWP_ADMIN)->first();
                $user->roles()->attach($role);
            }
        }

        return Redirect::route('twp.maintenance.staff.list');
    }

    /**
     * Display a listing of the resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Inertia\Response::render
     */
    public function applicationReasonList(Request $request): \Inertia\Response
    {
        $reasons = Reason::get();

        return Inertia::render('Twp/Maintenance', ['status' => true, 'results' => $reasons, 'page' => 'application-reasons']);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\Request  $request
     * @param  Reason $reason
     * @return \Illuminate\Http\RedirectResponse::render
     */
    public function applicationReasonEdit(Request $request, Reason $reason): \Illuminate\Http\RedirectResponse
    {
        if (Auth::user()->hasRole(Role::TWP_ADMIN) && Auth::user()->disabled === false) {
            $reason->reason_status = "DENIED";
//            $reason->reason_status = $request->reason_status;
            $reason->title = $request->title;
            $reason->letter_body = $request->letter_body;
            $reason->active_flag = $request->active_flag;
            $reason->save();
        }

        return Redirect::route('twp.maintenance.application-reasons.list');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  Request  $request
     * @return \Illuminate\Http\RedirectResponse::render
     */
    public function applicationReasonStore(Request $request): \Illuminate\Http\RedirectResponse
    {
        Reason::create($request->all());

        return Redirect::route('twp.maintenance.application-reasons.list');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Inertia\Response::render
     */
    public function goToPage(Request $request, $page = 'staff')
    {
        return Inertia::render('Twp/Maintenance', ['page' => $page]);
    }
}
