<?php

namespace App\Repositories;

use App\Models\Category;
use App\Enums;
use App\Models\Post;

class PostRepository implements PostRepositoryInterface
{
    public function getFeaturedPost()
    {
        return Post::where('is_featured', true)->first() ?? null;
    }

    public function getPublishedPostsByCategoryId(int $categoryId, string $orderedColumn = 'id', int $paginationCount = 5)
    {
        return Category::find($categoryId)
            ->posts()
            ->where('status', Enums\PostStatus::Published)
            ->with('author')
            ->latest($orderedColumn)
            ->paginate($paginationCount);
    }

    public function getLatestPublishedPosts(string $orderedColumn = 'id', int $limit = 6)
    {
        return Post::with('category', 'author')
            ->where('status', Enums\PostStatus::Published->value)
            ->latest($orderedColumn)
            ->take($limit)
            ->get();
    }
}