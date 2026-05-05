<?php

namespace Seangly\LaravelCsrm\Generators;

class BaseGenerator extends Generator
{
    public function generate(string $name = ''): void
    {
        $this->generateBaseController();
        $this->generateBaseService();
        $this->generateBaseRepository();
        $this->generateBaseRepositoryInterface();
    }

    protected function generateBaseController(): void
    {
        $content = $this->renderStub('base-controller.stub', []);
        $this->writeFile(app_path('Http/Controllers/BaseController.php'), $content);
    }

    protected function generateBaseService(): void
    {
        $content = $this->renderStub('base-service.stub', []);
        $this->writeFile(app_path('Services/BaseService.php'), $content);
    }

    protected function generateBaseRepository(): void
    {
        $content = $this->renderStub('base-repository.stub', []);
        $this->writeFile(app_path('Repositories/BaseRepository.php'), $content);
    }

    protected function generateBaseRepositoryInterface(): void
    {
        $content = $this->renderStub('base-repository-interface.stub', []);
        $this->writeFile(app_path('Repositories/Contracts/BaseRepositoryInterface.php'), $content);
    }
}
