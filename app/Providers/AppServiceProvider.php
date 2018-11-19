<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\DB;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        //log error levels - production

        error_reporting(env('PHP_SETTINGS_ERROR_REPORTING', 0));
        ini_set('log_errors', env('PHP_SETTINGS_LOG_ERRORS', 0));
        ini_set('display_errors', env('PHP_SETTINGS_DISPLAY_ERRORS', 0));

    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}
