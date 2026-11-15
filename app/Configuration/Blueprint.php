<?php

declare(strict_types=1);

namespace App\Configuration;

use Illuminate\Support\Arr;

final class Blueprint
{
    private array $registry;

    public function __construct(array $registry)
    {
        $this->registry = $registry;
    }

    public static function fromConfig(): self
    {
        $data = config('generator');

        if ($data === null && file_exists(base_path('config/generator.php'))) {
            $data = require base_path('config/generator.php');
        }

        return new self($data ?? []);
    }

    public function technology(string $id): array
    {
        return Arr::get($this->registry, "technologies.{$id}", []);
    }

    public function name(string $id): string
    {
        return $this->technology($id)['name'] ?? $id;
    }

    public function requires(string $id): array
    {
        return $this->technology($id)['requires'] ?? [];
    }

    public function conflicts(string $id): array
    {
        return $this->technology($id)['conflicts'] ?? [];
    }

    public function handler(string $id): ?string
    {
        return $this->technology($id)['handler'] ?? null;
    }

    public function allTechnologies(): array
    {
        return $this->registry['technologies'] ?? [];
    }

    public function preset(string $name): array
    {
        return Arr::get($this->registry, "presets.{$name}", []);
    }

    public function allPresets(): array
    {
        return $this->registry['presets'] ?? [];
    }
}
