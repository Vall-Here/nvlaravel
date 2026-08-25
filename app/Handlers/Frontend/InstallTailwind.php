<?php

declare(strict_types=1);

namespace App\Handlers\Frontend;

use App\Services\StarterContext;

final class InstallTailwind
{
    public function handle(StarterContext $context): void
    {
        $version = $context->runtime()->getOption('tailwind', 'version', 'v4');

        if ($version === 'v3') {
            $context->npm()->install(['tailwindcss@^3.4.0', 'postcss', 'autoprefixer']);
            
            $context->files()->put('tailwind.config.js', <<<JS
/** @type {import('tailwindcss').Config} */
export default {
  content: [
    "./resources/**/*.blade.php",
    "./resources/**/*.js",
    "./resources/**/*.vue",
  ],
  theme: {
    extend: {},
  },
  plugins: [],
}
JS);

            $context->files()->modify('resources/css/app.css', function (string $content) {
                if (!str_contains($content, '@tailwind base;')) {
                    return "@tailwind base;\n@tailwind components;\n@tailwind utilities;\n" . $content;
                }
                return $content;
            });

            $context->log("Tailwind CSS v3 configured with PostCSS and tailwind.config.js.");
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
