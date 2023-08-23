<?php

namespace App\Repositories;

interface CategoryRepositoryInterface
{
    public function getAllActiveCategories(string $orderedColumn = 'id');

    public function getActiveCategoriesWithLimit(string $orderedColumn = 'id', int $limit = 3);
}