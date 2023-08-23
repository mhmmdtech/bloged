<?php

namespace App\Http\Controllers\Application;

use App\Enums\PostStatus;
use App\Http\Controllers\Controller;
use App\Http\Resources\PostCollection;
use App\Http\Resources\PostResource;
use App\Models\Post;
use App\Repositories\PostRepositoryInterface;
use Inertia\Inertia;

class PostController extends Controller
{

    public function __construct(
        private PostRepositoryInterface $postRepository
    ) {
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $posts = new PostCollection($this->postRepository->getPublishedPostsPaginated($this->normalOrderedColumn, $this->applicationPaginatedItemsCount));

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
}