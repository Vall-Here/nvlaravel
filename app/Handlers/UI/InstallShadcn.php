<?php

declare(strict_types=1);

namespace App\Handlers\UI;

use App\Services\StarterContext;

final class InstallShadcn
{
    public function handle(StarterContext $context): void
    {
        $context->npm()->install(['clsx', 'tailwind-merge', 'lucide-react', 'class-variance-authority']);
        $context->log("Shadcn UI prerequisites installed.");
    }
}
