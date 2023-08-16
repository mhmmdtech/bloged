<?php

namespace App\Providers;

use Illuminate\Support\Carbon;
use Illuminate\Support\ServiceProvider;

class CarbonServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Carbon::macro('jdate', function ($format, $tr_num = 'fa') {
            return jdate($format, self::this()->timestamp, '', '', $tr_num);
        });

        Carbon::macro('jmktime', function ($year, $month, $day, $hour = 0, $minute = 0, $second = 0) {
            $timestamp = jmktime($hour, $minute, $second, $month, $day, $year);
            return self::createFromTimestamp($timestamp);
        });
    }
}