<?php

declare(strict_types=1);

namespace App\Handlers\Frontend;

use App\Services\StarterContext;

final class InstallHtmx
{
    public function handle(StarterContext $context): void
    {
        $context->npm()->install(['htmx.org']);

        $context->files()->modify('resources/js/app.js', function (string $content) {
            if (!str_contains($content, 'htmx')) {
                return "import 'htmx.org';\n" . $content;
            }
            return $content;
        });

        $context->log("HTMX installed and imported in resources/js/app.js.");
    }
}
