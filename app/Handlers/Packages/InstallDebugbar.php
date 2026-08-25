<?php

declare(strict_types=1);

namespace App\Handlers\Packages;

use App\Services\StarterContext;

final class InstallDebugbar
{
    public function handle(StarterContext $context): void
    {
        $context->composer()->require(['barryvdh/laravel-debugbar'], dev: true);
        $context->log("Barryvdh Laravel Debugbar installed.");
    }
}
