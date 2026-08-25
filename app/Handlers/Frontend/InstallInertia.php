<?php

declare(strict_types=1);

namespace App\Handlers\Frontend;

use App\Services\StarterContext;

final class InstallInertia
{
    public function handle(StarterContext $context): void
    {
        $context->composer()->require(['inertiajs/inertia-laravel']);
        $context->composer()->artisan(['inertia:middleware']);
        $context->log("Inertia.js server-side adapter installed.");
    }
}
