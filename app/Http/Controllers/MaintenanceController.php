<?php

namespace App\Http\Controllers;

use App\Http\Requests\StaffEditRequest;
use App\Http\Requests\MinistryEditRequest;
use App\Models\Admin;
use App\Models\Province;
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
        $staff = User::orderBy('created_at', 'desc')->get();

        return Inertia::render('Maintenance', ['status' => true, 'results' => $staff, 'page' => 'staff']);
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
        return Inertia::render('Maintenance', ['status' => true, 'results' => $user, 'page' => 'staff-edit']);
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
        if (Auth::user()->access_type === 'A' && Auth::user()->disabled === false) {
            $user->tele = $request->tele;
            $user->access_type = $request->access_type;
            $user->disabled = $request->disabled;
            $user->save();
        }

        return Redirect::route('maintenance.staff.list');
    }


    /**
     * Display a listing of the resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\User  $user
     * @return \Inertia\Response::render
     */
    public function ministryShow(Request $request): \Inertia\Response
    {
        $admin = Admin::first();
        $provinces = Province::where('country_code', 'CAN')->select('province_code')->get();
        return Inertia::render('Maintenance', ['status' => true, 'results' => $admin, 'provinces' => $provinces, 'page' => 'ministry']);
    }

    /**
     * Display a listing of the resource.
     *
     * @param  \App\Http\Requests\MinistryEditRequest  $request
     * @param  \App\Models\Admin  $admin
     * @return \Illuminate\Http\RedirectResponse::render
     */
    public function ministryEdit(MinistryEditRequest $request, Admin $admin): \Illuminate\Http\RedirectResponse
    {
        if (Auth::user()->access_type === 'A' && Auth::user()->disabled === false) {

        }

        return Redirect::route('maintenance.ministry.show');
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Inertia\Response::render
     */
    public function index()
    {
        return Inertia::render('Dashboard');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Inertia\Response::render
     */
    public function goToPage(Request $request, $page = 'area-of-audit')
    {
        return Inertia::render('Maintenance', ['page' => $page]);
    }
}
