<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Services\NewsService;
use App\Services\NewsServiceInterface;

class NewsServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        $this->app->bind(NewsServiceInterface::class, NewsService::class);
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        
    }
}
