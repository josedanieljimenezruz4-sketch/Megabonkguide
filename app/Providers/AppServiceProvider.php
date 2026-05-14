<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;
use Illuminate\Pagination\Paginator;
use App\Models\Suggestion;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        View::composer('components.admin.sidebar', function ($view) {
            $view->with('unreadSuggestionsCount', Suggestion::where('is_read', false)->count());
        });

        Paginator::defaultView('partials.pagination-neon');
    }
}
