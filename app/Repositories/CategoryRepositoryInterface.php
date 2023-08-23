<?php

namespace App\Repositories;

use App\Models\Category;

interface CategoryRepositoryInterface
{
    public function getAllActiveCategories(string $orderedColumn = 'id');

    public function getActiveCategoriesWithLimit(string $orderedColumn = 'id', int $limit = 3);

    public function getPaginatedCategories(int $perPage = 5, string $orderedColumn = "id");

    public function getTrashedPaginatedCategories(int $perPage = 5, string $orderedColumn = "deleted_at");

    public function getById(int $categoryId);

    public function create(array $data);

    public function update(Category $category, array $data);

    public function delete(Category $category);

    public function restore(Category $category);

    public function forceDeleteAll();

    public function forceDelete(Category $category);
}