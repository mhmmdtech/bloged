<?php

namespace App\Repositories;

use App\Models\Province;

class ProvinceRepository
{
    public function getPaginatedProvinces(int $perPage = 5, string $orderedColumn = "id")
    {
        return Province::latest($orderedColumn)->paginate($perPage);
    }

    public function getTrashedPaginatedProvinces(int $perPage = 5, string $orderedColumn = "deleted_at")
    {
        return Province::onlyTrashed()->latest($orderedColumn)->paginate($perPage);
    }

    public function getById(int $provinceId)
    {
        return Province::with('creator')
            ->findOrFail($provinceId);
        ;
    }

    public function create(array $data)
    {
        return auth()->user()->provinces()->create($data);
    }

    public function update(Province $province, array $data)
    {
        $province->update($data);
    }

    public function delete(Province $province)
    {
        $province->delete();
    }

    public function restore(Province $province)
    {
        $province->restore();
    }

    public function forceDeleteAll()
    {
        $trashedProvinces = Province::onlyTrashed()->get(['id']);
        Province::whereIn('id', array_flatten($trashedProvinces->toArray()))->forceDelete();
    }

    public function forceDelete(Province $province)
    {
        $province->forceDelete();
    }
}