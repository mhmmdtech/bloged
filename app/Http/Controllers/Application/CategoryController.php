<?php

namespace App\Http\Controllers\Application;

use App\Enums\CategoryStatus;
use App\Enums\PostStatus;
use App\Http\Controllers\Controller;
use App\Http\Resources\CategoryCollection;
use App\Http\Resources\CategoryResource;
use App\Http\Resources\PostCollection;
use App\Models\Category;
use Inertia\Inertia;

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {

        $categories = new CategoryCollection(Category::with('creator')->where('status', CategoryStatus::Active->value)->latest()->get());

        return Inertia::render('App/Categories/Index', compact('categories'));
    }

    /**
     * Display the specified resource.
     */
    public function show(Category $category)
    {
        $category->load('posts');

        $category = new CategoryResource($category);

        $posts = new PostCollection($category->posts()->where('status', PostStatus::Published)->with('author')->latest()->paginate(10));

        return Inertia::render('App/Categories/Single', compact('category', 'posts'));
    }
}