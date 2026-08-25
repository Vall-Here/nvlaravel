<?php

declare(strict_types=1);

namespace App\Handlers\Backend;

use App\Services\StarterContext;

final class InstallFortify
{
    public function handle(StarterContext $context): void
    {
        $context->composer()->require(['laravel/fortify']);
        $context->composer()->artisan(['fortify:install']);
        $context->log("Laravel Fortify backend authentication installed.");
    }
}
