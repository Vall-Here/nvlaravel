<?php

declare(strict_types=1);

namespace App\Services;

use Symfony\Component\Process\Process;

final class EnvironmentDoctor
{
    /**
     * Check if system environment meets all CLI execution prerequisites.
     *
     * @return string[] Array of diagnostic issue messages
     */
    public function checkPrerequisites(): array
    {
        $issues = [];

        if (version_compare(PHP_VERSION, '8.2.0', '<')) {
            $issues[] = "PHP 8.2.0 or higher is required. Current version: " . PHP_VERSION;
        }

        if (!$this->isBinaryAvailable('composer')) {
            $issues[] = "Composer binary is not accessible in your system PATH.";
        }

        if (!$this->isBinaryAvailable('npm')) {
            $issues[] = "Node / NPM binary is not accessible in your system PATH.";
        }

        return $issues;
    }

    public function isBinaryAvailable(string $binary): bool
    {
        $cmd = (PHP_OS_FAMILY === 'Windows') ? ['where', $binary] : ['which', $binary];
        $process = new Process($cmd);
        $process->run();
        return $process->isSuccessful();
    }
}
