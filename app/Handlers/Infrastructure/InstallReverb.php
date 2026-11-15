<?php

declare(strict_types=1);

namespace App\Handlers\Infrastructure;

use App\Services\StarterContext;

final class InstallReverb
{
    public function handle(StarterContext $context): void
    {
        $context->composer()->artisan(['install:broadcasting', '--reverb', '--no-interaction']);
        $context->npm()->install(['laravel-echo', 'pusher-js']);

        $jsFile = $context->files()->exists('resources/js/app.jsx') ? 'resources/js/app.jsx' : 'resources/js/app.js';
        $context->files()->modify($jsFile, function (string $content) {
            if (!str_contains($content, "import './echo'")) {
                return "import './echo';\n" . $content;
            }
            return $content;
        });

        $context->log("Laravel Reverb WebSocket broadcasting installed and configured in {$jsFile}.");
    }
}
