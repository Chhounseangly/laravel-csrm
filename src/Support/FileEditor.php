<?php

namespace Seangly\LaravelCsrm\Support;

final class FileEditor
{
    public static function ensureRepositoryBinding(string $providerPath, string $name): bool
    {
        if (! file_exists($providerPath)) {
            return false;
        }

        $content = file_get_contents($providerPath);
        $needle = "\\App\\Repositories\\Contracts\\{$name}RepositoryInterface::class";

        if (str_contains($content, $needle)) {
            return false;
        }

        $binding = <<<PHP
\$this->app->bind(
    \\App\\Repositories\\Contracts\\{$name}RepositoryInterface::class,
    \\App\\Repositories\\{$name}Repository::class,
);
PHP;

        $updated = preg_replace_callback(
            '/(public function register\(\): void\s*\{)(.*?)(^\s*\})/ms',
            static function ($matches) use ($binding) {
                $opening = $matches[1];
                $body = trim($matches[2]);
                $closing = $matches[3];

                if ($body === '' || $body === '//') {
                    $newBody = '        '.str_replace("\n", "\n        ", $binding);
                } else {
                    $newBody = rtrim($matches[2])."\n\n".'        '.str_replace("\n", "\n        ", $binding);
                }

                return $opening."\n".$newBody."\n".$closing;
            },
            $content,
            1
        );

        if (! is_string($updated) || $updated === $content) {
            return false;
        }

        file_put_contents($providerPath, $updated);

        return true;
    }
}
