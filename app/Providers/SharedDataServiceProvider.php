<?php

namespace App\Providers;

use App\Models\Category;
use App\Models\Post;
use App\Services\Frontend\Posts\Cache\PostCachingService;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Schema;

class SharedDataServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        
    }

    /**
     * Bootstrap services.
     */
    

    public function boot(): void
    {
        // جلوگیری از خطا قبل از اجرای migration
        if (!Schema::hasTable('categories') || !Schema::hasTable('posts')) {
            return;
        }

        try {
            $categories = Category::active()
                ->hasInPostsRelation()
                ->basicSelect()
                ->withPostsAndCount()
                ->get();

            $newCatgories = $categories->take(9);

            $allCategories = Category::active()
                ->basicSelect()
                ->get();

        } catch (\Exception $e) {
            // fallback
            $categories = collect();
            $newCatgories = collect();
            $allCategories = collect();
        }

        View::share([
            'categories' => $categories,
            'newCatgories' => $newCatgories,
            'allCategories' => $allCategories,
        ]);
    }
}
