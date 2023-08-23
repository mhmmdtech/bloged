<?php

namespace App\Http\Controllers\Application;

use App\Enums\PostStatus;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Resources\PostCollection;
use App\Repositories\PostRepositoryInterface;
use Inertia\Inertia;

class SearchController extends Controller
{

    public function __construct(
        private PostRepositoryInterface $postRepository
    ) {
    }
    protected int $applicationPaginatedItemsCount = 5;
    /**
     * Handle the incoming request.
     */
    public function __invoke(Request $request)
    {
        $query = trim(request()->query('query', ""));

        if ($query === "" || $query === NULL)
            return Inertia::render('App/Search');


        if (strlen($query) < 5)
            return redirect()->back();

        $posts = $this->postRepository->searchPostsPaginated($query, $this->applicationPaginatedItemsCount);
        $posts = new PostCollection($posts);
        return Inertia::render('App/Search', compact('posts', 'query'));
    }
}