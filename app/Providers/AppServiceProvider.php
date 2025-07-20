<?php

namespace App\Providers;

use App\Http\View\Composers\HeaderComposer;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;
use App\Models\Page;

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
        Paginator::useBootstrap();
        View::composer('layouts.front_office.header', HeaderComposer::class);
        // مشاركة متغير $pages مع جميع الـ views
        View::composer('*', function ($view) {
            $view->with('pages', Page::paginate(5));
        });
    }
}
