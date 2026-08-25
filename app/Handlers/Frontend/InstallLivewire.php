<?php

declare(strict_types=1);

namespace App\Handlers\Frontend;

use App\Services\StarterContext;

final class InstallLivewire
{
    public function handle(StarterContext $context): void
    {
        $context->composer()->require(['livewire/livewire']);
        $context->log("Laravel Livewire installed.");
    }
}
