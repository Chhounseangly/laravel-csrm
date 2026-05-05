<?php

namespace Seangly\LaravelCsrm\Generators;

class ServiceGenerator extends Generator
{
    public function generate(string $name): void
    {
        $content = $this->renderStub('service.stub', [
            'Name' => $this->studly($name),
        ]);

        $path = app_path("Services/{$this->studly($name)}Service.php");
        $this->writeFile($path, $content);
    }
}
