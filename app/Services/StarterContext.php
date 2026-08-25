<?php

declare(strict_types=1);

namespace App\Services;

use App\Configuration\RuntimeConfig;

final class StarterContext
{
    private RuntimeConfig $runtime;
    private ComposerManager $composer;
    private NpmManager $npm;
    private FileModifier $files;
    private $output;

    public function __construct(
        RuntimeConfig $runtime,
        ComposerManager $composer,
        NpmManager $npm,
        FileModifier $files,
        $output = null
    ) {
        $this->runtime = $runtime;
        $this->composer = $composer;
        $this->npm = $npm;
        $this->files = $files;
        $this->output = $output;

        $this->composer->setContext($this);
        $this->npm->setContext($this);
        $this->files->setContext($this);
    }

    public function runtime(): RuntimeConfig
    {
        return $this->runtime;
    }

    public function composer(): ComposerManager
    {
        return $this->composer;
    }

    public function npm(): NpmManager
    {
        return $this->npm;
    }

    public function files(): FileModifier
    {
        return $this->files;
    }

    public function projectPath(string $subpath = ''): string
    {
        $base = $this->runtime->projectPath();
        if ($subpath === '') {
            return $base;
        }
        return $base . DIRECTORY_SEPARATOR . ltrim($subpath, '/\\');
    }

    public function log(string $message): void
    {
        if ($this->output) {
            $this->output->writeln("  <info>✓</info> {$message}");
        }
    }

    public function warn(string $message): void
    {
        if ($this->output) {
            $this->output->writeln("  <comment>!</comment> {$message}");
        }
    }
}
