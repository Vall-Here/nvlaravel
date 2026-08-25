<?php

declare(strict_types=1);

namespace App\Handlers\Frontend;

use App\Services\StarterContext;

final class InstallReact
{
    public function handle(StarterContext $context): void
    {
        $context->npm()->install(['react', 'react-dom', '@vitejs/plugin-react']);
        $context->log("React and Vite React plugin installed.");
    }
}
