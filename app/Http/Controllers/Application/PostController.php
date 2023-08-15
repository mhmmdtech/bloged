<?php

namespace App\Http\Controllers\Application;

use App\Enums\PostStatus;
use App\Http\Controllers\Controller;
use App\Http\Resources\PostCollection;
use App\Http\Resources\PostResource;
use App\Models\Post;
use Inertia\Inertia;

class PostController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $posts = new PostCollection(Post::with('category', 'author')->where('status', PostStatus::Published->value)->latest()->paginate(10));

        return Inertia::render('App/Posts/Index', compact('posts'));
    }

    /**
     * Display the specified resource.
     */
    public function show(Post $post)
    {
        $post->load('author', 'category');

        $post = new PostResource($post);

        return Inertia::render('App/Posts/Show', compact('post'));
    }

    /**
     * Search about specified resource.
     */
    public function search()
    {
        $query = trim(request()->query('query', ""));
        $posts = Post::with('author', 'category')->whereRaw("MATCH(title, seo_title, description, seo_description, body) AGAINST(? IN NATURAL LANGUAGE MODE WITH QUERY EXPANSION)", [$query])->paginate(5);
        $posts = new PostCollection($posts);
        return Inertia::render('App/Search', compact('posts', 'query'));
    }
}