<?php

namespace App\Http\Controllers\Twp;

use App\Http\Requests\Twp\ApplicationEditRequest;
use App\Http\Requests\Twp\ApplicationStoreRequest;
use App\Models\Twp\Application;
use App\Models\Twp\Reason;
use Illuminate\Support\Facades\Redirect;

class ApplicationController extends Controller
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
     * @param  ApplicationEditRequest  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(ApplicationStoreRequest $request)
    {
        $application = Application::create($request->validated());

        return Redirect::route('twp.students.show', [$application->twp_student_id]);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Application  $application
     * @return \Illuminate\Http\Response
     */
    public function show(Application $application)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Twp\Application  $application
     * @return \Illuminate\Http\Response
     */
    public function edit(Application $application)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  ApplicationEditRequest  $request
     * @param  \App\Models\Twp\Application  $application
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(ApplicationEditRequest $request, Application $application)
    {
        Application::where('id', $application->id)->update($request->validated());
        $application = Application::find($application->id);

        $application->reasons()->detach();
        if($application->application_status == 'DENIED'){
            foreach ($request->reasons as $reason_id)
            {
                $reason = Reason::find($reason_id);
                $application->reasons()->attach($reason);
            }
        }

        return Redirect::route('twp.students.show', [$application->twp_student_id]);
    }

}
