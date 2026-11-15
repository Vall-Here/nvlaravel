<?php

declare(strict_types=1);

namespace App\Handlers\Frontend;

use App\Services\StarterContext;

final class InstallAlpine
{
    public function handle(StarterContext $context): void
    {
        if ($context->runtime()->isEnabled('livewire')) {
            $context->log("Alpine.js is bundled and managed by Livewire 3.");
            return;
        }

        $context->npm()->install(['alpinejs']);

        $jsFile = $context->files()->exists('resources/js/app.jsx') ? 'resources/js/app.jsx' : 'resources/js/app.js';

        $context->files()->modify($jsFile, function (string $content) {
            if (!str_contains($content, 'Alpine')) {
                return "import Alpine from 'alpinejs';\nwindow.Alpine = Alpine;\nAlpine.start();\n" . $content;
            }
            return $content;
        });

        $context->log("Alpine.js installed and initialized in {$jsFile}.");
    }
}
