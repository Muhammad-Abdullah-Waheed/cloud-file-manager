<?php

namespace Modules\Drive\Providers;

use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Schedule;
use Illuminate\Support\ServiceProvider;
use Modules\Drive\Console\Commands\PurgeTrashedItems;
use Modules\Drive\Models\File;
use Modules\Drive\Models\Folder;
use Modules\Drive\Policies\FilePolicy;
use Modules\Drive\Policies\FolderPolicy;
use Modules\Drive\Repositories\FileRepository;
use Modules\Drive\Repositories\FileVersionRepository;
use Modules\Drive\Repositories\FolderRepository;
use Modules\Drive\Repositories\Interfaces\FileRepositoryInterface;
use Modules\Drive\Repositories\Interfaces\FileVersionRepositoryInterface;
use Modules\Drive\Repositories\Interfaces\FolderRepositoryInterface;

class DriveServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->bind(FolderRepositoryInterface::class, FolderRepository::class);
        $this->app->bind(FileRepositoryInterface::class, FileRepository::class);
        $this->app->bind(FileVersionRepositoryInterface::class, FileVersionRepository::class);
    }

    public function boot(): void
    {
        Route::middleware('web')->group(__DIR__.'/../Routes/web.php');
        $this->loadViewsFrom(__DIR__.'/../Resources/views', 'drive');
        $this->loadMigrationsFrom(__DIR__.'/../Database/Migrations');
        $this->loadTranslationsFrom(__DIR__.'/../lang', 'drive');

        Gate::policy(Folder::class, FolderPolicy::class);
        Gate::policy(File::class, FilePolicy::class);

        Route::bind('file', fn ($value) => File::withTrashed()->findOrFail($value));
        Route::bind('folder', fn ($value) => Folder::withTrashed()->findOrFail($value));

        Relation::morphMap([
            'folder' => Folder::class,
            'file'   => File::class,
        ], merge: true);

        $this->app['router']->aliasMiddleware(
            'drive.access',
            \Modules\Drive\Http\Middleware\EnsureDriveAccess::class
        );

        if ($this->app->runningInConsole()) {
            $this->commands([
                PurgeTrashedItems::class,
            ]);
        }

        Schedule::command('trash:purge')->daily();
    }
}
