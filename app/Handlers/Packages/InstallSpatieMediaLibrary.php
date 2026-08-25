<?php

declare(strict_types=1);

namespace App\Handlers\Packages;

use App\Services\StarterContext;

final class InstallSpatieMediaLibrary
{
    public function handle(StarterContext $context): void
    {
        $context->composer()->require(['spatie/laravel-medialibrary']);
        $context->composer()->publish('Spatie\MediaLibrary\MediaLibraryServiceProvider', 'migrations');
        $context->log("Spatie MediaLibrary installed & migrations published.");
    }
}
