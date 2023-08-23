<?php

namespace App\Repositories;

use App\Models\Province;

interface ProvinceRepositoryInterface
{
    public function getAllProvincesWithCities(string $orderedColumn = "id");

    public function getPaginatedProvinces(int $perPage = 5, string $orderedColumn = "id");

    public function getTrashedPaginatedProvinces(int $perPage = 5, string $orderedColumn = "deleted_at");

    public function getById(int $provinceId);

    public function create(array $data);

    public function update(Province $province, array $data);

    public function delete(Province $province);

    public function restore(Province $province);

    public function forceDeleteAll();

    public function forceDelete(Province $province);
}