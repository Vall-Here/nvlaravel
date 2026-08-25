<?php

declare(strict_types=1);

namespace App\Planning;

use App\Configuration\Blueprint;
use App\Configuration\RuntimeConfig;

final class ExecutionPlan
{
    private RuntimeConfig $runtime;
    private Blueprint $blueprint;
    private array $resolvedIds;

    public function __construct(RuntimeConfig $runtime, Blueprint $blueprint, array $resolvedIds)
    {
        $this->runtime = $runtime;
        $this->blueprint = $blueprint;
        $this->resolvedIds = $resolvedIds;
    }

    public function runtime(): RuntimeConfig
    {
        return $this->runtime;
    }

    public function resolvedIds(): array
    {
        return $this->resolvedIds;
    }

    /**
     * Get IDs that were explicitly selected by the user.
     */
    public function userSelectedIds(): array
    {
        return $this->runtime->enabledIds();
    }

    /**
     * Get IDs that were automatically pulled in as dependencies.
     */
    public function autoResolvedIds(): array
    {
        return array_values(array_diff($this->resolvedIds, $this->userSelectedIds()));
    }

    /**
     * Return list of handlers to execute in order.
     *
     * @return array<string, string> Array mapping technology ID => Handler class FQCN
     */
    public function handlers(): array
    {
        $handlers = [];
        foreach ($this->resolvedIds as $id) {
            $handler = $this->blueprint->handler($id);
            if ($handler !== null) {
                $handlers[$id] = $handler;
            }
        }
        return $handlers;
    }
}
