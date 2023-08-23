<?php

namespace App\Repositories;

use App\Models\City;

interface CityRepositoryInterface
{
    public function getPaginatedCities(int $perPage = 5, string $orderedColumn = "id");

    public function getById(int $cityId);

    public function create(array $data);

    public function update(City $city, array $data);

    public function delete(City $city);
}