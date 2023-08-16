<?php

namespace App\Http\Controllers\Application;

use App\Enums\PostStatus;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Resources\PostCollection;
use App\Models\Post;
use Inertia\Inertia;

class SearchController extends Controller
{
    protected int $applicationPaginatedItemsCount = 5;
    /**
     * Handle the incoming request.
     */
    public function __invoke(Request $request)
    {
        $query = trim(request()->query('query', ""));
        if ($query === "" || $query === NULL) {
            return Inertia::render('App/Search');
        }
        if (strlen($query) < 5)
            return;
        $posts = Post::with('author', 'category')->whereRaw("MATCH(title, seo_title, description, seo_description, body) AGAINST(? IN NATURAL LANGUAGE MODE WITH QUERY EXPANSION)", [$query])->where('status', PostStatus::Published)->paginate($this->applicationPaginatedItemsCount);
        $posts = new PostCollection($posts);
        return Inertia::render('App/Search', compact('posts', 'query'));
    }
}