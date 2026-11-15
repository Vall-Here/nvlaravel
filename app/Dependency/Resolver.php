<?php

declare(strict_types=1);

namespace App\Dependency;

use App\Configuration\Blueprint;
use App\Configuration\RuntimeConfig;
use RuntimeException;

final class Resolver
{
    private Blueprint $blueprint;

    public function __construct(Blueprint $blueprint)
    {
        $this->blueprint = $blueprint;
    }

    /**
     * Resolve user requested technologies into a topological order of dependencies.
     *
     * @return string[] Array of technology IDs in execution order
     */
    public function resolve(RuntimeConfig $runtime): array
    {
        $requested = $runtime->enabledIds();
        
        // Ensure base laravel framework is always included
        if (!in_array('laravel', $requested, true)) {
            array_unshift($requested, 'laravel');
        }

        $resolved = [];

        foreach ($requested as $id) {
            $this->resolveItem($id, $resolved, []);
        }

        $this->validateConflicts($resolved);

        // Inertia requires a client-side framework (default to Vue if neither React nor Vue is selected)
        if (in_array('inertia', $resolved, true) && !in_array('react', $resolved, true) && !in_array('vue', $resolved, true)) {
            $this->resolveItem('vue', $resolved, []);
        }

        // Stably sort resolved technologies into safe architectural execution layers
        usort($resolved, fn (string $a, string $b) => $this->layerRank($a) <=> $this->layerRank($b));

        return array_values($resolved);
    }

    private function layerRank(string $id): int
    {
        return match ($id) {
            'laravel' => 1,
            'blade', 'inertia', 'react', 'vue', 'tailwind', 'bootstrap', 'vite', 'livewire' => 2,
            'breeze', 'fortify' => 3,
            'flowbite', 'daisyui', 'shadcn', 'alpine', 'htmx' => 4,
            'database-migrate' => 6,
            'git' => 7,
            default => 5,
        };
    }

    private function resolveItem(string $id, array &$resolved, array $path): void
    {
        if (in_array($id, $resolved, true)) {
            return;
        }

        if (in_array($id, $path, true)) {
            $cycle = implode(' -> ', $path) . ' -> ' . $id;
            throw new RuntimeException("Circular dependency detected: {$cycle}");
        }

        $path[] = $id;

        $requires = $this->blueprint->requires($id);
        foreach ($requires as $req) {
            $this->resolveItem($req, $resolved, $path);
        }

        if (!in_array($id, $resolved, true)) {
            $resolved[] = $id;
        }
    }

    private function validateConflicts(array $resolved): void
    {
        foreach ($resolved as $id) {
            $conflicts = $this->blueprint->conflicts($id);
            foreach ($conflicts as $conflict) {
                if (in_array($conflict, $resolved, true)) {
                    $nameA = $this->blueprint->name($id);
                    $nameB = $this->blueprint->name($conflict);
                    throw new RuntimeException("Conflict detected: [{$nameA}] is incompatible with [{$nameB}].");
                }
            }
        }
    }
}
