<?php

declare(strict_types=1);

namespace App\Services;

use Illuminate\Support\Facades\File;

final class FileModifier
{
    private ?StarterContext $context = null;

    public function setContext(StarterContext $context): void
    {
        $this->context = $context;
    }

    public function append(string $relativePath, string $content): void
    {
        $fullPath = $this->fullPath($relativePath);
        $existing = File::exists($fullPath) ? File::get($fullPath) : '';
        File::put($fullPath, $existing . "\n" . $content);
    }

    public function prepend(string $relativePath, string $content): void
    {
        $fullPath = $this->fullPath($relativePath);
        $existing = File::exists($fullPath) ? File::get($fullPath) : '';
        File::put($fullPath, $content . "\n" . $existing);
    }

    public function replace(string $relativePath, string $search, string $replace): void
    {
        $fullPath = $this->fullPath($relativePath);
        if (File::exists($fullPath)) {
            $content = File::get($fullPath);
            File::put($fullPath, str_replace($search, $replace, $content));
        }
    }

    public function modify(string $relativePath, callable $callback): void
    {
        $fullPath = $this->fullPath($relativePath);
        if (File::exists($fullPath)) {
            $content = File::get($fullPath);
            $newContent = $callback($content);
            File::put($fullPath, $newContent);
        }
    }

    public function put(string $relativePath, string $content): void
    {
        $fullPath = $this->fullPath($relativePath);
        File::ensureDirectoryExists(dirname($fullPath));
        File::put($fullPath, $content);
    }

    public function copyDirectory(string $sourcePath, string $targetRelativePath): void
    {
        $fullTarget = $this->fullPath($targetRelativePath);
        File::ensureDirectoryExists($fullTarget);
        File::copyDirectory($sourcePath, $fullTarget);
    }

    public function move(string $oldRelativePath, string $newRelativePath): void
    {
        $oldFull = $this->fullPath($oldRelativePath);
        $newFull = $this->fullPath($newRelativePath);
        if (File::exists($oldFull)) {
            File::move($oldFull, $newFull);
        }
    }

    public function delete(string $relativePath): void
    {
        $fullPath = $this->fullPath($relativePath);
        if (File::exists($fullPath)) {
            File::delete($fullPath);
        }
    }

    public function exists(string $relativePath): bool
    {
        return File::exists($this->fullPath($relativePath));
    }

    private function fullPath(string $relativePath): string
    {
        return $this->context ? $this->context->projectPath($relativePath) : $relativePath;
    }
}
