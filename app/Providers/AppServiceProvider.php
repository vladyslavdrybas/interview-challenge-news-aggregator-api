<?php

namespace App\Providers;

use App\Models\NewsAuthor;
use App\Models\NewsCategory;
use App\Models\NewsSource;
use App\Models\UserPreference;
use App\Observers\NewsAuthorObserver;
use App\Observers\NewsCategoryObserver;
use App\Observers\NewsSourceObserver;
use App\Observers\UserPreferenceObserver;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        if ($this->app->environment('local')) {
            $this->app->register(\Laravel\Telescope\TelescopeServiceProvider::class);
            $this->app->register(TelescopeServiceProvider::class);
        }
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        NewsSource::observe(NewsSourceObserver::class);
        NewsCategory::observe(NewsCategoryObserver::class);
        NewsAuthor::observe(NewsAuthorObserver::class);
        UserPreference::observe(UserPreferenceObserver::class);
    }
}
