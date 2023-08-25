<?php

namespace App\Repositories;

use App\Models\City;
use App\Models\Province;

interface CityRepositoryInterface
{
    public function getPaginatedCities(Province $province, int $perPage = 5, string $orderedColumn = "id");

    public function getById(int $cityId);

    public function create(Province $province, array $data);

    public function update(City $city, array $data);

    public function delete(City $city);
}