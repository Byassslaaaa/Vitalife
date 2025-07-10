<?php

namespace App\Providers;

use Illuminate\Support\Facades\Blade;
use App\View\Components\SelectInput;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Route;
use App\Http\Middleware\AdminMiddleware;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        // Register admin middleware
        Route::aliasMiddleware('admin', AdminMiddleware::class);

        // Register Blade components
        Blade::component('select-input', SelectInput::class);
    }
}