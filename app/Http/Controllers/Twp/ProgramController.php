<?php

namespace App\Http\Controllers\Twp;

use App\Http\Requests\Twp\ProgramEditRequest;
use App\Http\Requests\Twp\ProgramStoreRequest;
use App\Models\Twp\Program;
use App\Models\Twp\ProgramHistory;
use Illuminate\Support\Facades\Redirect;

class ProgramController extends Controller
{
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
     * @param  ProgramStoreRequest  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(ProgramStoreRequest $request)
    {
        $application = Program::create($request->validated());

        return Redirect::route('twp.students.show', [$application->twp_student_id]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  ProgramEditRequest  $request
     * @param  \App\Models\Twp\Program  $program
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(ProgramEditRequest $request, Program $program)
    {
        $history = $program->toArray();
//        $history = $program;
        $history['program_twp_id'] = $program->id;
//        $history->save();
        ProgramHistory::create($history);

        Program::where('id', $program->id)->update($request->validated());
        $program = Program::find($program->id);

        return Redirect::route('twp.students.show', [$program->twp_student_id]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Program  $programTwp
     * @return \Illuminate\Http\Response
     */
    public function destroy(Program $programTwp)
    {
        //
    }
}
