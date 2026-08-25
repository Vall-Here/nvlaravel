<?php

declare(strict_types=1);

namespace App\Handlers\Packages;

use App\Services\StarterContext;

final class InstallSpatiePermission
{
    public function handle(StarterContext $context): void
    {
        $context->composer()->require(['spatie/laravel-permission']);
        $context->composer()->publish('Spatie\Permission\PermissionServiceProvider');
        $context->log("Spatie Permission installed & config published.");
    }
}
