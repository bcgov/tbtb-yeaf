<?php

namespace App\Http\Controllers;

use App\Http\Requests\StudentUpdateRequest;
use App\Models\Country;
use App\Models\Province;
use App\Models\Student;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Inertia\Inertia;

class StudentController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Inertia\Response::render
     */
    public function dashboard()
    {
        $students = new Student();
        $students = $this->paginateGrants($students);

        return Inertia::render('Dashboard', ['status' => true, 'results' => $students]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
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
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @return \Inertia\Response
     */
    public function show(Student $student)
    {
        $student = Student::where('id', $student->id)->with('grants', 'appeals', 'comments')->first();
        $countries = Country::orderBy('country_code', 'asc')->get();
        $provinces = Province::orderBy('province_code', 'asc')->get();

        return Inertia::render('StudentEdit', ['status' => true, 'result' => $student, 'countries' => $countries, 'provinces' => $provinces]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function edit(Student $student)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(StudentUpdateRequest $request, Student $student)
    {

        return Redirect::route('students.show', [$student->id]);
    }

    /**
     * Remove the specified resource from storage.
     *
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
