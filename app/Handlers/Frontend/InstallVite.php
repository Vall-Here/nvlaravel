<?php

declare(strict_types=1);

namespace App\Handlers\Frontend;

use App\Services\StarterContext;

final class InstallVite
{
    public function handle(StarterContext $context): void
    {
        if ($context->runtime()->isEnabled('breeze')) {
            $context->log("Vite configuration skipped (managed by Laravel Breeze).");
            return;
        }

        $inputJs = 'resources/js/app.js';
        if ($context->runtime()->isEnabled('react')) {
            $inputJs = 'resources/js/app.jsx';
        }

        $plugins = ["laravel({\n            input: ['resources/css/app.css', '{$inputJs}'],\n            refresh: true,\n        })"];
        $imports = ["import { defineConfig } from 'vite';\nimport laravel from 'laravel-vite-plugin';"];

        if ($context->runtime()->isEnabled('react')) {
            $imports[] = "import react from '@vitejs/plugin-react';";
            $plugins[] = "react()";
        }

        if ($context->runtime()->isEnabled('vue')) {
            $imports[] = "import vue from '@vitejs/plugin-vue';";
            $plugins[] = "vue({\n            template: {\n                transformAssetUrls: {\n                    base: null,\n                    includeAbsolute: false,\n                },\n            },\n        })";
        }

        if ($context->runtime()->isEnabled('inertia')) {
            $imports[] = "import inertia from '@inertiajs/vite';";
            $plugins[] = "inertia()";
        }

        if ($context->runtime()->isEnabled('tailwind') && $context->runtime()->getOption('tailwind', 'version', 'v4') === 'v4') {
            $imports[] = "import tailwindcss from '@tailwindcss/vite';";
            array_unshift($plugins, "tailwindcss()");
        }

        $importsStr = implode("\n", $imports);
        $pluginsStr = implode(",\n        ", $plugins);

        $viteConfig = <<<JS
{$importsStr}

export default defineConfig({
    plugins: [
        {$pluginsStr},
    ],
    resolve: {
        alias: {
            '@': '/resources/js',
        },
    },
});
JS;

        $context->files()->put('vite.config.js', $viteConfig);
        $context->log("Vite configured dynamically with plugins for: " . implode(', ', array_filter([
            $context->runtime()->isEnabled('react') ? 'React' : null,
            $context->runtime()->isEnabled('vue') ? 'Vue' : null,
            ($context->runtime()->isEnabled('tailwind') && $context->runtime()->getOption('tailwind', 'version', 'v4') === 'v4') ? 'Tailwind v4' : null,
        ])));
    }
}
