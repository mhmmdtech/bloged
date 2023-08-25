<?php

namespace App\Repositories;

use App\Models\Category;
use App\Enums;
use App\Services\FileManager\FileManager;

class CategoryRepository implements CategoryRepositoryInterface
{


    public function __construct(
        private FileManager $fileManagerService
    ) {
    }

    public function getAllActiveCategories(string $orderedColumn = 'id')
    {
        return Category::with('creator')
            ->where('status', Enums\CategoryStatus::Active->value)
            ->latest($orderedColumn)
            ->get();
    }

    public function getActiveCategoriesWithLimit(string $orderedColumn = 'id', int $limit = 3)
    {
        return Category::with('creator')
            ->where('status', Enums\CategoryStatus::Active->value)
            ->latest($orderedColumn)
            ->take($limit)
            ->get();
    }

    public function getPaginatedCategories(int $perPage = 5, string $orderedColumn = 'id')
    {
        return Category::latest($orderedColumn)->paginate($perPage);
    }

    public function getTrashedPaginatedCategories(int $perPage = 5, string $orderedColumn = 'deleted_at')
    {
        return Category::onlyTrashed()->latest($orderedColumn)->paginate($perPage);
    }

    public function getById($categoryId)
    {
        return Category::with('creator')->findOrFail($categoryId);
    }

    public function create(array $data)
    {
        $data['thumbnail'] = $this->fileManagerService->uploadMultiQualityImage(
            $data['thumbnail'],
            'categories' . DIRECTORY_SEPARATOR . 'thumbnails',
            $data['seo_title']
        );

        return auth()->user()->categories()->create($data);

    }

    public function update(Category $category, array $data)
    {
        if (isset($data['thumbnail'])) {
            $this->fileManagerService->deleteMultiQualityImage($category->thumbnail);
            $data['thumbnail'] = $this->fileManagerService->uploadMultiQualityImage(
                $data['thumbnail'],
                'categories' . DIRECTORY_SEPARATOR . 'thumbnails',
                $data['seo_title']
            );
        }

        $category->update($data);
    }

    public function delete(Category $category)
    {
        $category->delete();
    }

    public function restore(Category $category)
    {
        $category->restore();
    }
    public function forceDeleteAll()
    {
        $trashedCategories = Category::onlyTrashed()->get(['id', 'thumbnail']);

        $trashedCategories->each(function (Category $category) {
            $this->fileManagerService->deleteMultiQualityImage($category->thumbnail);

        });

        Category::whereIn('id', array_flatten($trashedCategories->toArray()))->forceDelete();
    }

    public function forceDelete(Category $category)
    {
        $this->fileManagerService->deleteMultiQualityImage($category->thumbnail);
        $category->forceDelete();
    }
}