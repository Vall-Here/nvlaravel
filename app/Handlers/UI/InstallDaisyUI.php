<?php

declare(strict_types=1);

namespace App\Handlers\UI;

use App\Services\StarterContext;

final class InstallDaisyUI
{
    public function handle(StarterContext $context): void
    {
        $version = $context->runtime()->isEnabled('breeze') ? 'v4' : $context->runtime()->getOption('tailwind', 'version', 'v4');
        $context->npm()->install([$version === 'v3' ? 'daisyui@^4' : 'daisyui@latest']);

        if ($version === 'v3') {
            $configTarget = $context->files()->exists('tailwind.config.cjs') ? 'tailwind.config.cjs' : 'tailwind.config.js';
            $context->files()->modify($configTarget, function (string $content) {
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
