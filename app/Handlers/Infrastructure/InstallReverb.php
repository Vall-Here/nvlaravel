<?php

declare(strict_types=1);

namespace App\Handlers\Infrastructure;

use App\Services\StarterContext;

final class InstallReverb
{
    public function handle(StarterContext $context): void
    {
        $context->composer()->artisan(['install:broadcasting', '--no-interaction']);
        $context->log("Laravel Reverb WebSocket broadcasting installed.");
    }
}
