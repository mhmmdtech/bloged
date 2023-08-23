<?php

namespace App\Repositories;

interface PostRepositoryInterface
{
    public function getPublishedPostsByCategoryId(int $categoryId, string $orderedColumn = 'id', int $paginationCount = 5);
}