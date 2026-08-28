<?php

declare(strict_types=1);

namespace App\Handlers\Frontend;

use App\Services\StarterContext;

final class InstallInertia
{
    public function handle(StarterContext $context): void
    {
        if ($context->runtime()->isEnabled('breeze')) {
            $context->log("Inertia configuration skipped (managed by Laravel Breeze).");
            return;
        }

        $context->composer()->require(['inertiajs/inertia-laravel']);
        $context->npm()->install(['@inertiajs/vite']);
        $context->composer()->artisan(['inertia:middleware']);

        // Register middleware in bootstrap/app.php
        $context->files()->modify('bootstrap/app.php', function (string $content) {
            $middlewareStub = "->withMiddleware(function (Middleware \$middleware) {\n        \$middleware->web(append: [\n            \App\Http\Middleware\HandleInertiaRequests::class,\n        ]);";
            return str_replace(
                '->withMiddleware(function (Middleware $middleware) {',
                $middlewareStub,
                $content
            );
        });

        // Create app.blade.php root layout
        $viteEntry = "['resources/css/app.css', 'resources/js/app.js']";
        $viteRefresh = "";
        
        if ($context->runtime()->isEnabled('react')) {
            $viteEntry = "['resources/css/app.css', 'resources/js/app.jsx']";
            $viteRefresh = "@viteReactRefresh\n        ";
        }

        $appBlade = <<<BLADE
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title inertia>{{ config('app.name', 'Laravel') }}</title>

        <!-- Scripts -->
        {$viteRefresh}@vite({$viteEntry})
        @inertiaHead
    </head>
    <body class="font-sans antialiased">
        @inertia
    </body>
</html>
BLADE;
        
        $context->files()->put('resources/views/app.blade.php', $appBlade);

        $context->log("Inertia.js server-side adapter installed and configured.");
    }
}
