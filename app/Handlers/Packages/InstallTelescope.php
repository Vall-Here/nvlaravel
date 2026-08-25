<?php

declare(strict_types=1);

namespace App\Handlers\Packages;

use App\Services\StarterContext;

final class InstallTelescope
{
    public function handle(StarterContext $context): void
    {
        $context->composer()->require(['laravel/telescope'], dev: true);
        $context->composer()->artisan(['telescope:install']);
        $context->log("Laravel Telescope installed & configured for dev environment.");
    }
}
