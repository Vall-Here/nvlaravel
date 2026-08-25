<?php

declare(strict_types=1);

namespace App\Handlers\UI;

use App\Services\StarterContext;

final class InstallDaisyUI
{
    public function handle(StarterContext $context): void
    {
        $context->npm()->install(['daisyui']);
        $context->log("DaisyUI installed.");
    }
}
