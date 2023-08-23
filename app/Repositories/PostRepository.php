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

    public function getPublishedPostsPaginated(string $orderedColumn = 'id', int $perPage = 5)
    {
        return Post::with('category', 'author')
            ->where('status', Enums\PostStatus::Published->value)
            ->latest($orderedColumn)
            ->paginate($perPage);
    }

    public function getPostById($postId)
    {
        return Post::with('author', 'category')
            ->findOrFail($postId);
    }

    public function searchPostsPaginated(string $query = "", int $perPage = 5)
    {
        return Post::with('author', 'category')
            ->whereRaw("MATCH(title, seo_title, description, seo_description, body) AGAINST(? IN NATURAL LANGUAGE MODE WITH QUERY EXPANSION)", [$query])
            ->where('status', Enums\PostStatus::Published)
            ->paginate($perPage);
    }
}