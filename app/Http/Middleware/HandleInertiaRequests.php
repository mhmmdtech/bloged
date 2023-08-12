<?php

namespace App\Http\Middleware;

use App\Models\Category;
use App\Models\Post;
use App\Models\User;
use App\Http\Resources\UserResource;
use App\Models\City;
use App\Models\Log;
use App\Models\Province;
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
                    'browse_category' => $request->user() && $request->user()->can('browse category', Category::class),
                    'browse_post' => $request->user() && $request->user()->can('browse post', Post::class),
                    'browse_user' => $request->user() && $request->user()->can('browse user', User::class),
                    'browse_log' => $request->user() && $request->user()->can('browse log', Log::class),
                    'browse_province' => $request->user() && $request->user()->can('browse province', Province::class),
                    'browse_city' => $request->user() && $request->user()->can('browse city', City::class),
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