<?php

declare(strict_types=1);

namespace App\Services;

use Symfony\Component\Process\Process;
use RuntimeException;

final class ComposerManager
{
    private ?StarterContext $context = null;

    public function setContext(StarterContext $context): void
    {
        $this->context = $context;
    }

    public function require(array $packages, bool $dev = false): void
    {
        $cmd = array_merge(['composer', 'require'], $packages);
        if ($dev) {
            $cmd[] = '--dev';
        }
        $this->run($cmd);
    }

    public function createProject(string $package, string $targetPath): void
    {
        $parentDir = dirname($targetPath);
        $this->run(['composer', 'create-project', $package, $targetPath], cwd: $parentDir);
    }

    public function artisan(array $command): void
    {
        $cmd = array_merge(['php', 'artisan'], $command);
        $this->run($cmd);
    }

    public function publish(string $provider, ?string $tag = null): void
    {
        $cmd = ['php', 'artisan', 'vendor:publish', "--provider={$provider}"];
        if ($tag !== null) {
            $cmd[] = "--tag={$tag}";
        }
        $this->run($cmd);
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
            throw new RuntimeException("Composer/Artisan command failed: " . implode(' ', $command) . "\n" . $process->getErrorOutput());
        }
    }
}
