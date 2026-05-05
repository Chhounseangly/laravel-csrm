<?php

namespace Seangly\LaravelCsrm\Commands;

use Illuminate\Console\Command;
use Seangly\LaravelCsrm\Generators\BaseGenerator;
use Seangly\LaravelCsrm\Generators\ControllerGenerator;
use Seangly\LaravelCsrm\Generators\MigrationGenerator;
use Seangly\LaravelCsrm\Generators\ModelGenerator;
use Seangly\LaravelCsrm\Generators\RepositoryGenerator;
use Seangly\LaravelCsrm\Generators\ServiceGenerator;
use Seangly\LaravelCsrm\Support\FileEditor;

class InstallCommand extends Command
{
    protected $signature = 'csrm:install
                            {--force : Overwrite existing files}';

    protected $description = 'Install the base CSRM classes (Controller, Service, Repository)';

    public function handle(): int
    {
        $this->info('');
        $this->info('  ██████╗███████╗██████╗ ███╗   ███╗');
        $this->info('  ██╔════╝██╔════╝██╔══██╗████╗ ████║');
        $this->info('  ██║     ███████╗██████╔╝██╔████╔██║');
        $this->info('  ██║     ╚════██║██╔══██╗██║╚██╔╝██║');
        $this->info('  ╚██████╗███████║██║  ██║██║ ╚═╝ ██║');
        $this->info('   ╚═════╝╚══════╝╚═╝  ╚═╝╚═╝     ╚═╝');
        $this->info('');
        $this->info('  Controller → Service → Repository → Model → Migration');
        $this->info('');

        $force = $this->option('force');

        $steps = [
            'Base classes' => fn () => (new BaseGenerator($this, $force))->generate(),
        ];

        foreach ($steps as $label => $step) {
            try {
                $step();
            } catch (\Exception $e) {
                $this->error("  Error generating {$label}: ".$e->getMessage());

                return self::FAILURE;
            }
        }

        $this->info('');
        $this->info('  ✓ Base CSRM classes installed successfully!');
        $this->info('');
        $this->info('  Get Started:');
        $this->line('    Run: php artisan csrm:make {Name} to create a new module');
        $this->info('');

        return self::SUCCESS;
    }

    protected function bindInterface(string $name): void
    {
        $providerPath = app_path('Providers/AppServiceProvider.php');

        if (! file_exists($providerPath)) {
            $this->warn('  AppServiceProvider not found — skipping auto-bind.');

            return;
        }

        if (FileEditor::ensureRepositoryBinding($providerPath, $name)) {
            return;
        }

        $this->line('  <fg=yellow>SKIP</> Interface already bound in AppServiceProvider');
    }
}
