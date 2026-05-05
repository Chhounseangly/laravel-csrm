<?php

namespace Seangly\LaravelCsrm;

use Illuminate\Support\ServiceProvider;
use Seangly\LaravelCsrm\Commands\InstallCommand;
use Seangly\LaravelCsrm\Commands\MakeModuleCommand;

class CsrmServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        if ($this->app->runningInConsole()) {
            $this->commands([
                InstallCommand::class,
                MakeModuleCommand::class,
            ]);

            // Allow users to publish and customise stubs
            $this->publishes([
                __DIR__.'/Stubs' => base_path('stubs/csrm'),
            ], 'csrm-stubs');
        }
    }

    public function register(): void
    {
        //
    }
}
