<?php

namespace App\Http\Controllers\Twp;

use App\Http\Requests\StaffEditRequest;
use App\Http\Requests\Twp\InstitutionEditRequest;
use App\Http\Requests\Twp\InstitutionStoreRequest;
use App\Models\Role;
use App\Models\Twp\Institution;
use App\Models\Twp\PaymentType;
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
     * @return \Inertia\Response::render
     */
    public function institutionList(Request $request): \Inertia\Response
    {
        $schools = Institution::orderBy('created_at', 'desc')->get();

        return Inertia::render('Twp/Maintenance', ['status' => true, 'results' => $schools, 'page' => 'institutions']);
    }

    /**
     * Display a listing of the resource.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\RedirectResponse::render
     */
    public function institutionStore(InstitutionStoreRequest $request): \Illuminate\Http\RedirectResponse
    {
        Institution::create($request->all());

        return Redirect::route('twp.maintenance.institutions.list');
    }

    /**
     * Display a listing of the resource.
     *
     * @param  \App\Http\Requests\StaffEditRequest  $request
     * @return \Illuminate\Http\RedirectResponse::render
     */
    public function institutionUpdate(InstitutionEditRequest $request, Institution $institution): \Illuminate\Http\RedirectResponse
    {
        if (Auth::user()->hasRole(Role::TWP_ADMIN) && Auth::user()->disabled === false) {
            Institution::where('id', $institution->id)->update($request->validated());
        }

        return Redirect::route('twp.maintenance.institutions.list');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Inertia\Response::render
     */
    public function staffList(Request $request): \Inertia\Response
    {
        $staff = User::whereHas('roles', function ($q) {
            return $q->whereIn('name', [Role::TWP_ADMIN, Role::TWP_USER]);
        })->orderBy('created_at', 'desc')->get();

        foreach ($staff as $user) {
            if ($user->roles->contains('name', Role::TWP_ADMIN)) {
                $user->access_type = 'A';
            } else {
                $user->access_type = 'U';
            }
        }

        return Inertia::render('Twp/Maintenance', ['status' => true, 'results' => $staff, 'page' => 'staff']);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Inertia\Response::render
     */
    public function staffShow(Request $request, User $user): \Inertia\Response
    {
        if ($user->roles->contains('name', Role::TWP_ADMIN)) {
            $user->access_type = 'A';
        } else {
            $user->access_type = 'U';
        }

        return Inertia::render('Twp/Maintenance', ['status' => true, 'results' => $user, 'page' => 'staff-edit']);
    }

    /**
     * Display a listing of the resource.
     *
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
     * @return \Inertia\Response::render
     */
    public function reasonList(Request $request): \Inertia\Response
    {
        $reasons = Reason::get();

        return Inertia::render('Twp/Maintenance', ['status' => true, 'results' => $reasons, 'page' => 'reasons']);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\Request  $request
     * @return \Illuminate\Http\RedirectResponse::render
     */
    public function reasonUpdate(Request $request, Reason $reason): \Illuminate\Http\RedirectResponse
    {
        if (Auth::user()->hasRole(Role::TWP_ADMIN) && Auth::user()->disabled === false) {
            //            $reason->reason_status = "DENIED";
            $reason->reason_status = $request->reason_status;
            $reason->title = $request->title;
            $reason->letter_body = $request->letter_body;
            $reason->active_flag = $request->active_flag;
            $reason->save();
        }

        return Redirect::route('twp.maintenance.reasons.list');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @return \Illuminate\Http\RedirectResponse::render
     */
    public function reasonStore(Request $request): \Illuminate\Http\RedirectResponse
    {
        Reason::create($request->all());

        return Redirect::route('twp.maintenance.reasons.list');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Inertia\Response::render
     */
    public function paymentList(Request $request): \Inertia\Response
    {
        $paymentTypes = PaymentType::get();

        return Inertia::render('Twp/Maintenance', ['status' => true, 'results' => $paymentTypes, 'page' => 'payments']);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\Request  $request
     * @param  PaymentType  $paymentType
     * @return \Illuminate\Http\RedirectResponse::render
     */
    public function paymentUpdate(Request $request, PaymentType $payment): \Illuminate\Http\RedirectResponse
    {
        if (Auth::user()->hasRole(Role::TWP_ADMIN) && Auth::user()->disabled === false) {
            $payment->title = $request->title;
            $payment->active_flag = $request->active_flag;
            $payment->save();
        }

        return Redirect::route('twp.maintenance.payments.list');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @return \Illuminate\Http\RedirectResponse::render
     */
    public function paymentStore(Request $request): \Illuminate\Http\RedirectResponse
    {
        PaymentType::create($request->all());

        return Redirect::route('twp.maintenance.payments.list');
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
