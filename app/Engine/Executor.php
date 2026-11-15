<?php

declare(strict_types=1);

namespace App\Engine;

use App\Planning\ExecutionPlan;
use App\Services\StarterContext;
use RuntimeException;

final class Executor
{
    private StarterContext $context;

    public function __construct(StarterContext $context)
    {
        $this->context = $context;
    }

    public function execute(ExecutionPlan $plan): void
    {
        $handlers = $plan->handlers();
        $optimizer = new \App\Services\PostInstallOptimizer();
        $optimized = false;

        foreach ($handlers as $techId => $handlerClass) {
            if ($techId === 'git' && !$optimized) {
                $optimizer->optimize($this->context);
                $optimized = true;
            }

            if (!class_exists($handlerClass)) {
                $this->context->warn("Handler class [{$handlerClass}] for technology [{$techId}] does not exist. Skipping.");
                continue;
            }

            $this->context->log("Executing handler for [{$techId}]...");

            /** @var object $handler */
            $handler = app($handlerClass);

            if (!method_exists($handler, 'handle')) {
                throw new RuntimeException("Handler [{$handlerClass}] must implement a handle(StarterContext \$context) method.");
            }

            $handler->handle($this->context);
        }

        if (!$optimized) {
            $optimizer->optimize($this->context);
        }
    }
}
