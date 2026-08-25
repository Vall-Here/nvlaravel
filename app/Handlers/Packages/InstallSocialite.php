<?php

declare(strict_types=1);

namespace App\Handlers\Packages;

use App\Services\StarterContext;

final class InstallSocialite
{
    public function handle(StarterContext $context): void
    {
        $context->composer()->require(['laravel/socialite']);

        $context->files()->modify('config/services.php', function (string $content) {
            if (!str_contains($content, "'google' =>")) {
                $serviceStubs = <<<PHP

    'google' => [
        'client_id' => env('GOOGLE_CLIENT_ID'),
        'client_secret' => env('GOOGLE_CLIENT_SECRET'),
        'redirect' => env('GOOGLE_REDIRECT_URI', '/auth/google/callback'),
    ],

    'github' => [
        'client_id' => env('GITHUB_CLIENT_ID'),
        'client_secret' => env('GITHUB_CLIENT_SECRET'),
        'redirect' => env('GITHUB_REDIRECT_URI', '/auth/github/callback'),
    ],
PHP;
                return preg_replace('/return \[/', "return [" . $serviceStubs, $content, 1);
            }
            return $content;
        });

        $context->log("Laravel Socialite installed and config/services.php updated with Google/GitHub providers.");
    }
}
