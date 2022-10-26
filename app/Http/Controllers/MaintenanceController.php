<?php

namespace App\Http\Controllers;

use App\Http\Requests\MinistryEditRequest;
use App\Http\Requests\StaffEditRequest;
use App\Http\Requests\IneligibleEditRequest;
use App\Http\Requests\IneligibleStoreRequest;
use App\Models\Admin;
use App\Models\Ineligible;
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
     * @return \Inertia\Response::render
     */
    public function ineligiblesList(Request $request): \Inertia\Response
    {
        $ineligibles = Ineligible::orderBy('code_id', 'asc')->get();

        return Inertia::render('Maintenance', ['status' => true, 'results' => $ineligibles, 'page' => 'ineligibles']);
    }

    /**
     * Display a resource to edit.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Ineligible  $ineligible
     * @return \Inertia\Response::render
     */
    public function ineligiblesShow(Request $request, Ineligible $ineligible): \Inertia\Response
    {
        return Inertia::render('Maintenance', ['status' => true, 'results' => $ineligible, 'page' => 'ineligibles-edit']);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\IneligibleEditRequest  $request
     * @param  \App\Models\Ineligible  $ineligible
     * @return \Illuminate\Http\RedirectResponse::render
     */
    public function ineligibleEdit(IneligibleEditRequest $request, Ineligible $ineligible): \Illuminate\Http\RedirectResponse
    {
        if (Auth::user()->access_type === 'A' && Auth::user()->disabled === false) {
            $ineligible->code_id = $request->code_id;
            $ineligible->description = $request->description;
            $ineligible->active_flag = $request->active_flag;
            $ineligible->code_type = $request->code_type;
            $ineligible->paragraph_text = $request->paragraph_text;
            $ineligible->save();
        }

        return Redirect::route('maintenance.ineligibles.list');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  IneligibleStoreRequest  $request
     * @return \Illuminate\Http\RedirectResponse::render
     */
    public function ineligibleStore(IneligibleStoreRequest $request): \Illuminate\Http\RedirectResponse
    {
        $ineligible = Ineligible::create($request->validated());
        return Redirect::route('maintenance.ineligibles.list');
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
        return Inertia::render('Students');
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
