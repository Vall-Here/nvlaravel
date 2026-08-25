<?php

declare(strict_types=1);

namespace App\Handlers\UI;

use App\Services\StarterContext;

final class InstallFlowbite
{
    public function handle(StarterContext $context): void
    {
        $context->npm()->install(['flowbite', 'flowbite-plugin']);

        $context->files()->modify('resources/css/app.css', function (string $content) {
            if (!str_contains($content, 'flowbite')) {
                return $content . "\n@import \"flowbite\";\n";
            }
            return $content;
        });

        $context->log("Flowbite UI installed and imported.");
    }
}
