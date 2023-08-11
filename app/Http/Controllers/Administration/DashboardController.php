<?php

namespace App\Http\Controllers\Administration;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Post;
use App\Models\User;
use Illuminate\Http\Request;
use Inertia\Inertia;

class DashboardController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(Request $request)
    {
        return Inertia::render('Admin/Dashboard', [
            'can' => [
                'browse_category' => $request->user()->can('create', Category::class),
                'browse_post' => $request->user()->can('create', Post::class),
                'browse_user' => $request->user()->can('create', User::class),
            ],
        ]);
    }
}