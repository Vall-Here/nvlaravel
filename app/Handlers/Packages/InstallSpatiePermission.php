<?php

declare(strict_types=1);

namespace App\Handlers\Packages;

use App\Services\StarterContext;

final class InstallSpatiePermission
{
    public function handle(StarterContext $context): void
    {
        $context->composer()->require(['spatie/laravel-permission']);
        $context->composer()->publish('Spatie\Permission\PermissionServiceProvider');
        
        $context->files()->modify('app/Models/User.php', function (string $content) {
            if (!str_contains($content, 'HasRoles')) {
                $content = str_replace(
                    'use Illuminate\Foundation\Auth\User as Authenticatable;',
                    "use Illuminate\Foundation\Auth\User as Authenticatable;\nuse Spatie\Permission\Traits\HasRoles;",
                    $content
                );
                
                if (preg_match('/use\s+([^;]*HasFactory[^;]*);/', $content, $matches)) {
                    $content = str_replace($matches[0], rtrim($matches[0], ';') . ', HasRoles;', $content);
                } else {
                    $content = preg_replace('/(class\s+User\s+extends\s+Authenticatable[^{]*\{)/', "$1\n    use HasRoles;\n", $content);
                }
            }
            return $content;
        });

        $context->log("Spatie Permission installed, config published, and HasRoles trait added to User model.");
    }
}
