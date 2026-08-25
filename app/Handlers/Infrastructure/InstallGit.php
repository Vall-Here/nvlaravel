<?php

declare(strict_types=1);

namespace App\Handlers\Infrastructure;

use App\Services\StarterContext;

final class InstallGit
{
    public function handle(StarterContext $context): void
    {
        try {
            $context->composer()->run(['git', 'init']);
            $context->composer()->run(['git', 'add', '.']);
            $context->composer()->run(['git', 'commit', '-m', 'Initial commit from nvlaravel']);
            $context->log("Git repository initialized and initial commit created.");
        } catch (\Throwable $e) {
            $context->warn("Failed to initialize Git repository: " . $e->getMessage());
        }
    }
}
