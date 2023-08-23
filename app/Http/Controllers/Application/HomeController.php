<?php

namespace App\Http\Controllers\Application;

use App\Enums;
use App\Http\Controllers\Controller;
use App\Http\Resources\CategoryCollection;
use App\Http\Resources\PostCollection;
use App\Http\Resources\PostResource;
use App\Models\Category;
use App\Models\Post;
use App\Repositories\CategoryRepositoryInterface;
use App\Repositories\PostRepositoryInterface;
use Illuminate\Http\Request;
use Inertia\Inertia;

class HomeController extends Controller
{
    public function __construct(
        private CategoryRepositoryInterface $categoryRepository,
        private PostRepositoryInterface $postRepository
    ) {
    }

    /**
     * Handle the incoming request.
     */
    public function __invoke(Request $request)
    {
        $featuredPost = $this->postRepository->getFeaturedPost();

        if (collect($featuredPost)->isNotEmpty())
            $featuredPost = new PostResource($featuredPost);

        $latestPosts = new PostCollection($this->postRepository->getLatestPublishedPosts('id', 6));

        $categories = new CategoryCollection($this->categoryRepository->getActiveCategoriesWithLimit('id', 3));

        return Inertia::render('App/Home', compact('featuredPost', 'latestPosts', 'categories'));
    }
}