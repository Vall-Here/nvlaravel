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

        // Ensure .env points to sqlite before migrating
        $context->files()->modify('.env', function (string $content) {
            if (preg_match('/^DB_CONNECTION=.*$/m', $content)) {
                $content = preg_replace('/^DB_CONNECTION=.*$/m', 'DB_CONNECTION=sqlite', $content);
            } else {
                $content .= "\nDB_CONNECTION=sqlite\n";
            }
            // Remove MySQL/Postgres connection details that conflict with sqlite
            $content = preg_replace('/^DB_HOST=.+\n?/m', '', $content);
            $content = preg_replace('/^DB_PORT=.+\n?/m', '', $content);
            $content = preg_replace('/^DB_DATABASE=.+\n?/m', '', $content);
            $content = preg_replace('/^DB_USERNAME=.+\n?/m', '', $content);
            $content = preg_replace('/^DB_PASSWORD=.+\n?/m', '', $content);
            return $content;
        });

        $context->composer()->artisan(['migrate', '--force']);
        $context->log('Database initialized (SQLite) and migrations executed successfully.');
    }
}
