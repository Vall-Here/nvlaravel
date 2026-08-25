<?php

declare(strict_types=1);

namespace App\Handlers\Core;

use App\Services\StarterContext;

final class InstallModularArchitecture
{
    public function handle(StarterContext $context): void
    {
        $directories = [
            'app/Services',
            'app/Actions',
            'app/Data',
            'app/Queries',
            'app/Repositories',
        ];

        foreach ($directories as $dir) {
            $context->files()->put($dir . '/.gitkeep', '');
        }

        $context->log("Modular Architecture scaffolded (app/Services, app/Actions, app/Data, app/Queries, app/Repositories).");
    }
}
