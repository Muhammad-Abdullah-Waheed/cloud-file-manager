<?php

namespace Modules\Billing\Providers;

use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;
use Modules\Billing\Repositories\Interfaces\UpgradeRequestRepositoryInterface;
use Modules\Billing\Repositories\UpgradeRequestRepository;

class BillingServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->bind(UpgradeRequestRepositoryInterface::class, UpgradeRequestRepository::class);
    }

    public function boot(): void
    {
        Route::middleware('web')->group(__DIR__.'/../Routes/web.php');
        $this->loadViewsFrom(__DIR__.'/../Resources/views', 'billing');
        $this->loadMigrationsFrom(__DIR__.'/../Database/Migrations');
        $this->loadTranslationsFrom(__DIR__.'/../lang', 'billing');
    }
}
