<?php

namespace App\Http\Controllers\Yeaf;

use App\Http\Requests\BatchEditRequest;
use App\Http\Requests\BatchStoreRequest;
use App\Http\Requests\IneligibleEditRequest;
use App\Http\Requests\IneligibleStoreRequest;
use App\Http\Requests\MinistryEditRequest;
use App\Http\Requests\ProgramYearEditRequest;
use App\Http\Requests\ProgramYearStoreRequest;
use App\Http\Requests\StaffEditRequest;
use App\Models\Role;
use App\Models\User;
use App\Models\Yeaf\Admin;
use App\Models\Yeaf\Batch;
use App\Models\Yeaf\Ineligible;
use App\Models\Yeaf\ProgramYear;
use App\Models\Yeaf\Province;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Inertia\Inertia;
use PDF;

class MaintenanceController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Inertia\Response::render
     */
    public function letters(Request $request): \Inertia\Response
    {
        return Inertia::render('Yeaf/Maintenance', ['status' => true,
            'program_years' => ProgramYear::orderBy('year_start', 'desc')->get(),
            'batches' => Batch::orderBy('batch_number', 'desc')->get(), 'page' => 'letters', ]);
    }

    public function downloadLetter(Request $request, $type, $extra)
    {
        $admin = Admin::first();
        $now_d = date('F d, Y');
        $now_t = date('H:m:i');
        $user = Auth::user();
        if ($type === 'py') {
            $program_year = ProgramYear::find($extra);
            $pdf = PDF::loadView('py-pdf', compact('program_year', 'admin', 'user', 'now_d', 'now_t'));
            $file_name = $program_year->year_start.'_'.$program_year->year_end;
        } elseif ($type === 'batch') {
            $batch = Batch::find($extra);
            $pdf = PDF::loadView('batch-pdf', compact('batch', 'admin', 'user', 'now_d', 'now_t'));
            $file_name = $batch->batch_date;
        }

        return $pdf->download(mt_rand().'-'.$file_name.'-letter.pdf');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Inertia\Response::render
     */
    public function staffList(Request $request): \Inertia\Response
    {
        $staff = User::whereHas('roles', function ($q) {
            return $q->whereIn('name', [Role::YEAF_ADMIN, Role::YEAF_USER]);
        })->orderBy('created_at', 'desc')->get();

        foreach ($staff as $user) {
            if ($user->roles->contains('name', Role::YEAF_ADMIN)) {
                $user->access_type = 'A';
            } else {
                $user->access_type = 'U';
            }
        }

        return Inertia::render('Yeaf/Maintenance', ['status' => true, 'results' => $staff, 'page' => 'staff']);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Inertia\Response::render
     */
    public function staffShow(Request $request, User $user): \Inertia\Response
    {
        if ($user->roles->contains('name', Role::YEAF_ADMIN)) {
            $user->access_type = 'A';
        } else {
            $user->access_type = 'U';
        }

        return Inertia::render('Yeaf/Maintenance', ['status' => true, 'results' => $user, 'page' => 'staff-edit']);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\RedirectResponse::render
     */
    public function staffEdit(StaffEditRequest $request, User $user): \Illuminate\Http\RedirectResponse
    {
        if (Auth::user()->hasRole(Role::YEAF_ADMIN) && Auth::user()->disabled === false) {
            $user->tele = $request->tele;
            $user->access_type = $request->access_type;
            $user->disabled = $request->disabled;
            $user->save();

            //reset roles
            $roles = Role::whereIn('name', [Role::YEAF_ADMIN, Role::YEAF_USER])->get();
            foreach ($roles as $role) {
                $user->roles()->detach($role);
            }

            //add user role
            $role = Role::where('name', Role::YEAF_USER)->first();
            $user->roles()->attach($role);

            //if admin add admin role
            if ($request->access_type == 'A') {
                $role = Role::where('name', Role::YEAF_ADMIN)->first();
                $user->roles()->attach($role);
            }
        }

        return Redirect::route('maintenance.staff.list');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Inertia\Response::render
     */
    public function ineligiblesList(Request $request): \Inertia\Response
    {
        $ineligibles = Ineligible::orderBy('code_id', 'asc')->get();

        return Inertia::render('Yeaf/Maintenance', ['status' => true, 'results' => $ineligibles, 'page' => 'ineligibles']);
    }

    /**
     * Update the specified resource in storage.
     *
     * @return \Illuminate\Http\RedirectResponse::render
     */
    public function ineligibleEdit(IneligibleEditRequest $request, Ineligible $ineligible): \Illuminate\Http\RedirectResponse
    {
        if (Auth::user()->hasRole(Role::YEAF_ADMIN) && Auth::user()->disabled === false) {
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
     * @return \Inertia\Response::render
     */
    public function programYearsList(Request $request): \Inertia\Response
    {
        $program_years = ProgramYear::orderBy('year_start', 'desc')->get();

        return Inertia::render('Yeaf/Maintenance', ['status' => true, 'results' => $program_years, 'page' => 'program-years']);
    }

    /**
     * Update the specified resource in storage.
     *
     * @return \Illuminate\Http\RedirectResponse::render
     */
    public function programYearEdit(ProgramYearEditRequest $request, ProgramYear $programYear): \Illuminate\Http\RedirectResponse
    {
        if (Auth::user()->hasRole(Role::YEAF_ADMIN) && Auth::user()->disabled === false) {
            ProgramYear::where('id', $programYear->id)->update($request->validated());
        }

        return Redirect::route('maintenance.program-years.list');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @return \Illuminate\Http\RedirectResponse::render
     */
    public function programYearStore(ProgramYearStoreRequest $request): \Illuminate\Http\RedirectResponse
    {
        $py = ProgramYear::create($request->validated());

        return Redirect::route('maintenance.program-years.list');
    }

    /**
     * Display a listing of the resource.
     *
     * @param  \App\Models\User  $user
     * @return \Inertia\Response::render
     */

    /**
     * Display a listing of the resource.
     *
     * @return \Inertia\Response::render
     */
    public function batchesList(Request $request): \Inertia\Response
    {
        $batches = Batch::orderBy('batch_number', 'desc')->get();

        return Inertia::render('Yeaf/Maintenance', ['status' => true, 'results' => $batches, 'page' => 'batches']);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Models\Batch  $batch
     * @return \Illuminate\Http\RedirectResponse::render
     */
    public function batchEdit(BatchEditRequest $request, Batch $batch): \Illuminate\Http\RedirectResponse
    {
        if (Auth::user()->hasRole(Role::YEAF_ADMIN) && Auth::user()->disabled === false) {
            Batch::where('id', $batch->id)->update($request->validated());
        }

        return Redirect::route('maintenance.batches.list');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @return \Illuminate\Http\RedirectResponse::render
     */
    public function batchStore(BatchStoreRequest $request): \Illuminate\Http\RedirectResponse
    {
        Batch::create($request->validated());

        return Redirect::route('maintenance.batches.list');
    }

    /**
     * Display a listing of the resource.
     *
     * @param  \App\Models\User  $user
     * @return \Inertia\Response::render
     */
    public function ministryShow(Request $request): \Inertia\Response
    {
        $admin = Admin::first();
        $provinces = Province::where('country_code', 'CAN')->select('province_code')->get();

        return Inertia::render('Yeaf/Maintenance', ['status' => true, 'results' => $admin, 'provinces' => $provinces, 'page' => 'ministry']);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\RedirectResponse::render
     */
    public function ministryUpdate(MinistryEditRequest $request, Admin $admin): \Illuminate\Http\RedirectResponse
    {
        if (Auth::user()->hasRole(Role::YEAF_ADMIN) && Auth::user()->disabled === false) {
            Admin::where('id', $admin->id)->update($request->validated());
        }

        return Redirect::route('maintenance.ministry.show');
    }

    /**
     * Display a listing of the resource.
     *
     * @param  \App\Models\User  $user
     * @return \Inertia\Response::render
     */
    public function reportsShow(Request $request): \Inertia\Response
    {
        return Inertia::render('Yeaf/Maintenance', ['status' => true, 'page' => 'reports']);
        //        return Inertia::render('Yeaf/Maintenance', ['status' => true, 'results' => $admin, 'provinces' => $provinces, 'page' => 'ministry']);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Inertia\Response::render
     */
    public function index()
    {
        return Inertia::render('Yeaf/Students');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Inertia\Response::render
     */
    public function goToPage(Request $request, $page = 'area-of-audit')
    {
        return Inertia::render('Yeaf/Maintenance', ['page' => $page]);
    }
}
