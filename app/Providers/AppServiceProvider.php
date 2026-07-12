<?php

namespace App\Providers;

use Carbon\CarbonImmutable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Date;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\ServiceProvider;
use Illuminate\Validation\Rules\Password;
use App\Repositories\Interfaces\UserRepositoryInterface;
use App\Repositories\UserRepository;
use App\Repositories\Interfaces\RoleRepositoryInterface;
use App\Repositories\RoleRepository;
use App\Repositories\Interfaces\FolderRepositoryInterface;
use App\Repositories\FolderRepository;
use App\Models\Folder;
use App\Policies\FolderPolicy;
use Illuminate\Support\Facades\Gate;
use App\Repositories\Interfaces\FileRepositoryInterface;
use App\Repositories\FileRepository;
use App\Repositories\Interfaces\FileVersionRepositoryInterface;
use App\Repositories\FileVersionRepository;
use App\Models\File;
use App\Policies\FilePolicy;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->bind(UserRepositoryInterface::class, UserRepository::class);
        $this->app->bind(RoleRepositoryInterface::class, RoleRepository::class);
        $this->app->bind(FolderRepositoryInterface::class, FolderRepository::class);
        $this->app->bind(FileRepositoryInterface::class, FileRepository::class);
        $this->app->bind(FileVersionRepositoryInterface::class, FileVersionRepository::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Gate::policy(Folder::class, FolderPolicy::class);
        Gate::policy(File::class, FilePolicy::class);


        $this->configureDefaults();
        Model::unguard();
        Model::shouldBeStrict();
    }

    /**
     * Configure default behaviors for production-ready applications.
     */
    protected function configureDefaults(): void
    {
        Date::use(CarbonImmutable::class);

        DB::prohibitDestructiveCommands(
            app()->isProduction(),
        );

        Password::defaults(fn (): ?Password => app()->isProduction()
            ? Password::min(12)
                ->mixedCase()
                ->letters()
                ->numbers()
                ->symbols()
                ->uncompromised()
            : null,
        );
    }
}
