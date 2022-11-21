<?php

namespace App\Http\Controllers\Twp;

use App\Models\Twp\Student;
use App\Models\User;
use App\Models\Yeaf\Country;
use App\Models\Yeaf\Province;
use Illuminate\Http\Request;
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
     * @param  \App\Models\Yeaf\Student  $student
     * @return \Inertia\Response
     */
    public function show(Student $student)
    {
        $student = Student::where('id', $student->id)->with('application', 'program', 'payments')->first();
        $countries = Country::orderBy('country_code', 'asc')->get();
        $provinces = Province::orderBy('province_code', 'asc')->get();


        return Inertia::render('Twp/StudentEdit', ['status' => true,
            'result' => $student, 'countries' => $countries, 'provinces' => $provinces, ]);
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
     * @param  \App\Models\Student  $studentTwp
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Student $studentTwp)
    {
        //
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
