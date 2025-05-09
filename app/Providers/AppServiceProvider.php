<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Storage;
use App\Http\Controllers\GeminiChatController;

class AppServiceProvider extends ServiceProvider
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
        // Ensure the directory exists in storage/app/public
        if (!Storage::exists('views/images')) {
            Storage::makeDirectory('views/images');
        }

        view()->composer('layouts.header', function ($view) {
            $view->with('recentChats', GeminiChatController::getRecentChats());
        });
    }
}
