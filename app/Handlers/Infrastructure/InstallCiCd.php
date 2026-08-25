<?php

declare(strict_types=1);

namespace App\Handlers\Infrastructure;

use App\Services\StarterContext;
use Illuminate\Support\Facades\File;

final class InstallCiCd
{
    public function handle(StarterContext $context): void
    {
        $workflowDir = $context->projectPath('.github/workflows');
        
        if (!File::exists($workflowDir)) {
            File::makeDirectory($workflowDir, 0755, true);
        }

        $stub = <<<'YAML'
name: Run Tests

on:
  push:
    branches: [ "main" ]
  pull_request:
    branches: [ "main" ]

jobs:
  test:
    runs-on: ubuntu-latest
    steps:
    - uses: shivammathur/setup-php@v2
      with:
        php-version: '8.2'
    - uses: actions/checkout@v4
    - name: Copy .env
      run: php -r "file_exists('.env') || copy('.env.example', '.env');"
    - name: Install Dependencies
      run: composer install -q --no-ansi --no-interaction --no-scripts --no-progress --prefer-dist
    - name: Generate key
      run: php artisan key:generate
    - name: Directory Permissions
      run: chmod -R 777 storage bootstrap/cache
    - name: Run Tests
      run: vendor/bin/pest
YAML;

        $context->files()->put('.github/workflows/tests.yml', $stub);
        $context->log("GitHub Actions CI/CD Pipeline configured for Tests.");
    }
}
