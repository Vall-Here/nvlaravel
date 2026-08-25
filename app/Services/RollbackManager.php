<?php

declare(strict_types=1);

namespace App\Services;

use Illuminate\Support\Facades\File;

final class RollbackManager
{
    private string $projectPath;

    public function __construct(string $projectPath)
    {
        $this->projectPath = $projectPath;
    }

    public function rollback(): void
    {
        if (File::isDirectory($this->projectPath)) {
            File::deleteDirectory($this->projectPath);
        }
    }
}
