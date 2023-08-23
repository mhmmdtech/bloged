<?php

namespace App\Repositories;

interface PostRepositoryInterface
{
    public function getFeaturedPost();

    public function getPublishedPostsByCategoryId(int $categoryId, string $orderedColumn = 'id', int $paginationCount = 5);

    public function getLatestPublishedPosts(string $orderedColumn = 'id', int $limit = 6);

    public function getPublishedPostsPaginated(string $orderedColumn = 'id', int $perPage = 5);

    public function getPostById(int $postId);

    public function searchPostsPaginated(string $query = "", int $perPage = 5);
}