<?php

namespace App\Repositories;

use App\Models\Category;
use App\Enums;

class CategoryRepository implements CategoryRepositoryInterface
{

    public function getAllActiveCategories(string $orderedColumn = 'id')
    {
        return Category::with('creator')
            ->where('status', Enums\CategoryStatus::Active->value)
            ->latest($orderedColumn)
            ->get();
    }
}