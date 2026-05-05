<?php

use Seangly\LaravelCsrm\Support\FileEditor;

it('adds repository binding inside register method', function () {
    $path = tempnam(sys_get_temp_dir(), 'provider_');

    file_put_contents($path, <<<'PHP'
<?php

class AppServiceProvider
{
    public function register(): void
    {
        //
    }
}
PHP);

    $result = FileEditor::ensureRepositoryBinding($path, 'Post');
    $content = file_get_contents($path);

    expect($result)->toBeTrue();
    expect($content)->toContain('\App\Repositories\Contracts\PostRepositoryInterface::class');
    expect($content)->toContain('\App\Repositories\PostRepository::class');
    expect($content)->not->toContain('        //');

    @unlink($path);
});

it('inserts clean binding block in empty register method body', function () {
    $path = tempnam(sys_get_temp_dir(), 'provider_');

    file_put_contents($path, <<<'PHP'
<?php

class AppServiceProvider
{
    public function register(): void
    {
        //
    }
}
PHP);

    FileEditor::ensureRepositoryBinding($path, 'User');
    $content = file_get_contents($path);

    expect($content)->toContain(<<<'PHP'
    public function register(): void
    {
        $this->app->bind(
            \App\Repositories\Contracts\UserRepositoryInterface::class,
            \App\Repositories\UserRepository::class,
        );
    }
PHP);

    @unlink($path);
});

it('does not duplicate repository binding', function () {
    $path = tempnam(sys_get_temp_dir(), 'provider_');

    file_put_contents($path, <<<'PHP'
<?php

class AppServiceProvider
{
    public function register(): void
    {
        $this->app->bind(
            \App\Repositories\Contracts\PostRepositoryInterface::class,
            \App\Repositories\PostRepository::class,
        );
    }
}
PHP);

    $result = FileEditor::ensureRepositoryBinding($path, 'Post');
    $content = file_get_contents($path);

    expect($result)->toBeFalse();
    expect(substr_count($content, 'PostRepositoryInterface::class'))->toBe(1);

    @unlink($path);
});
