<?php

namespace Seangly\LaravelCsrm\Generators;

use Illuminate\Console\Command;
use Illuminate\Support\Str;

abstract class Generator
{
    public function __construct(
        protected Command $command,
        protected bool $force = false
    ) {}

    abstract public function generate(string $name): void;

    // ── Stub resolution ──────────────────────────────────────────────────────

    protected function stubPath(string $stub): string
    {
        // User can publish stubs to stubs/csrm/ to override
        $published = base_path("stubs/csrm/{$stub}");

        return file_exists($published)
            ? $published
            : __DIR__.'/../Stubs/'.$stub;
    }

    protected function renderStub(string $stub, array $replacements): string
    {
        $content = file_get_contents($this->stubPath($stub));

        foreach ($replacements as $search => $replace) {
            $content = str_replace("{{ {$search} }}", $replace, $content);
        }

        return $content;
    }

    // ── File writing ─────────────────────────────────────────────────────────

    protected function writeFile(string $path, string $content): void
    {
        if (file_exists($path) && ! $this->force) {
            $this->command->line("  <fg=yellow>SKIP</>  {$this->relativePath($path)} already exists");

            return;
        }

        $directory = dirname($path);
        if (! is_dir($directory)) {
            mkdir($directory, 0755, true);
        }

        file_put_contents($path, $content);
        $this->command->line("  <fg=green>CREATE</> {$this->relativePath($path)}");
    }

    protected function relativePath(string $path): string
    {
        return str_replace(base_path().'/', '', $path);
    }

    // ── Name helpers ─────────────────────────────────────────────────────────

    protected function studly(string $name): string
    {
        return Str::studly($name);
    }

    protected function snake(string $name): string
    {
        return Str::snake($name);
    }

    protected function plural(string $name): string
    {
        return Str::plural(Str::snake($name));
    }

    protected function camel(string $name): string
    {
        return Str::camel($name);
    }

    protected function lower(string $name): string
    {
        return strtolower(Str::snake($name));
    }
}
