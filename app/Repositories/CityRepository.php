<?php

namespace App\Repositories;

use App\Models\City;
use App\Models\Province;

class CityRepository
{
    public function getPaginatedCities(Province $province, int $perPage = 5, string $orderedColumn = "id")
    {
        return $province->cities()->paginate($perPage);
    }

    public function getById(int $cityId)
    {
        return City::with('creator', 'province')
            ->findOrFail($cityId);
    }

    public function create(Province $province, array $data)
    {
        return auth()->user()->cities()->create(array_merge($data, ['province_id' => $province->id]));
    }

    public function update(City $city, array $data)
    {
        $city->update($data);
    }

    public function delete(City $city)
    {
        $city->delete();
    }
}