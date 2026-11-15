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

            $context->files()->put('tailwind.config.cjs', <<<JS
/** @type {import('tailwindcss').Config} */
module.exports = {
  content: [
    "./resources/**/*.blade.php",
    "./resources/**/*.js",
    "./resources/**/*.jsx",
    "./resources/**/*.vue",
  ],
  theme: {
    extend: {},
  },
  plugins: [],
};
JS);

            $context->files()->put('postcss.config.cjs', <<<JS
module.exports = {
  plugins: {
    tailwindcss: {},
    autoprefixer: {},
  },
};
JS);

            // Clean up v4 imports and set up v3 directives
            $context->files()->modify('resources/css/app.css', function (string $content) {
                $content = preg_replace('/@import\s+["\']tailwindcss["\'];?\s*/', '', $content);
                if (!str_contains($content, '@tailwind base;')) {
                    return "@tailwind base;\n@tailwind components;\n@tailwind utilities;\n" . ltrim($content);
                }
                return $content;
            });

            $context->log("Tailwind CSS v3 configured officially with PostCSS (.cjs).");
        } else {
            $context->npm()->install(['tailwindcss', '@tailwindcss/vite']);

            // Clean any legacy config files that might conflict with Tailwind v4 Vite plugin
            $context->files()->delete('tailwind.config.js');
            $context->files()->delete('tailwind.config.cjs');
            $context->files()->delete('postcss.config.js');
            $context->files()->delete('postcss.config.cjs');

            // Clean up legacy v3 directives and ensure v4 @import
            $context->files()->modify('resources/css/app.css', function (string $content) {
                $content = preg_replace('/@tailwind\s+(base|components|utilities);?\s*/', '', $content);
                if (!str_contains($content, '@import "tailwindcss";')) {
                    return "@import \"tailwindcss\";\n" . ltrim($content);
                }
                return $content;
            });

            $context->log("Tailwind CSS v4 configured with Vite plugin.");
        }
    }
}
