<?php

declare(strict_types=1);

namespace App\Handlers\Frontend;

use App\Services\StarterContext;

final class InstallTailwind
{
    public function handle(StarterContext $context): void
    {
        if ($context->runtime()->isEnabled('breeze')) {
            $context->log("Tailwind CSS configuration skipped (managed by Laravel Breeze).");
            return;
        }

        $version = $context->runtime()->getOption('tailwind', 'version', 'v4');

        if ($version === 'v3') {
            $context->npm()->install(['tailwindcss@^3.4.0', 'postcss', 'autoprefixer']);
            
            // Generate official tailwind.config.js and postcss.config.js
            $context->npm()->run(['npx', 'tailwindcss', 'init', '-p']);

            // Update content array in tailwind.config.js to support Blade, Vue, and React
            $context->files()->modify('tailwind.config.js', function (string $content) {
                return str_replace(
                    'content: []',
                    "content: [\n    \"./resources/**/*.blade.php\",\n    \"./resources/**/*.js\",\n    \"./resources/**/*.jsx\",\n    \"./resources/**/*.vue\",\n  ]",
                    $content
                );
            });

            $context->files()->modify('resources/css/app.css', function (string $content) {
                if (!str_contains($content, '@tailwind base;')) {
                    return "@tailwind base;\n@tailwind components;\n@tailwind utilities;\n" . $content;
                }
                return $content;
            });

            $context->log("Tailwind CSS v3 configured officially with PostCSS.");
        } else {
            $context->npm()->install(['tailwindcss', '@tailwindcss/vite']);

            $context->files()->modify('resources/css/app.css', function (string $content) {
                if (!str_contains($content, '@import "tailwindcss";')) {
                    return "@import \"tailwindcss\";\n" . $content;
                }
                return $content;
            });

            $context->log("Tailwind CSS v4 configured with Vite plugin.");
        }
    }
}
