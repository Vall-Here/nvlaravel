<?php

declare(strict_types=1);

namespace App\Handlers\Frontend;

use App\Services\StarterContext;

final class InstallBootstrap
{
    public function handle(StarterContext $context): void
    {
        $context->npm()->install(['bootstrap', '@popperjs/core']);

        // Purge any Tailwind directives leftover from Laravel skeleton
        $context->files()->modify('resources/css/app.css', function (string $content) {
            $content = preg_replace('/@import\s+["\']tailwindcss["\'];?\s*/', '', $content);
            $content = preg_replace('/@tailwind\s+(base|components|utilities);?\s*/', '', $content);
            if (!str_contains($content, 'bootstrap')) {
                return "@import 'bootstrap/dist/css/bootstrap.min.css';\n" . ltrim($content);
            }
            return $content;
        });

        // Clean any Tailwind/PostCSS configs
        $context->files()->delete('tailwind.config.js');
        $context->files()->delete('tailwind.config.cjs');
        $context->files()->delete('postcss.config.js');
        $context->files()->delete('postcss.config.cjs');

        $jsFile = $context->files()->exists('resources/js/app.jsx') ? 'resources/js/app.jsx' : 'resources/js/app.js';
        $context->files()->modify($jsFile, function (string $content) {
            if (!str_contains($content, 'bootstrap')) {
                return "import 'bootstrap';\n" . $content;
            }
            return $content;
        });

        $context->log("Bootstrap CSS & JS installed and configured.");
    }
}
