<?php

namespace App\Providers;

use App\Services\Frontend\Posts\Cache\PostCachingService;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Schema;

class CacheServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        $this->app->singleton(PostCachingService::class, function () {
            return new PostCachingService();
        });
    }

    /**
     * Bootstrap services.
     */
    

    public function boot(): void
    {
        // جلوگیری از اجرای کد قبل از آماده بودن دیتابیس
        if (!Schema::hasTable('posts')) {
            return;
        }

        try {
            $postCachingService = app(PostCachingService::class);

            $latestCachedPosts = $postCachingService->cache_latest_posts();
            $cachedPosts = $postCachingService->cache_read_more_posts();
            $cached_popular_posts = $postCachingService->cache_popular_posts();

        } catch (\Exception $e) {
            // fallback اگر کش یا DB مشکل داشت
            $latestCachedPosts = collect();
            $cachedPosts = collect();
            $cached_popular_posts = collect();
        }

        View::share([
            'latestCachedPosts' => $latestCachedPosts,
            'cachedPosts' => $cachedPosts,
            'cachedPopularPosts' => $cached_popular_posts,
        ]);
    }
}
