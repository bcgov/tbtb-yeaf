<?php

namespace App\Http\Controllers\Yeaf;

use App\Http\Requests\StudentStoreRequest;
use App\Http\Requests\StudentUpdateRequest;
use App\Models\User;
use App\Models\Yeaf\Batch;
use App\Models\Yeaf\Country;
use App\Models\Yeaf\Ineligible;
use App\Models\Yeaf\Institution;
use App\Models\Yeaf\Program;
use App\Models\Yeaf\ProgramYear;
use App\Models\Yeaf\Province;
use App\Models\Yeaf\Student;
use Illuminate\Support\Facades\Redirect;
use Inertia\Inertia;

class StudentController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Inertia\Response
     */
    public function index()
    {
        $students = new Student();
        $students = $this->paginateGrants($students);
        [$countries, $provinces] = $this->getCountriesProvinces();

        return Inertia::render('Yeaf/Students', ['status' => true, 'results' => $students, 'countries' => $countries, 'provinces' => $provinces]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  StudentStoreRequest  $request
     * @return \Inertia\Response
     */
    public function store(StudentStoreRequest $request)
    {
        $student = Student::create($request->validated());
        $students = new Student();
        $students = $this->paginateGrants($students);
        [$countries, $provinces] = $this->getCountriesProvinces();

        return Inertia::render('Yeaf/Students', ['status' => true, 'student' => $student, 'results' => $students, 'countries' => $countries, 'provinces' => $provinces]);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Yeaf\Student  $student
     * @return \Inertia\Response
     */
    public function show(Student $student)
    {
        $student = Student::where('id', $student->id)->with('grants.school', 'grants.grantPendingIneligibles', 'grants.grantDeniedIneligibles', 'grants.appeals', 'comments')->first();
        $countries = Country::orderBy('country_code', 'asc')->get();
        $provinces = Province::orderBy('province_code', 'asc')->get();

        $program_types = Program::orderBy('program_description', 'asc')->get();
        $program_years = ProgramYear::orderBy('year_start', 'desc')->get();
        $schools = Institution::orderBy('name', 'asc')->get();
        $batches = Batch::orderBy('batch_number', 'desc')->get();
        $active_staff = User::where('disabled', 'false')->orderBy('user_id', 'asc')->get();
        $all_staff = User::orderBy('user_id', 'asc')->get();
        $ineligibles = Ineligible::orderBy('description')->get();

        return Inertia::render('Yeaf/StudentEdit', ['status' => true,
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
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\StudentUpdateRequest  $request
     * @param  \App\Models\Yeaf\Student  $student
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(StudentUpdateRequest $request, Student $student)
    {
        Student::where('id', $student->id)->update($request->validated());
        $student = Student::find($student->id);

        return Redirect::route('students.show', [$student->id]);
    }

    private function paginateGrants($grants)
    {
        if (request()->filter_sin !== null) {
            $grants = $grants->where('sin', request()->filter_sin);
        }

        if (request()->filter_fname !== null) {
            $grants = $grants->where('first_name', 'ILIKE', '%'.request()->filter_fname.'%');
        }
        if (request()->filter_lname !== null) {
            $grants = $grants->where('last_name', 'ILIKE', '%'.request()->filter_lname.'%');
        }

        if (request()->sort !== null) {
            $grants = $grants->orderBy(request()->sort, request()->direction);
        } else {
            $grants = $grants->orderBy('created_at', 'desc');
        }

        return $grants->paginate(25)->onEachSide(1)->appends(request()->query());
    }

    private function getCountriesProvinces()
    {
        return [Country::orderBy('country_code', 'asc')->get(), Province::orderBy('province_code', 'asc')->get()];
    }
}
