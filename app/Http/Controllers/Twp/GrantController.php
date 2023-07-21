<?php

namespace App\Http\Controllers\Twp;

use App\Http\Requests\Twp\GrantEditRequest;
use App\Http\Requests\Twp\GrantStoreRequest;
use App\Models\Twp\Grant;
use Illuminate\Support\Facades\Redirect;

class GrantController extends Controller
{
    /**
     * Store a newly created resource in storage.
     *
     * @param  GrantStoreRequest  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(GrantStoreRequest $request)
    {
        $grant = Grant::create($request->validated());

        return Redirect::route('twp.students.show', [$grant->twp_student_id]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  GrantEditRequest  $request
     * @param  \App\Models\Twp\Grant  $grant
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(GrantEditRequest $request, Grant $grant)
    {
        Grant::where('id', $grant->id)->update($request->validated());
        $grant = Grant::find($grant->id);

        return Redirect::route('twp.students.show', [$grant->twp_student_id]);
    }
}
