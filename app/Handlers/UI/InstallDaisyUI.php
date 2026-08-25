<?php

declare(strict_types=1);

namespace App\Handlers\UI;

use App\Services\StarterContext;

final class InstallDaisyUI
{
    public function handle(StarterContext $context): void
    {
        $context->npm()->install(['daisyui']);
        
        $version = $context->runtime()->getOption('tailwind', 'version', 'v4');

        if ($version === 'v3') {
            $context->files()->modify('tailwind.config.js', function (string $content) {
                if (!str_contains($content, "require('daisyui')")) {
                    $content = preg_replace('/plugins:\s*\[/', "plugins: [\n    require('daisyui'),", $content);
                }
                return $content;
            });
        } else {
            $context->files()->modify('resources/css/app.css', function (string $content) {
                if (!str_contains($content, '@plugin "daisyui";')) {
                    return $content . "\n@plugin \"daisyui\";\n";
                }
                return $content;
            });
        }

        $context->log("DaisyUI installed and configured.");
    }
}
