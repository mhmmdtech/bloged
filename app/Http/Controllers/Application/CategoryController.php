<?php

namespace App\Http\Controllers\Application;

use App\Enums;
use App\Http\Controllers\Controller;
use App\Http\Resources\CategoryCollection;
use App\Http\Resources\CategoryResource;
use App\Http\Resources\PostCollection;
use App\Models\Category;
use App\Repositories\CategoryRepositoryInterface;
use App\Repositories\PostRepositoryInterface;
use Inertia\Inertia;

class CategoryController extends Controller
{
    public function __construct(
        private CategoryRepositoryInterface $categoryRepository,
        private PostRepositoryInterface $postRepository
    ) {
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {

        $categories = new CategoryCollection($this->categoryRepository->getAllActiveCategories($this->normalOrderedColumn));

        return Inertia::render('App/Categories/Index', compact('categories'));
    }

    /**
     * Display the specified resource.
     */
    public function show(Category $category)
    {
        $category->load('posts');

        $category = new CategoryResource($category);

        $posts = new PostCollection($this->postRepository->getPublishedPostsByCategoryId($category->id, $this->normalOrderedColumn, $this->applicationPaginatedItemsCount));

        return Inertia::render('App/Categories/Single', compact('category', 'posts'));
    }
}