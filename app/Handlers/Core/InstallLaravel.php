<?php

declare(strict_types=1);

namespace App\Handlers\Core;

use App\Services\StarterContext;

final class InstallLaravel
{
    public function handle(StarterContext $context): void
    {
        $targetPath = $context->runtime()->projectPath();
        $version = $context->runtime()->getOption('laravel', 'version', 'latest');

        $package = match ($version) {
            '12' => 'laravel/laravel:^12.0',
            '11' => 'laravel/laravel:^11.0',
            default => 'laravel/laravel',
        };

        $context->composer()->createProject($package, $targetPath);
        $context->files()->put('database/database.sqlite', '');
        $context->log("Base Laravel Framework ({$version}) initialized at {$targetPath}");
    }
}
