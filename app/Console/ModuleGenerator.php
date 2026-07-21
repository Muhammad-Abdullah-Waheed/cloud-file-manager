<?php

namespace App\Console;

use Illuminate\Filesystem\Filesystem;
use Illuminate\Support\Str;

class ModuleGenerator
{
    public function __construct(private Filesystem $files) {}

    public function generate(string $name): void
    {
        $studly = Str::studly($name);
        $lower = Str::lower($studly);
        $base = base_path("modules/{$studly}");

        $replacements = [
            '{{ module }}'          => $studly,
            '{{ moduleLower }}'     => $lower,
            '{{ moduleNamespace }}' => "Modules\\{$studly}",
        ];

        $directories = [
            'Console/Commands',
            'Database/Migrations',
            'Database/Seeders',
            'Exceptions',
            'Features',
            'Http/Controllers',
            'Http/Middleware',
            'Http/Requests',
            'Models',
            'Policies',
            'Providers',
            'Repositories/Interfaces',
            'Resources/views',
            'Routes',
            'lang/en',
            'lang/ar',
        ];

        foreach ($directories as $dir) {
            $this->files->makeDirectory("{$base}/{$dir}", 0755, true);
        }

        $stubMap = [
            'module.json'             => 'module.json',
            'ServiceProvider.php'     => "Providers/{$studly}ServiceProvider.php",
            'routes-web.php'          => 'Routes/web.php',
            'Controller.php'          => "Http/Controllers/{$studly}Controller.php",
            'Middleware.php'          => "Http/Middleware/Ensure{$studly}Access.php",
            'Model.php'               => "Models/{$studly}.php",
            'RepositoryInterface.php' => "Repositories/Interfaces/{$studly}RepositoryInterface.php",
            'Repository.php'          => "Repositories/{$studly}Repository.php",
            'Policy.php'              => "Policies/{$studly}Policy.php",
            'Feature.php'             => "Features/Example{$studly}Feature.php",
            'Request.php'             => "Http/Requests/Store{$studly}Request.php",
            'Migration.php'           => "Database/Migrations/2026_01_01_000000_create_{$lower}_table.php",
            'Seeder.php'              => "Database/Seeders/{$studly}Seeder.php",
            'view-index.blade.php'    => 'Resources/views/index.blade.php',
            'lang-en.php'             => 'lang/en/messages.php',
            'lang-ar.php'             => 'lang/ar/messages.php',
        ];

        foreach ($stubMap as $stub => $destination) {
            $this->copyStub($stub, "{$base}/{$destination}", $replacements);
        }

        $this->registerProvider($studly);
    }

    /**
     * @param  array<string, string>  $replacements
     */
    private function copyStub(string $stub, string $destination, array $replacements): void
    {
        $content = file_get_contents(base_path("stubs/module/{$stub}.stub"));
        $content = str_replace(array_keys($replacements), array_values($replacements), $content);
        $this->files->put($destination, $content);
    }

    private function registerProvider(string $studly): void
    {
        $providersFile = base_path('bootstrap/providers.php');
        $providerClass = "Modules\\{$studly}\\Providers\\{$studly}ServiceProvider::class";
        $contents = file_get_contents($providersFile);

        if (str_contains($contents, $providerClass)) {
            return;
        }

        $contents = preg_replace(
            '/\n\];/',
            "\n    {$providerClass},\n];",
            $contents,
            1
        );

        $this->files->put($providersFile, $contents);
    }
}
