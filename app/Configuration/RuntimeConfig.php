<?php

declare(strict_types=1);

namespace App\Configuration;

final class RuntimeConfig
{
    private array $enabled = [];
    private array $options = [];
    private string $projectName = '';
    private string $projectPath = '';

    public function setProject(string $name, string $basePath): self
    {
        $this->projectName = $name;
        $this->projectPath = rtrim($basePath, DIRECTORY_SEPARATOR) . DIRECTORY_SEPARATOR . $name;
        return $this;
    }

    public function projectName(): string
    {
        return $this->projectName;
    }

    public function projectPath(): string
    {
        return $this->projectPath;
    }

    public function enable(string $id): self
    {
        $this->enabled[$id] = true;
        return $this;
    }

    public function isEnabled(string $id): bool
    {
        return $this->enabled[$id] ?? false;
    }

    public function enableMany(array $ids): self
    {
        foreach ($ids as $id) {
            $this->enable($id);
        }
        return $this;
    }

    public function setOption(string $id, string $key, mixed $value): self
    {
        $this->options[$id][$key] = $value;
        return $this;
    }

    public function getOption(string $id, string $key, mixed $default = null): mixed
    {
        return $this->options[$id][$key] ?? $default;
    }

    public function enabledIds(): array
    {
        return array_keys(array_filter($this->enabled));
    }
}
