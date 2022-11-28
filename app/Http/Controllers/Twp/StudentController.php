<?php

namespace App\Http\Controllers\Twp;

use App\Http\Requests\Twp\StudentStoreRequest;
use App\Http\Requests\Twp\StudentUpdateRequest;
use App\Models\Twp\ApplicationReason;
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
        $student = Student::where('id', $extra)->with('application', 'program', 'payments')->first();

        $reasons = ApplicationReason::all();
        $letter_file = match ($type) {
            'student_success_under_age' => 'twp.student-success-under-age',
            'student_denied' => 'twp.student-denied',
            'school_denied' => 'twp.school-denied',
            default => 'twp.student-success',
        };
        $pdf = PDF::loadView($letter_file, compact('admin', 'reasons', 'student', 'now_d'));
        $pdf->getDomPDF()->set_option("enable_php", true);
        $pdf->set_paper("Letter", "portrait");

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
        $students = $this->paginateGrants($students);
        [$countries, $provinces] = $this->getCountriesProvinces();

        return Inertia::render('Twp/Students', ['status' => true, 'results' => $students, 'countries' => $countries, 'provinces' => $provinces]);
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
     * @param  StudentStoreRequest  $request
     * @return \Inertia\Response
     */
    public function store(StudentStoreRequest $request)
    {
        $student = Student::create($request->validated());
        $students = new Student();
        $students = $this->paginateGrants($students);
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
        $student = Student::where('id', $student->id)->with('application', 'program', 'payments')->first();
        $applicationReasons = ApplicationReason::orderBy('reason_status', 'asc')->get();
        $provinces = Province::orderBy('province_code', 'asc')->get();

        return Inertia::render('Twp/StudentEdit', ['status' => true,
            'result' => $student, 'reasons' => $applicationReasons, 'provinces' => $provinces, ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Student  $studentTwp
     * @return \Illuminate\Http\Response
     */
    public function edit(Student $studentTwp)
    {
        //
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

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Student  $studentTwp
     * @return \Illuminate\Http\Response
     */
    public function destroy(Student $studentTwp)
    {
        //
    }

    private function paginateGrants($grants)
    {
        if (request()->filter_institution_student_number !== null) {
            $grants = $grants->where('institution_student_number', request()->filter_institution_student_number);
        }
        if (request()->filter_pen !== null) {
            $grants = $grants->where('pen', request()->filter_pen);
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
    }

    private function getCountriesProvinces()
    {
        return [Country::orderBy('country_code', 'asc')->get(), Province::orderBy('province_code', 'asc')->get()];
    }
}
