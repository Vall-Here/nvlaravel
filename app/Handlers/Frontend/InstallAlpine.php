<?php

declare(strict_types=1);

namespace App\Handlers\Frontend;

use App\Services\StarterContext;

final class InstallAlpine
{
    public function handle(StarterContext $context): void
    {
        $context->npm()->install(['alpinejs']);

        $context->files()->modify('resources/js/app.js', function (string $content) {
            if (!str_contains($content, 'Alpine')) {
                return "import Alpine from 'alpinejs';\nwindow.Alpine = Alpine;\nAlpine.start();\n" . $content;
            }
            return $content;
        });

        $context->log("Alpine.js installed and initialized in resources/js/app.js.");
    }
}
