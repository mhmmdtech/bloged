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
use App\Repositories\CityRepository;
use Inertia\Inertia;

class CityController extends Controller
{
    public function __construct(
        private CityRepository $cityRepository
    ) {
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Province $province)
    {
        $this->authorize('browse city', City::class);

        $cities = new CityCollection(
            $this->cityRepository->getPaginatedCities(
                $province,
                $this->administrationPaginatedItemsCount,
                $this->normalOrderedColumn
            )
        );

        $province = new ProvinceResource($province);

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

        $this->cityRepository->create($province, $inputs);

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

        $this->cityRepository->update($city, $inputs);

        return redirect()->route('administration.cities.show', $city->id);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(City $city)
    {
        $this->authorize('delete city', $city);

        $this->cityRepository->delete($city);

        return redirect()->route('administration.provinces.index');
    }
}