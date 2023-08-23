<?php

namespace App\Repositories;

use App\Models\Category;
use App\Enums;
use App\Models\Post;
use App\Services\FileManager\FileManager;

class PostRepository implements PostRepositoryInterface
{

    public function __construct(
        private FileManager $fileManagerService
    ) {
    }

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

    public function searchPostsPaginated(string $query = "", int $perPage = 5)
    {
        return Post::with('author', 'category')
            ->whereRaw("MATCH(title, seo_title, description, seo_description, body) AGAINST(? IN NATURAL LANGUAGE MODE WITH QUERY EXPANSION)", [$query])
            ->where('status', Enums\PostStatus::Published)
            ->paginate($perPage);
    }

    public function getPaginatedPosts(int $perPage = 5, string $orderedColumn = "id")
    {
        return Post::with('author', 'category')
            ->latest($orderedColumn)
            ->paginate($perPage);
    }

    public function getTrashedPaginatedPosts(int $perPage = 5, string $orderedColumn = "deleted_at")
    {
        return post::onlyTrashed()
            ->latest($orderedColumn)
            ->paginate($perPage);
    }

    public function getById($postId)
    {
        return Post::with('author', 'category')
            ->findOrFail($postId);
    }

    public function create(array $data)
    {
        $data['thumbnail'] = $this->fileManagerService
            ->uploadMultiQualityImage(
                $data['thumbnail'],
                'posts' . DIRECTORY_SEPARATOR . 'thumbnails',
                $data['seo_title']
            );

        return auth()->user()->posts()->create($data);
    }

    public function update(Post $post, array $data)
    {
        if (isset($data['thumbnail'])) {
            $this->fileManagerService->deleteMultiQualityImage($post->thumbnail);
            $data['thumbnail'] = $this->fileManagerService
                ->uploadMultiQualityImage(
                    $data['thumbnail'],
                    'posts' . DIRECTORY_SEPARATOR . 'thumbnails',
                    $data['seo_title']
                );
        }

        $post->update($data);
    }

    public function delete(Post $post)
    {
        $post->delete();
    }

    public function restore(Post $post)
    {
        $post->restore();
    }

    public function forceDeleteAll()
    {
        $trashedPosts = Post::onlyTrashed()->get(['id', 'thumbnail']);
        $trashedPosts->each(function (Post $post) {
            $this->fileManagerService->deleteMultiQualityImage($post->thumbnail);

        });
        Post::whereIn('id', array_flatten($trashedPosts->toArray()))->forceDelete();
    }

    public function forceDelete(Post $post)
    {
        $this->fileManagerService->deleteMultiQualityImage($post->thumbnail);
        $post->forceDelete();
    }

    public function toggleFeatured(Post $post)
    {
        if ($post->is_featured) {
            $post->update(['is_featured' => false]);
            return;
        }

        // Disable the previously featured post
        Post::where('is_featured', true)->update(['is_featured' => false]);

        $post->update(['is_featured' => true]);
    }
}