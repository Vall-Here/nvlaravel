<?php

declare(strict_types=1);

namespace App\Handlers\Backend;

use App\Services\StarterContext;

final class InstallBreeze
{
    public function handle(StarterContext $context): void
    {
        $context->composer()->require(['laravel/breeze'], dev: true);

        $stack = 'blade';
        if ($context->runtime()->isEnabled('livewire')) {
            $stack = 'livewire';
        } elseif ($context->runtime()->isEnabled('react')) {
            $stack = 'react';
        } elseif ($context->runtime()->isEnabled('vue')) {
            $stack = 'vue';
        }

        $context->composer()->artisan(['breeze:install', $stack, '--no-interaction']);
        $context->npm()->install();
        $context->log("Laravel Breeze installed with [{$stack}] stack.");
    }
}
