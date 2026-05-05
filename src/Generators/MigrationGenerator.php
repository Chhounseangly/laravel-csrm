<?php

namespace Seangly\LaravelCsrm\Generators;

class MigrationGenerator extends Generator
{
    public function generate(string $name): void
    {
        $table = $this->plural($name);
        $migrationPattern = database_path("migrations/*_create_{$table}_table.php");
        $existingMigrations = glob($migrationPattern) ?: [];

        if (! empty($existingMigrations) && ! $this->force) {
            $this->command->line("  <fg=yellow>SKIP</>  migration for [{$table}] already exists");

            return;
        }

        if (! empty($existingMigrations) && $this->force) {
            foreach ($existingMigrations as $migration) {
                @unlink($migration);
                $this->command->line("  <fg=yellow>DELETE</> {$this->relativePath($migration)}");
            }
        }

        $exitCode = $this->command->call('make:migration', [
            'name' => "create_{$table}_table",
            '--create' => $table,
        ]);

        if ($exitCode !== 0) {
            throw new \RuntimeException("failed to generate migration for [{$table}]");
        }

        $generatedMigrations = glob($migrationPattern) ?: [];
        $latestMigration = end($generatedMigrations);

        if ($latestMigration !== false) {
            $this->command->line("  <fg=green>CREATE</> {$this->relativePath($latestMigration)}");
        }
    }
}
