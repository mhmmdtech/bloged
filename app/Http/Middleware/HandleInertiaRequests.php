<?php

namespace App\Http\Middleware;

use App\Http\Resources\UserResource;
use App\Models;
use Illuminate\Http\Request;
use Inertia\Middleware;
use Tightenco\Ziggy\Ziggy;

class HandleInertiaRequests extends Middleware
{
    /**
     * The root template that is loaded on the first page visit.
     *
     * @var string
     */
    protected $rootView = 'app';

    /**
     * Determine the current asset version.
     */
    public function version(Request $request): string|null
    {
        return parent::version($request);
    }

    /**
     * Define the props that are shared by default.
     *
     * @return array<string, mixed>
     */
    public function share(Request $request): array
    {
        return array_merge(parent::share($request), [
            'auth' => [
                'user' => $request->user() === null ? $request->user() : new UserResource($request->user()),
                'can' => [
                    'browse_category' => $request->user() && $request->user()->can('browse category', Models\Category::class),
                    'delete_category' => $request->user() && $request->user()->can('delete category', Models\Category::class),
                    'browse_post' => $request->user() && $request->user()->can('browse post', Models\Post::class),
                    'delete_post' => $request->user() && $request->user()->can('delete post', Models\Post::class),
                    'browse_user' => $request->user() && $request->user()->can('browse user', Models\User::class),
                    'browse_log' => $request->user() && $request->user()->can('browse log', Models\Log::class),
                    'browse_province' => $request->user() && $request->user()->can('browse province', Models\Province::class),
                    'delete_province' => $request->user() && $request->user()->can('delete province', Models\Province::class),
                    'browse_city' => $request->user() && $request->user()->can('browse city', Models\City::class),
                    'browse_analytic' => $request->user() && $request->user()->can('browse analytic', Models\User::class),
                ],
            ],
            'ziggy' => function () use ($request) {
                return array_merge((new Ziggy)->toArray(), [
                    'location' => $request->url(),
                ]);
            },
        ]);
    }
}