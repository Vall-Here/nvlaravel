<?php

declare(strict_types=1);

namespace App\Handlers\Frontend;

use App\Services\StarterContext;

final class InstallBootstrap
{
    public function handle(StarterContext $context): void
    {
        $context->npm()->install(['bootstrap', '@popperjs/core']);
        $context->log("Bootstrap CSS installed.");
    }
}
