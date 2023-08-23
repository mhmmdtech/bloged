<?php

namespace App\Repositories;

use App\Models\Category;
use App\Enums;

class PostRepository implements PostRepositoryInterface
{
    public function getPublishedPostsByCategoryId(int $categoryId, string $orderedColumn = 'id', int $paginationCount = 5)
    {
        return Category::find($categoryId)
            ->posts()
            ->where('status', Enums\PostStatus::Published)
            ->with('author')
            ->latest($orderedColumn)
            ->paginate($paginationCount);
    }
}