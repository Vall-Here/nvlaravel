<?php

declare(strict_types=1);

namespace App\Handlers\Packages;

use App\Services\StarterContext;

final class InstallHorizon
{
    public function handle(StarterContext $context): void
    {
        $context->composer()->require(['laravel/horizon']);
        $context->composer()->artisan(['horizon:install']);
        $context->log("Laravel Horizon queue monitor installed.");
    }
}
