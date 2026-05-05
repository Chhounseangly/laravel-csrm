<?php

namespace Seangly\LaravelCsrm\Generators;

use Illuminate\Console\Command;

class ControllerGenerator extends Generator
{
    public function __construct(
        Command $command,
        bool $force = false,
        protected bool $api = false
    ) {
        parent::__construct($command, $force);
    }

    public function generate(string $name): void
    {
        $stub = $this->api
            ? 'api-controller-empty.stub'
            : 'web-controller-empty.stub';

        $content = $this->renderStub($stub, [
            'Name' => $this->studly($name),
            'variable' => $this->camel($name),
        ]);

        $path = $this->api
            ? app_path("Http/Controllers/Api/{$this->studly($name)}Controller.php")
            : app_path("Http/Controllers/{$this->studly($name)}Controller.php");

        $this->writeFile($path, $content);
    }
}
