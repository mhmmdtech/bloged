<?php

namespace App\Http\Controllers\Application;

use App\Enums\CategoryStatus;
use App\Enums\PostStatus;
use App\Http\Controllers\Controller;
use App\Http\Resources\CategoryCollection;
use App\Http\Resources\PostCollection;
use App\Http\Resources\PostResource;
use App\Models\Category;
use App\Models\Post;
use Illuminate\Http\Request;
use Inertia\Inertia;

class HomeController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(Request $request)
    {
        $featuredPost = Post::where('is_featured', true)->first() ?? [];

        if (collect($featuredPost)->isNotEmpty())
            $featuredPost = new PostResource($featuredPost);

        $latestPosts = new PostCollection(Post::with('category', 'author')->where('status', PostStatus::Published->value)->latest('id')->take(6)->get());

        $categories = new CategoryCollection(Category::with('creator')->where('status', CategoryStatus::Active->value)->latest('id')->take(3)->get());

        return Inertia::render('App/Home', compact('featuredPost', 'latestPosts', 'categories'));
    }
}