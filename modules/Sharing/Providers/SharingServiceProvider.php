<?php

namespace Modules\Sharing\Providers;

use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;
use Modules\Sharing\Models\Shared;
use Modules\Sharing\Policies\SharePolicy;
use Modules\Sharing\Repositories\Interfaces\ShareRepositoryInterface;
use Modules\Sharing\Repositories\ShareRepository;

class SharingServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->bind(ShareRepositoryInterface::class, ShareRepository::class);
    }

    public function boot(): void
    {
        Route::middleware('web')->group(__DIR__.'/../Routes/web.php');
        $this->loadViewsFrom(__DIR__.'/../Resources/views', 'sharing');
        $this->loadMigrationsFrom(__DIR__.'/../Database/Migrations');
        $this->loadTranslationsFrom(__DIR__.'/../lang', 'sharing');

        Gate::policy(Shared::class, SharePolicy::class);
    }
}
