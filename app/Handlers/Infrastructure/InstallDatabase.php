<?php

declare(strict_types=1);

namespace App\Handlers\Infrastructure;

use App\Services\StarterContext;

final class InstallDatabase
{
    public function handle(StarterContext $context): void
    {
        $dbPath = 'database/database.sqlite';
        
        if (!$context->files()->exists($dbPath)) {
            $context->files()->put($dbPath, '');
        }

        $context->composer()->artisan(['migrate', '--force']);
        $context->log("Database initialized (SQLite) and migrations executed successfully.");
    }
}
