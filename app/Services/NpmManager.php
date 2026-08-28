<?php

declare(strict_types=1);

namespace App\Services;

use Symfony\Component\Process\Process;
use RuntimeException;

final class NpmManager
{
    private ?StarterContext $context = null;

    public function setContext(StarterContext $context): void
    {
        $this->context = $context;
    }

    public function install(array $packages = [], bool $dev = true): void
    {
        if (empty($packages)) {
            $this->run(['npm', 'install', '--legacy-peer-deps']);
            return;
        }

        $cmd = array_merge(['npm', 'install'], $packages);
        if ($dev) {
            $cmd[] = '-D';
        }
        $cmd[] = '--legacy-peer-deps';
        
        $this->run($cmd);
    }

    public function runBuild(): void
    {
        $this->run(['npm', 'run', 'build']);
    }

    public function run(array $command, ?string $cwd = null): void
    {
        if ($cwd === null) {
            $projectPath = $this->context ? $this->context->projectPath() : getcwd();
            $cwd = file_exists($projectPath) ? $projectPath : dirname($projectPath);
        }
        
        $process = new Process($command, $cwd);
        $process->setTimeout(3600);
        $process->run();

        if (!$process->isSuccessful()) {
            throw new RuntimeException("NPM command failed: " . implode(' ', $command) . "\n" . $process->getErrorOutput());
        }
    }
}
