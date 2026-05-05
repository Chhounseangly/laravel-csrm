<?php

namespace Seangly\LaravelCsrm\Generators;

class ModelGenerator extends Generator
{
    public function generate(string $name): void
    {
        $content = $this->renderStub('model.stub', [
            'Name' => $this->studly($name),
            'table' => $this->plural($this->snake($name)),
        ]);

        $path = app_path("Models/{$this->studly($name)}.php");
        $this->writeFile($path, $content);
    }
}
