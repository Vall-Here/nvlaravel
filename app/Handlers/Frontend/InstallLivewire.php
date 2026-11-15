<?php

declare(strict_types=1);

namespace App\Handlers\Frontend;

use App\Services\StarterContext;

final class InstallLivewire
{
    public function handle(StarterContext $context): void
    {
        $context->composer()->require(['livewire/livewire']);
        $context->composer()->artisan(['livewire:layout']);

        $jsEntry = ($context->files()->exists('resources/js/app.jsx') || $context->runtime()->isEnabled('react')) ? 'resources/js/app.jsx' : 'resources/js/app.js';

        $context->files()->modify('resources/views/components/layouts/app.blade.php', function (string $content) use ($jsEntry) {
            if (!str_contains($content, '@vite')) {
                return str_replace('</head>', "        @vite(['resources/css/app.css', '{$jsEntry}'])\n    </head>", $content);
            }
            return $content;
        });

        $context->log("Laravel Livewire installed and base layout generated with Vite directives.");
    }
}
