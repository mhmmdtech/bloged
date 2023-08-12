<?php

namespace App\Http\Controllers\Administration;

use App\Enums\CityStatus;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreProvinceCityRequest;
use App\Http\Requests\Admin\UpdateProvinceCityRequest;
use App\Http\Resources\CityCollection;
use App\Http\Resources\CityResource;
use App\Http\Resources\ProvinceResource;
use App\Models\City;
use App\Models\Province;
use Illuminate\Http\Request;
use Inertia\Inertia;

class CityController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Province $province)
    {
        $this->authorize('browse city', City::class);

        $province = new ProvinceResource($province);

        $cities = new CityCollection($province->cities()->paginate(5));

        return Inertia::render('Admin/Cities/Index', compact('province', 'cities'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Province $province)
    {
        $this->authorize('add city', City::class);

        $statuses = CityStatus::array();

        $province = new ProvinceResource($province);

        return Inertia::render('Admin/Cities/Create', compact('statuses', 'province'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreProvinceCityRequest $request, Province $province)
    {
        $this->authorize('add city', City::class);

        $inputs = $request->validated();

        auth()->user()->cities()->create(array_merge($inputs, ['province_id' => $province->id]));

        return redirect()->route('administration.provinces.cities.index', $province->id);
    }

    /**
     * Display the specified resource.
     */
    public function show(City $city)
    {
        $this->authorize('read city', $city);

        $city->load('creator', 'province');

        $city = new CityResource($city);

        return Inertia::render('Admin/Cities/Show', compact('city'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(City $city)
    {
        $this->authorize('edit city', $city);

        $statuses = CityStatus::array();

        $city = new CityResource($city);

        return Inertia::render('Admin/Cities/Edit', compact('city', 'statuses'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateProvinceCityRequest $request, City $city)
    {
        $this->authorize('edit city', $city);

        $inputs = removeNullFromArray($request->validated());

        $city->update($inputs);

        return redirect()->route('administration.cities.show', $city->id);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(City $city)
    {
        $this->authorize('delete city', $city);

        $city->delete();

        return redirect()->route('administration.provinces.index');
    }
}