<?php

declare(strict_types=1);

namespace App\Handlers\UI;

use App\Services\StarterContext;

final class InstallShadcn
{
    public function handle(StarterContext $context): void
    {
        $packages = ['clsx', 'tailwind-merge', 'class-variance-authority'];
        if ($context->runtime()->isEnabled('react')) {
            $packages[] = 'lucide-react';
        }

        $context->npm()->install($packages);

        $context->files()->put('resources/js/lib/utils.js', <<<JS
import { clsx } from 'clsx';
import { twMerge } from 'tailwind-merge';

export function cn(...inputs) {
    return twMerge(clsx(inputs));
}
JS);

        $context->log("Shadcn UI prerequisites installed and resources/js/lib/utils.js created.");
    }
}
