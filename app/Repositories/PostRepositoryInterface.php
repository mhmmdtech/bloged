<?php

namespace App\Repositories;

interface PostRepositoryInterface
{
    public function getFeaturedPost();

    public function getPublishedPostsByCategoryId(int $categoryId, string $orderedColumn = 'id', int $paginationCount = 5);

    public function getLatestPublishedPosts(string $orderedColumn = 'id', int $limit = 6);
}