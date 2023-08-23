<?php

namespace App\Providers;

use App\Repositories;
use Illuminate\Support\ServiceProvider;
use Illuminate\Validation\Rules\Password;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->bind(Repositories\CategoryRepositoryInterface::class, Repositories\CategoryRepository::class);
        $this->app->bind(Repositories\PostRepositoryInterface::class, Repositories\PostRepository::class);
        $this->app->bind(Repositories\ProvinceRepositoryInterface::class, Repositories\ProvinceRepository::class);
        $this->app->bind(Repositories\CityRepositoryInterface::class, Repositories\CityRepository::class);
        $this->app->bind(Repositories\UserStatisticsRepositoryInterface::class, Repositories\UserStatisticsRepository::class);
        $this->app->bind(Repositories\LogRepositoryInterface::class, Repositories\LogRepository::class);
        $this->app->bind(Repositories\UserRepositoryInterface::class, Repositories\UserRepository::class);
        $this->app->bind(Repositories\PermissionRepositoryInterface::class, Repositories\PermissionRepository::class);
        $this->app->bind(Repositories\RoleRepositoryInterface::class, Repositories\RoleRepository::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Password::defaults(function () {
            $rule = Password::min(8);
            return $this->app->isProduction()
                ? $rule->letters()
                    ->mixedCase()
                    ->numbers()
                    ->symbols()
                    ->uncompromised(3)
                : $rule;
        });
    }
}