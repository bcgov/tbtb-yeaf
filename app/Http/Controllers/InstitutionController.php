<?php

namespace App\Http\Controllers;

use App\Models\Country;
use App\Models\Institution;
use App\Models\Province;
use Illuminate\Http\Request;
use Inertia\Inertia;

class InstitutionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Inertia\Response
     */
    public function index()
    {
        $schools = new Institution();
        $schools = $this->paginateSchools($schools);

        return Inertia::render('Schools', ['status' => true, 'results' => $schools]);

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
     * @param  \App\Models\Institution  $institution
     * @return \Inertia\Response
     */
    public function show(Institution $institution)
    {
        $countries = Country::orderBy('country_code', 'asc')->get();
        $provinces = Province::orderBy('province_code', 'asc')->get();

        return Inertia::render('SchoolEdit', ['status' => true, 'result' => $institution, 'countries' => $countries, 'provinces' => $provinces]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Institution  $institution
     * @return \Illuminate\Http\Response
     */
    public function edit(Institution $institution)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Institution  $institution
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Institution $institution)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Institution  $institution
     * @return \Illuminate\Http\Response
     */
    public function destroy(Institution $institution)
    {
        //
    }


    private function paginateSchools($schools)
    {
        if (request()->filter_name !== null) {
            $schools = $schools->where('name', request()->filter_name);
        }

        if (request()->filter_city !== null) {
            $schools = $schools->where('city', 'ILIKE', request()->filter_city);
        }


        if (request()->sort !== null) {
            $schools = $schools->orderBy(request()->sort, request()->direction);
        } else {
            $schools = $schools->orderBy('created_at', 'desc');
        }

        return $schools->paginate(25)->onEachSide(1)->appends(request()->query());
    }
}
