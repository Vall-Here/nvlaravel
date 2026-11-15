<?php

declare(strict_types=1);

namespace App\Handlers\UI;

use App\Services\StarterContext;

final class InstallFlowbite
{
    public function handle(StarterContext $context): void
    {
        $context->npm()->install(['flowbite']);
        
        $version = $context->runtime()->isEnabled('breeze') ? 'v4' : $context->runtime()->getOption('tailwind', 'version', 'v4');

        if ($version === 'v3') {
            $configTarget = $context->files()->exists('tailwind.config.cjs') ? 'tailwind.config.cjs' : 'tailwind.config.js';
            $context->files()->modify($configTarget, function (string $content) {
                // Add Flowbite to plugins
                if (!str_contains($content, "require('flowbite/plugin')")) {
                    $content = preg_replace('/plugins:\s*\[/', "plugins: [\n    require('flowbite/plugin'),", $content);
                }
                // Add Flowbite to content array
                if (!str_contains($content, 'flowbite/**/*.js')) {
                    $content = preg_replace('/content:\s*\[/', "content: [\n    \"./node_modules/flowbite/**/*.js\",", $content);
                }
                return $content;
            });
        } else {
            $context->files()->modify('resources/css/app.css', function (string $content) {
                if (!str_contains($content, '@plugin "flowbite/plugin";')) {
                    return $content . "\n@plugin \"flowbite/plugin\";\n@source \"../../node_modules/flowbite\";\n";
                }
                return $content;
            });
        }

        // Inject Flowbite JavaScript runtime for interactive components
        $jsFile = $context->files()->exists('resources/js/app.jsx') ? 'resources/js/app.jsx' : 'resources/js/app.js';
        $context->files()->modify($jsFile, function (string $content) {
            if (!str_contains($content, "import 'flowbite'")) {
                return "import 'flowbite';\n" . $content;
            }
            return $content;
        });

        $context->log("Flowbite UI installed and configured.");
    }
}
