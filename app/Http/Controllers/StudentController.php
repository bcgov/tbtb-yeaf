<?php

namespace App\Http\Controllers;

use App\Http\Requests\StudentUpdateRequest;
use App\Models\Batch;
use App\Models\Country;
use App\Models\Ineligible;
use App\Models\Institution;
use App\Models\Program;
use App\Models\ProgramYear;
use App\Models\Province;
use App\Models\Student;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Inertia\Inertia;

class StudentController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $students = new Student();
        $students = $this->paginateGrants($students);

        return Inertia::render('Students', ['status' => true, 'results' => $students]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Student  $student
     * @return \Inertia\Response
     */
    public function show(Student $student)
    {
        $student = Student::where('id', $student->id)->with('grants.grantPendingIneligibles', 'grants.grantDeniedIneligibles', 'grants.appeals', 'comments')->first();
        $countries = Country::orderBy('country_code', 'asc')->get();
        $provinces = Province::orderBy('province_code', 'asc')->get();

        $program_types = Program::orderBy('program_description', 'asc')->get();
        $program_years = ProgramYear::orderBy('year_start', 'desc')->get();
        $schools = Institution::orderBy('name', 'asc')->get();
        $batches = Batch::orderBy('batch_number', 'desc')->get();
        $active_staff = User::where('disabled', 'false')->orderBy('user_id', 'asc')->get();
        $all_staff = User::orderBy('user_id', 'asc')->get();
        $ineligibles = Ineligible::orderBy('description')->get();

        return Inertia::render('StudentEdit', ['status' => true,
            'program_types' => $program_types,
            'program_years' => $program_years,
            'schools' => $schools,
            'batches' => $batches,
            'ineligibles' => $ineligibles,
            'active_staff' => $active_staff,
            'all_staff' => $all_staff,
            'result' => $student, 'countries' => $countries, 'provinces' => $provinces, ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Student  $student
     * @return \Illuminate\Http\Response
     */
    public function edit(Student $student)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\StudentUpdateRequest  $request
     * @param  \App\Models\Student  $student
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(StudentUpdateRequest $request, Student $student)
    {
        return Redirect::route('students.show', [$student->id]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Student  $student
     * @return \Illuminate\Http\Response
     */
    public function destroy(Student $student)
    {
        //
    }

    private function paginateGrants($grants)
    {
        if (request()->filter_sin !== null) {
            $grants = $grants->where('sin', request()->filter_sin);
        }

        if (request()->filter_fname !== null) {
            $grants = $grants->where('first_name', 'ILIKE', request()->filter_fname);
        }
        if (request()->filter_lname !== null) {
            $grants = $grants->where('last_name', 'ILIKE', request()->filter_lname);
        }

        if (request()->sort !== null) {
            $grants = $grants->orderBy(request()->sort, request()->direction);
        } else {
            $grants = $grants->orderBy('created_at', 'desc');
        }

        return $grants->paginate(25)->onEachSide(1)->appends(request()->query());
//        return $grants->isActive()->with('institution')->paginate(25)->onEachSide(1)->appends(request()->query());
    }
}
