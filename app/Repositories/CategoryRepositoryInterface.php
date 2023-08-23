<?php

namespace App\Repositories;

interface CategoryRepositoryInterface
{
    public function getAllActiveCategories(string $orderedColumn = 'id');
}