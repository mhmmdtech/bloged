<?php

namespace App\Http\Controllers\Administration;

use App\Enums\ProvinceStatus;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreProvinceRequest;
use App\Http\Requests\Admin\UpdateProvinceRequest;
use App\Http\Resources\ProvinceCollection;
use App\Http\Resources\ProvinceResource;
use App\Models\Province;
use Inertia\Inertia;

class ProvinceController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $this->authorize('browse province', Province::class);

        $provinces = new ProvinceCollection(Province::latest()->paginate(5));

        return Inertia::render('Admin/Provinces/Index', compact('provinces'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $this->authorize('add province', Province::class);

        $statuses = ProvinceStatus::array();

        return Inertia::render('Admin/Provinces/Create', compact('statuses'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreProvinceRequest $request)
    {
        $this->authorize('add province', Province::class);

        $inputs = $request->validated();

        auth()->user()->provinces()->create($inputs);

        return redirect()->route('administration.provinces.index');
    }

    /**
     * Display the specified resource.
     */
    public function show(Province $province)
    {
        $this->authorize('read province', $province);

        $province->load('creator');

        $province = new ProvinceResource($province);

        return Inertia::render('Admin/Provinces/Show', compact('province'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Province $province)
    {
        $this->authorize('edit province', $province);

        $statuses = ProvinceStatus::array();

        $province = new ProvinceResource($province);

        return Inertia::render('Admin/Provinces/Edit', compact('province', 'statuses'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateProvinceRequest $request, Province $province)
    {
        $this->authorize('edit province', $province);

        $inputs = removeNullFromArray($request->validated());

        $province->update($inputs);

        return redirect()->route('administration.provinces.index');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Province $province)
    {
        $this->authorize('delete province', $province);

        $province->delete();

        return redirect()->route('administration.provinces.index');
    }

    /**
     * Display a listing of the soft deleted resource.
     */
    public function trashed()
    {
        $this->authorize('delete province', Province::class);

        $provinces = new ProvinceCollection(Province::onlyTrashed()->latest()->paginate(5));

        return Inertia::render('Admin/Provinces/Trashed', compact('provinces'));
    }

    /**
     * force delete the specified resource from storage.
     */
    public function forceDelete($provinceId = null)
    {
        $this->authorize('delete province', Province::class);

        if (is_null($provinceId)) {
            $trashedProvinces = Province::onlyTrashed()->get(['id'])->toArray();
            Province::whereIn('id', array_flatten($trashedProvinces))->forceDelete();
            return redirect()->route('administration.provinces.trashed');
        }

        $province = Province::withTrashed()->findOrFail($provinceId);
        $province->forceDelete();
        return redirect()->route('administration.provinces.trashed');
    }

    /**
     * restore the specified resource from storage.
     */
    public function restore($provinceId)
    {
        $this->authorize('delete province', Province::class);
        $province = Province::withTrashed()->findOrFail($provinceId);
        $province->restore();
        return redirect()->route('administration.provinces.trashed');
    }
}