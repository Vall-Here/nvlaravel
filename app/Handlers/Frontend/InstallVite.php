<?php

declare(strict_types=1);

namespace App\Handlers\Frontend;

use App\Services\StarterContext;

final class InstallVite
{
    public function handle(StarterContext $context): void
    {
        $context->npm()->install();
        $context->log("Vite dependencies installed.");
    }
}
