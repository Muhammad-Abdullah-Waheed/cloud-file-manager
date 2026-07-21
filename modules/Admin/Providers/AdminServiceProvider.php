<?php

namespace Modules\Admin\Providers;

use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;
use Modules\Admin\Repositories\DeleteRequestRepository;
use Modules\Admin\Repositories\Interfaces\DeleteRequestRepositoryInterface;

class AdminServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->bind(DeleteRequestRepositoryInterface::class, DeleteRequestRepository::class);
    }

    public function boot(): void
    {
        Route::middleware('web')->group(__DIR__.'/../Routes/web.php');
        $this->loadViewsFrom(__DIR__.'/../Resources/views', 'admin');
        $this->loadMigrationsFrom(__DIR__.'/../Database/Migrations');
        $this->loadTranslationsFrom(__DIR__.'/../lang', 'admin');
    }
}
