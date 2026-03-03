<?php

namespace App\Providers;

use App\Models\Category;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class ViewComposerServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void {}

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        // Cách 1: Chia sẻ cho tất cả views (đơn giản nhất)
        // View::composer('*', function ($view) {
        //     $categories = Category::where('is_active', true)
        //         ->orderBy('category_name')
        //         ->get();

        //     $view->with('categories', $categories);
        // });

        // Cách 2: Chia sẻ chỉ cho layout cụ thể và các view extends từ nó
        if (class_exists(Category::class)) {
            View::composer(['frontend.layouts.app', 'frontend.*'], function ($view) {
                $categories = Category::active()
                    // ->orderBy('category_name')
                    ->get();

                $view->with('categories', $categories);
            });
        }
    }
}
