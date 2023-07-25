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
     * Store a newly created resource in storage.
     *
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
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(ProgramEditRequest $request, Program $program)
    {
        $history = $program->toArray();
        $history['program_twp_id'] = $program->id;
        ProgramHistory::create($history);

        Program::where('id', $program->id)->update($request->validated());
        $program = Program::find($program->id);

        return Redirect::route('twp.students.show', [$program->twp_student_id]);
    }
}
