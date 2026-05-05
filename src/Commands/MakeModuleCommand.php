<?php

namespace Seangly\LaravelCsrm\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Str;
use Seangly\LaravelCsrm\Generators\ControllerGenerator;
use Seangly\LaravelCsrm\Generators\MigrationGenerator;
use Seangly\LaravelCsrm\Generators\ModelGenerator;
use Seangly\LaravelCsrm\Generators\RepositoryGenerator;
use Seangly\LaravelCsrm\Generators\ServiceGenerator;
use Seangly\LaravelCsrm\Support\FileEditor;

class MakeModuleCommand extends Command
{
    protected $signature = 'csrm:make
                            {name : The module name e.g. Post, Product, Order}
                            {--only= : Comma-separated list: model,migration,repository,service,controller}
                            {--api : Generate API controller}
                            {--web : Generate web controller (default)}
                            {--force : Overwrite existing files}';

    protected $description = 'Generate a new CSRM module (Controller, Service, Repository, Model, Migration)';

    protected array $allTypes = [
        'model', 'migration', 'repository', 'service', 'controller',
    ];

    public function handle(): int
    {
        $name = Str::studly($this->argument('name'));
        $force = $this->option('force');

        if ($this->option('api') && $this->option('web')) {
            $this->error('  Please use only one controller mode: --api or --web.');

            return self::FAILURE;
        }

        $isApiController = $this->option('api');
        $only = $this->option('only')
            ? array_map('trim', explode(',', $this->option('only')))
            : $this->allTypes;

        $this->info('');
        $this->info("  Generating CSRM module: <fg=cyan>{$name}</>");
        $this->info('');

        $generators = [
            'model' => fn () => (new ModelGenerator($this, $force))->generate($name),
            'migration' => fn () => (new MigrationGenerator($this, $force))->generate($name),
            'repository' => fn () => (new RepositoryGenerator($this, $force))->generate($name),
            'service' => fn () => (new ServiceGenerator($this, $force))->generate($name),
            'controller' => fn () => (new ControllerGenerator($this, $force, $isApiController))->generate($name),
        ];

        foreach ($only as $type) {
            if (! isset($generators[$type])) {
                $this->warn("  Unknown type [{$type}] — skipping.");

                continue;
            }
            try {
                $generators[$type]();
            } catch (\Exception $e) {
                $this->error("  Error generating {$type}: ".$e->getMessage());

                return self::FAILURE;
            }
        }

        // Auto-bind repository interface
        $this->bindInterface($name);

        $this->info('');
        $this->info("  ✓ Module [{$name}] created!");
        $this->line('    → Run: php artisan migrate');
        $this->info('');

        return self::SUCCESS;
    }

    protected function bindInterface(string $name): void
    {
        $providerPath = app_path('Providers/AppServiceProvider.php');

        if (! file_exists($providerPath)) {
            return;
        }

        if (FileEditor::ensureRepositoryBinding($providerPath, $name)) {
            $this->line("  <fg=green>BOUND</> {$name}RepositoryInterface in AppServiceProvider");
        }
    }
}
