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
use Inertia\Inertia;
use App\Services\FileManager\FileManager;

class PostController extends Controller
{
    public function __construct(private FileManager $fileManagerService)
    {
        //
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $this->authorize('browse post', Post::class);

        $posts = new PostCollection(Post::with('author', 'category')->latest($this->normalOrderedColumn)->paginate($this->administrationPaginatedItemsCount));

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

        $inputs['thumbnail'] = $this->fileManagerService
            ->uploadMultiQualityImage(
                $inputs['thumbnail'],
                'posts' . DIRECTORY_SEPARATOR . 'thumbnails',
                $inputs['seo_title']
            );

        auth()->user()->posts()->create($inputs);

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

        if (isset($inputs['thumbnail'])) {
            $this->fileManagerService->deleteMultiQualityImage($post->thumbnail);
            $inputs['thumbnail'] = $this->fileManagerService
                ->uploadMultiQualityImage(
                    $inputs['thumbnail'],
                    'posts' . DIRECTORY_SEPARATOR . 'thumbnails',
                    $inputs['seo_title']
                );
        }

        $post->update($inputs);

        return redirect()->route('administration.posts.index');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Post $post)
    {
        $this->authorize('delete post', $post);

        $post->delete();

        return redirect()->route('administration.posts.index');
    }

    /**
     * Display a listing of the soft deleted resource.
     */
    public function trashed()
    {
        $this->authorize('delete post', Post::class);

        $posts = new PostCollection(post::onlyTrashed()->latest($this->trashedOrderedColumn)->paginate($this->administrationPaginatedItemsCount));

        return Inertia::render('Admin/Posts/Trashed', compact('posts'));
    }

    /**
     * force delete the specified resource from storage.
     */
    public function forceDelete(?Post $post = null)
    {
        $this->authorize('delete post', Post::class);

        if (is_null($post)) {
            $trashedPosts = Post::onlyTrashed()->get(['id', 'thumbnail']);
            $trashedPosts->each(function (Post $post) {
                $this->fileManagerService->deleteMultiQualityImage($post->thumbnail);

            });
            Post::whereIn('id', array_flatten($trashedPosts->toArray()))->forceDelete();
            return redirect()->route('administration.posts.trashed');
        }

        $this->fileManagerService->deleteMultiQualityImage($post->thumbnail);
        $post->forceDelete();
        return redirect()->route('administration.posts.trashed');
    }

    /**
     * restore the specified resource from storage.
     */
    public function restore(Post $post)
    {
        $this->authorize('delete post', Post::class);
        $post->restore();
        return redirect()->route('administration.posts.trashed');
    }

    /**
     * toggle the is_featured functionality
     */
    public function toggleFeatured(Post $post)
    {
        $this->authorize('edit post', $post);

        if ($post->is_featured) {
            $post->update(['is_featured' => false]);
            return;
        }

        // Disable the previously featured post
        Post::where('is_featured', true)->update(['is_featured' => false]);

        $post->update(['is_featured' => true]);
    }
}