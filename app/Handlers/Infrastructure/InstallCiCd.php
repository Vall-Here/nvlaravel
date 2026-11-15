<?php

declare(strict_types=1);

namespace App\Handlers\Infrastructure;

use App\Services\StarterContext;

final class InstallCiCd
{
    public function handle(StarterContext $context): void
    {
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
    - uses: actions/checkout@v4
    - uses: shivammathur/setup-php@v2
      with:
        php-version: '8.2'
        extensions: mbstring, xml, ctype, iconv, intl, pdo_sqlite, bcmath
    - name: Copy .env
      run: php -r "file_exists('.env') || copy('.env.example', '.env');"
    - name: Configure SQLite for CI
      run: |
        touch database/database.sqlite
        echo "DB_CONNECTION=sqlite" >> .env
    - name: Install Dependencies
      run: composer install -q --no-ansi --no-interaction --no-scripts --no-progress --prefer-dist
    - name: Generate key
      run: php artisan key:generate
    - name: Directory Permissions
      run: chmod -R 777 storage bootstrap/cache
    - name: Run Migrations
      run: php artisan migrate --force
    - name: Run Tests
      run: vendor/bin/pest
YAML;

        $context->files()->put('.github/workflows/tests.yml', $stub);
        $context->log("GitHub Actions CI/CD Pipeline configured for Tests.");
    }
}
