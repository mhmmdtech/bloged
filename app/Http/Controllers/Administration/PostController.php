<?php

namespace App\Http\Controllers\Administration;

use App\Enums\CategoryStatus;
use App\Enums\PostStatus;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StorePostRequest;
use App\Http\Requests\Admin\UpdatePostRequest;
use App\Http\Resources\PostCollection;
use App\Http\Resources\PostResource;
use App\Models\Category;
use App\Models\Post;
use Illuminate\Support\Facades\Storage;
use Inertia\Inertia;

class PostController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $posts = new PostCollection(Post::with('author', 'category')->latest()->paginate(5));

        return Inertia::render('Admin/Posts/Index', compact('posts'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $statuses = PostStatus::array();

        $activeCategories = Category::where('status', CategoryStatus::Active->value)->get(['id', 'title']);

        return Inertia::render('Admin/Posts/Create', compact('statuses', 'activeCategories'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StorePostRequest $request)
    {
        $inputs = $request->validated();

        $inputs['thumbnail'] = Storage::disk('public')->putFile('posts', $request->file('thumbnail'));

        auth()->user()->posts()->create($inputs);

        return redirect()->route('administration.posts.index');
    }

    /**
     * Display the specified resource.
     */
    public function show(Post $post)
    {
        $post->load('author', 'category');

        $post = new PostResource($post);

        return Inertia::render('Admin/Posts/Show', compact('post'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Post $post)
    {
        $post->load('category');

        $statuses = PostStatus::array();

        $post = new PostResource($post);

        $activeCategories = Category::where('status', CategoryStatus::Active->value)->get();

        return Inertia::render('Admin/Posts/Edit', compact('post', 'statuses', 'activeCategories'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdatePostRequest $request, Post $post)
    {
        $inputs = removeNullFromArray($request->validated());

        $post->update($inputs);

        return redirect()->route('administration.posts.index');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Post $post)
    {
        $post->delete();

        return redirect()->route('administration.posts.index');
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