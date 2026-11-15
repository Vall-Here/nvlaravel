<?php

declare(strict_types=1);

namespace App\Handlers\Frontend;

use App\Services\StarterContext;

final class InstallHtmx
{
    public function handle(StarterContext $context): void
    {
        $context->npm()->install(['htmx.org']);

        $jsFile = 'resources/js/app.js';
        if ($context->files()->exists('resources/js/app.jsx')) {
            $jsFile = 'resources/js/app.jsx';
        }

        $context->files()->modify($jsFile, function (string $content) {
            if (!str_contains($content, 'htmx')) {
                return "import htmx from 'htmx.org';\nwindow.htmx = htmx;\n" . $content;
            }
            return $content;
        });

        $context->log("HTMX installed and imported in {$jsFile}.");
    }
}
