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
