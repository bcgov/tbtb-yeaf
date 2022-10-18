<?php

namespace App\Http\Controllers;

use App\Http\Requests\InstitutionStoreRequest;
use App\Http\Requests\InstitutionUpdateRequest;
use App\Models\Country;
use App\Models\Institution;
use App\Models\Province;
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
        [$countries, $provinces] = $this->getCountriesProvinces();

        return Inertia::render('Schools', ['status' => true, 'results' => $schools, 'countries' => $countries, 'provinces' => $provinces]);
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
     * @param  InstitutionStoreRequest  $request
     * @return \Inertia\Response
     */
    public function store(InstitutionStoreRequest $request)
    {
        $institution = Institution::create($request->validated());
        $schools = new Institution();
        $schools = $this->paginateSchools($schools);

        [$countries, $provinces] = $this->getCountriesProvinces();

        return Inertia::render('Schools', ['status' => true, 'school' => $institution, 'results' => $schools, 'countries' => $countries, 'provinces' => $provinces]);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Institution  $institution
     * @return \Inertia\Response
     */
    public function show(Institution $institution)
    {
        [$countries, $provinces] = $this->getCountriesProvinces();

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
     * @param  InstitutionUpdateRequest  $request
     * @param  \App\Models\Institution  $institution
     * @return \Inertia\Response
     */
    public function update(InstitutionUpdateRequest $request, Institution $institution)
    {
        Institution::where('id', $institution->id)->update($request->validated());
        [$countries, $provinces] = $this->getCountriesProvinces();

        return Inertia::render('SchoolEdit', ['status' => true, 'result' => $institution, 'countries' => $countries, 'provinces' => $provinces]);
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
            $schools = $schools->where('name', 'ILIKE', '%'.request()->filter_name.'%');
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

    private function getCountriesProvinces()
    {
        return [Country::orderBy('country_code', 'asc')->get(), Province::orderBy('province_code', 'asc')->get()];
    }
}
