<?php

namespace App\Console\Commands;

use App\Console\ModuleGenerator;
use Illuminate\Console\Command;

class MakeModuleCommand extends Command
{
    protected $signature = 'make:module
                            {name : The module name, e.g. Drive}
                            {--force : Overwrite if module already exists}';

    protected $description = 'Create a new application module with full directory structure';

    public function handle(ModuleGenerator $generator): int
    {
        $name = (string) $this->argument('name');

        if (! preg_match('/^[A-Z][A-Za-z0-9]*$/', $name)) {
            $this->error('Module name must be StudlyCase, e.g. Drive, Admin, Auth');

            return self::FAILURE;
        }

        if (is_dir(base_path("modules/{$name}")) && ! $this->option('force')) {
            $this->error("Module [{$name}] already exists. Use --force to overwrite.");

            return self::FAILURE;
        }

        $generator->generate($name);

        $this->info("Module [{$name}] created successfully.");
        $this->newLine();
        $this->line('Next steps:');
        $this->line('  composer dump-autoload');
        $this->line('  php artisan migrate');

        return self::SUCCESS;
    }
}
