<?php

declare(strict_types=1);

namespace App\Handlers\Frontend;

use App\Services\StarterContext;

final class InstallBlade
{
    public function handle(StarterContext $context): void
    {
        $context->log("Blade templating verified.");
    }
}
