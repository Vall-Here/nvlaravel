<?php

declare(strict_types=1);

namespace App\Handlers\Frontend;

use App\Services\StarterContext;

final class InstallVue
{
    public function handle(StarterContext $context): void
    {
        $context->npm()->install(['vue', '@vitejs/plugin-vue']);
        $context->log("Vue.js and Vite Vue plugin installed.");
    }
}
