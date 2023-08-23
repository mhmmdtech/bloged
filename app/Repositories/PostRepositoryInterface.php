<?php

namespace App\Repositories;

use App\Models\Post;

interface PostRepositoryInterface
{
    public function getFeaturedPost();

    public function getPublishedPostsByCategoryId(int $categoryId, string $orderedColumn = 'id', int $paginationCount = 5);

    public function getLatestPublishedPosts(string $orderedColumn = 'id', int $limit = 6);

    public function getPublishedPostsPaginated(string $orderedColumn = 'id', int $perPage = 5);

    public function searchPostsPaginated(string $query = "", int $perPage = 5);

    public function getPaginatedPosts(int $perPage = 5, string $orderedColumn = "id");

    public function getTrashedPaginatedPosts(int $perPage = 5, string $orderedColumn = "deleted_at");

    public function getById(int $postId);

    public function create(array $data);

    public function update(Post $post, array $data);

    public function delete(Post $post);

    public function restore(Post $post);

    public function forceDeleteAll();

    public function forceDelete(Post $post);

    public function toggleFeatured(Post $post);
}