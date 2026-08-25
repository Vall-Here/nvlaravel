<?php

namespace App\Services;

use Symfony\Component\Process\Process;
use Illuminate\Support\Facades\File;

class Scaffolder
{
    protected $output;
    protected $projectPath;

    public function setOutput($output)
    {
        $this->output = $output;
    }

    public function build(array $config): bool
    {
        $this->projectPath = getcwd() . DIRECTORY_SEPARATOR . $config['name'];

        if (!empty($config['git'])) {
            $this->step("Cloning Custom Template from {$config['git']}...");
            $this->runProcess(['git', 'clone', $config['git'], $config['name']]);
        } else {
            $this->step("Installing Base Laravel 13...");
            $this->runProcess(['composer', 'create-project', 'laravel/laravel', $config['name']]);
        }

        // Change directory to the new project for subsequent commands
        chdir($this->projectPath);

        $this->setupDatabase($config['database']);

        if ($config['type'] === 'admin') {
            $this->step("Installing Auth Stack ({$config['stack']})...");
            $this->runProcess(['composer', 'require', 'laravel/breeze', '--dev']);
            
            $breezeInstallArgs = ['php', 'artisan', 'breeze:install', $config['stack'], '--no-interaction'];
            $this->runProcess($breezeInstallArgs);

            $this->injectStubs();
        }

        if (!empty($config['libraries'])) {
            $this->step("Installing additional libraries...");
            
            $packages = array_merge(['composer', 'require'], $config['libraries']);
            $this->runProcess($packages);
            
            $this->publishLibraryConfigs($config['libraries']);
        }

        $this->step("Finalizing (Migrations & Build)...");
        $this->runProcess(['php', 'artisan', 'migrate', '--force']);
        $this->runProcess(['npm', 'install']);
        $this->runProcess(['npm', 'run', 'build']);

        return true;
    }

    protected function setupDatabase($type)
    {
        $this->step("Setting up database: {$type}...");
        
        $envPath = $this->projectPath . '/.env';
        if (!File::exists($envPath)) {
            File::copy($this->projectPath . '/.env.example', $envPath);
        }

        $envContent = File::get($envPath);
        
        // Remove existing DB_ settings
        $envContent = preg_replace('/^DB_.*$/m', '', $envContent);
        // Clean up multiple empty lines
        $envContent = preg_replace("/\n\n+/", "\n\n", $envContent);

        $dbSettings = "";
        if ($type === 'sqlite') {
            $dbSettings = "DB_CONNECTION=sqlite\n";
            $sqlitePath = $this->projectPath . '/database/database.sqlite';
            if (!File::exists($sqlitePath)) {
                File::put($sqlitePath, '');
            }
        } elseif ($type === 'mysql') {
            $dbSettings = "DB_CONNECTION=mysql\nDB_HOST=127.0.0.1\nDB_PORT=3306\nDB_DATABASE=" . basename($this->projectPath) . "\nDB_USERNAME=root\nDB_PASSWORD=\n";
        } elseif ($type === 'pgsql') {
            $dbSettings = "DB_CONNECTION=pgsql\nDB_HOST=127.0.0.1\nDB_PORT=5432\nDB_DATABASE=" . basename($this->projectPath) . "\nDB_USERNAME=postgres\nDB_PASSWORD=\n";
        }

        File::put($envPath, $envContent . "\n" . $dbSettings);
    }

    protected function injectStubs()
    {
        $this->step("Injecting System Templates...");
        $stubPath = base_path('stubs/dashboard');
        
        if (File::exists($stubPath)) {
            File::copyDirectory($stubPath, $this->projectPath);
            $this->step("System templates injected successfully.");
        } else {
            $this->step("No system templates found to inject (skipping).");
        }
    }

    protected function publishLibraryConfigs(array $libraries)
    {
        $libConfig = config('starter.libraries');
        foreach ($libraries as $lib) {
            if (isset($libConfig[$lib]['commands'])) {
                foreach ($libConfig[$lib]['commands'] as $cmd) {
                    $this->step("Running custom command for {$lib}...");
                    $this->runProcess($cmd);
                }
            }
        }
    }

    protected function step($message)
    {
        if ($this->output) {
            $this->output->writeln("<info>---> {$message}</info>");
        }
    }

    protected function runProcess(array $command)
    {
        $process = new Process($command);
        $process->setTimeout(3600);
        $process->run(function ($type, $buffer) {
            if ($this->output && $this->output->isVerbose()) {
                $this->output->write($buffer);
            }
        });

        if (!$process->isSuccessful()) {
            throw new \RuntimeException($process->getErrorOutput());
        }
    }
}
