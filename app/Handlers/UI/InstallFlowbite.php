<?php

declare(strict_types=1);

namespace App\Handlers\UI;

use App\Services\StarterContext;

final class InstallFlowbite
{
    public function handle(StarterContext $context): void
    {
        $context->npm()->install(['flowbite', 'flowbite-plugin']);
        
        $version = $context->runtime()->getOption('tailwind', 'version', 'v4');

        if ($version === 'v3') {
            $context->files()->modify('tailwind.config.js', function (string $content) {
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
                    return $content . "\n@plugin \"flowbite/plugin\";\n@source \"../node_modules/flowbite\";\n";
                }
                return $content;
            });
        }

        $context->log("Flowbite UI installed and configured.");
    }
}
