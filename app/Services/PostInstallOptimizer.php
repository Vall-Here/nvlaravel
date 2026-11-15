<?php

declare(strict_types=1);

namespace App\Services;

class PostInstallOptimizer
{
    public function optimize(StarterContext $context): void
    {
        $context->log('Running Post-Install optimizations...');
        
        $this->optimizeEnv($context);
        $this->generateReadme($context);
        $this->injectDevRunner($context);
    }

    private function optimizeEnv(StarterContext $context): void
    {
        $context->files()->modify('.env', function (string $content) use ($context) {
            // Set APP_URL to local server default so links work out of the box
            $content = preg_replace('/APP_URL=http:\/\/localhost/', 'APP_URL=http://localhost:8000', $content);
            
            // Adjust Queue Connection
            if ($context->runtime()->isEnabled('horizon')) {
                // Horizon requires redis
                $content = preg_replace('/^QUEUE_CONNECTION=.*$/m', 'QUEUE_CONNECTION=redis', $content);
            }

            return $content;
        });
        
        $context->log('.env optimized for selected services.');
    }

    private function generateReadme(StarterContext $context): void
    {
        $runtime = $context->runtime();
        $appName = $runtime->projectName();
        
        $readme = "# {$appName}\n\n";
        $readme .= "This project was generated using `nvlaravel`.\n\n";
        $readme .= "## Getting Started\n\n";
        
        if ($runtime->isEnabled('vite')) {
            $readme .= "1. Install NPM dependencies: `npm install`\n";
            $readme .= "2. Run the dev server: `npm run dev:all` (or `npm run dev` and `php artisan serve` separately)\n\n";
        } else {
            $readme .= "1. Run the dev server: `php artisan serve`\n\n";
        }

        $readme .= "## Installed Services Dashboard\n\n";
        $readme .= "| Service | Command / URL |\n";
        $readme .= "|---|---|\n";
        $readme .= "| Local Server | `http://localhost:8000` |\n";
        
        if ($runtime->isEnabled('reverb')) {
            $readme .= "| Laravel Reverb | `php artisan reverb:start` |\n";
        }
        
        if ($runtime->isEnabled('horizon')) {
            $readme .= "| Laravel Horizon | `http://localhost:8000/horizon` (Requires `php artisan horizon`) |\n";
        }
        
        if ($runtime->isEnabled('telescope')) {
            $readme .= "| Laravel Telescope | `http://localhost:8000/telescope` |\n";
        }

        if ($runtime->isEnabled('tailwind')) {
            $readme .= "\n## Styling\n\nThis project uses Tailwind CSS. Modify your styles in `resources/css/app.css` and configure plugins according to your chosen UI libraries.\n";
        }
        
        if ($runtime->isEnabled('modular-architecture')) {
            $readme .= "\n## Architecture\n\nThis project follows a Modular Domain Architecture. Business logic resides in `app/Services`, `app/Actions`, `app/Data`, `app/Queries`, and `app/Repositories`.\n";
        }

        $context->files()->put('README.md', $readme);
        $context->log('Custom README.md documentation generated.');
    }

    private function injectDevRunner(StarterContext $context): void
    {
        $runtime = $context->runtime();
        
        $commands = ['"php artisan serve"'];
        
        if ($runtime->isEnabled('vite')) {
            $commands[] = '"npm run dev"';
        }
        
        if ($runtime->isEnabled('reverb')) {
            $commands[] = '"php artisan reverb:start"';
        }
        
        if ($runtime->isEnabled('horizon')) {
            $commands[] = '"php artisan horizon"';
        } elseif ($runtime->isEnabled('reverb') || $runtime->isEnabled('telescope') || $runtime->isEnabled('spatie-medialibrary')) {
            // Only inject queue:listen when a package actually dispatches jobs
            $commands[] = '"php artisan queue:listen"';
        }
        
        // If only serve (and optionally vite), no need for concurrently.
        if (count($commands) <= 1) {
            return;
        }

        // Install concurrently
        $context->npm()->install(['concurrently'], true);
        
        $commandString = "concurrently " . implode(' ', $commands);
        
        $context->files()->modify('package.json', function (string $content) use ($commandString) {
            $json = json_decode($content, true);
            if (isset($json['scripts'])) {
                $json['scripts']['dev:all'] = $commandString;
            }
            return json_encode($json, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);
        });
        
        $context->log('Injected `npm run dev:all` concurrently script.');
    }
}
