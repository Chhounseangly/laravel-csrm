<?php

namespace Seangly\LaravelCsrm\Generators;

class RepositoryGenerator extends Generator
{
    public function generate(string $name): void
    {
        $content = $this->renderStub('repository.stub', [
            'Name' => $this->studly($name),
        ]);

        $interface = $this->renderStub('repository-interface.stub', [
            'Name' => $this->studly($name),
        ]);

        $this->writeFile(app_path("Repositories/Contracts/{$this->studly($name)}RepositoryInterface.php"), $interface);
        $this->writeFile(app_path("Repositories/{$this->studly($name)}Repository.php"), $content);
    }
}
