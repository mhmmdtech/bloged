<?php

namespace App\Http\Controllers\Administration;

use App\Enums\ProvinceStatus;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreProvinceRequest;
use App\Http\Requests\Admin\UpdateProvinceRequest;
use App\Http\Resources\ProvinceCollection;
use App\Http\Resources\ProvinceResource;
use App\Models\Province;
use App\Repositories\ProvinceRepositoryInterface;
use Inertia\Inertia;

class ProvinceController extends Controller
{
    public function __construct(
        private ProvinceRepositoryInterface $provinceRepository
    ) {
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $this->authorize('browse province', Province::class);

        $provinces = new ProvinceCollection(
            $this->provinceRepository->getPaginatedProvinces(
                $this->administrationPaginatedItemsCount,
                $this->normalOrderedColumn
            )
        );

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

        $this->provinceRepository->create($inputs);

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

        $this->provinceRepository->update($province, $inputs);

        return redirect()->route('administration.provinces.index');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Province $province)
    {
        $this->authorize('delete province', $province);

        $this->provinceRepository->delete($province);

        return redirect()->route('administration.provinces.index');
    }

    /**
     * Display a listing of the soft deleted resource.
     */
    public function trashed()
    {
        $this->authorize('delete province', Province::class);

        $provinces = new ProvinceCollection(
            $this->provinceRepository->getTrashedPaginatedProvinces(
                $this->administrationPaginatedItemsCount,
                $this->trashedOrderedColumn,
            )
        );

        return Inertia::render('Admin/Provinces/Trashed', compact('provinces'));
    }

    /**
     * force delete the specified resource from storage.
     */
    public function forceDelete(?Province $province = null)
    {
        $this->authorize('delete province', Province::class);

        if (is_null($province)) {
            $this->provinceRepository->forceDeleteAll();
            return redirect()->route('administration.provinces.trashed');
        }

        $this->provinceRepository->forceDelete($province);
        return redirect()->route('administration.provinces.trashed');
    }

    /**
     * restore the specified resource from storage.
     */
    public function restore(Province $province)
    {
        $this->authorize('delete province', Province::class);

        $this->provinceRepository->restore($province);

        return redirect()->route('administration.provinces.trashed');
    }
}