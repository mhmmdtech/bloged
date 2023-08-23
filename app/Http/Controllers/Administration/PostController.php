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
use App\Repositories\PostRepository;
use Inertia\Inertia;

class PostController extends Controller
{
    public function __construct(
        private PostRepository $postRepository
    ) {
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $this->authorize('browse post', Post::class);

        $posts = new PostCollection(
            $this->postRepository->getPaginatedPosts(
                $this->administrationPaginatedItemsCount,
                $this->normalOrderedColumn,
            )
        );

        return Inertia::render('Admin/Posts/Index', compact('posts'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $this->authorize('add post', Post::class);

        $statuses = PostStatus::array();

        $activeCategories = Category::where('status', CategoryStatus::Active->value)->get(['id', 'title']);

        return Inertia::render('Admin/Posts/Create', compact('statuses', 'activeCategories'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StorePostRequest $request)
    {
        $this->authorize('add post', Post::class);

        $inputs = $request->validated();

        $this->postRepository->create($inputs);

        return redirect()->route('administration.posts.index');
    }

    /**
     * Display the specified resource.
     */
    public function show(Post $post)
    {
        $this->authorize('read post', $post);

        $post->load('author', 'category');

        $post = new PostResource($post);

        return Inertia::render('Admin/Posts/Show', compact('post'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Post $post)
    {
        $this->authorize('edit post', $post);

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
        $this->authorize('edit post', $post);

        $inputs = removeNullFromArray($request->validated());

        $this->postRepository->update($post, $inputs);

        return redirect()->route('administration.posts.index');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Post $post)
    {
        $this->authorize('delete post', $post);

        $this->postRepository->delete($post);

        return redirect()->route('administration.posts.index');
    }

    /**
     * Display a listing of the soft deleted resource.
     */
    public function trashed()
    {
        $this->authorize('delete post', Post::class);

        $posts = new PostCollection(
            $this->postRepository->getTrashedPaginatedPosts(
                $this->administrationPaginatedItemsCount,
                $this->trashedOrderedColumn,
            )
        );

        return Inertia::render('Admin/Posts/Trashed', compact('posts'));
    }

    /**
     * force delete the specified resource from storage.
     */
    public function forceDelete(?Post $post = null)
    {
        $this->authorize('delete post', Post::class);

        if (is_null($post)) {
            $this->postRepository->forceDeleteAll();
            return redirect()->route('administration.posts.trashed');
        }

        $this->postRepository->forceDelete($post);
        return redirect()->route('administration.posts.trashed');
    }

    /**
     * restore the specified resource from storage.
     */
    public function restore(Post $post)
    {
        $this->authorize('delete post', Post::class);

        $this->postRepository->restore($post);

        return redirect()->route('administration.posts.trashed');
    }

    /**
     * toggle the is_featured functionality
     */
    public function toggleFeatured(Post $post)
    {
        $this->authorize('edit post', $post);

        $this->postRepository->toggleFeatured($post);
    }
}