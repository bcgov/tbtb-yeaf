<?php

namespace App\Http\Controllers\Twp;

use App\Http\Requests\Twp\StudentStoreRequest;
use App\Http\Requests\Twp\StudentUpdateRequest;
use App\Models\Twp\Institution;
use App\Models\Twp\Reason;
use App\Models\Twp\Student;
use App\Models\Yeaf\Admin;
use App\Models\Yeaf\Country;
use App\Models\Yeaf\Province;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Inertia\Inertia;
use PDF;

class StudentController extends Controller
{
    public function downloadLetter(Request $request, $type, $extra)
    {
        $admin = Admin::first();
        $now_d = date('F d, Y');
        $student = Student::where('id', $extra)->with('application.reasons', 'program', 'payments')->first();

        $reasons = Reason::all();
        $letter_file = match ($type) {
            'student_success_under_age' => 'twp.student-success-under-age',
            'student_denied' => 'twp.student-denied',
            'school_denied' => 'twp.school-denied',
            default => 'twp.student-success',
        };
        $pdf = PDF::loadView($letter_file, compact('admin', 'reasons', 'student', 'now_d'));
        $pdf->getDomPDF()->set_option('enable_php', true);
        $pdf->set_paper('Letter', 'portrait');
        $file_name = $student->birth_date;

        return $pdf->download(mt_rand().'-'.$file_name.'-letter.pdf');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Inertia\Response
     */
    public function index()
    {
        $students = new Student();
        $students = $this->paginateStudents($students);
        [$countries, $provinces] = $this->getCountriesProvinces();

        return Inertia::render('Twp/Students', ['status' => true, 'results' => $students, 'countries' => $countries, 'provinces' => $provinces]);
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
        $students = $this->paginateStudents($students);
        [$countries, $provinces] = $this->getCountriesProvinces();

        return Inertia::render('Twp/Students', ['status' => true, 'student' => $student, 'results' => $students, 'countries' => $countries, 'provinces' => $provinces]);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Yeaf\Student  $student
     * @return \Inertia\Response
     */
    public function show(Student $student)
    {
        $student = Student::where('id', $student->id)->with('application.reasons', 'program', 'payments')->first();
        $reasons = Reason::orderBy('reason_status', 'asc')->get();
        $schools = Institution::orderBy('name', 'asc')->get();
        $provinces = Province::orderBy('province_code', 'asc')->get();

        return Inertia::render('Twp/StudentEdit', ['status' => true, 'schools' => $schools,
            'result' => $student, 'reasons' => $reasons, 'provinces' => $provinces, ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Twp\Student  $student
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(StudentUpdateRequest $request, Student $student)
    {
        Student::where('id', $student->id)->update($request->validated());
        $student = Student::find($student->id);

        return Redirect::route('twp.students.show', [$student->id]);
    }

    private function paginateStudents($students)
    {
        $students = $students->with('application');
        if (request()->filter_institution_student_number !== null) {
            $students = $students->where('institution_student_number', request()->filter_institution_student_number);
        }
        if (request()->filter_pen !== null) {
            $students = $students->where('pen', request()->filter_pen);
        }

        if (request()->filter_fname !== null) {
            $students = $students->where('first_name', 'ILIKE', request()->filter_fname);
        }
        if (request()->filter_lname !== null) {
            $students = $students->where('last_name', 'ILIKE', request()->filter_lname);
        }

        if (request()->sort !== null) {
            $students = $students->orderBy(request()->sort, request()->direction);
        } else {
            $students = $students->orderBy('created_at', 'desc');
        }

        return $students->paginate(25)->onEachSide(1)->appends(request()->query());
    }

    private function getCountriesProvinces()
    {
        return [Country::orderBy('country_code', 'asc')->get(), Province::orderBy('province_code', 'asc')->get()];
    }
}
