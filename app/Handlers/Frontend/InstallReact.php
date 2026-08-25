<?php

declare(strict_types=1);

namespace App\Handlers\Frontend;

use App\Services\StarterContext;

final class InstallReact
{
    public function handle(StarterContext $context): void
    {
        $context->npm()->install(['react', 'react-dom', '@vitejs/plugin-react']);
        
        if ($context->runtime()->isEnabled('breeze')) {
            $context->log("React configuration skipped (managed by Laravel Breeze).");
            return;
        }

        $context->files()->move('resources/js/app.js', 'resources/js/app.jsx');

        if ($context->runtime()->isEnabled('inertia')) {
            $context->npm()->install(['@inertiajs/react']);
            $stub = <<<JS
import './bootstrap';
import '../css/app.css';
import { createRoot } from 'react-dom/client';
import { createInertiaApp } from '@inertiajs/react';
import { resolvePageComponent } from 'laravel-vite-plugin/inertia-helpers';

const appName = import.meta.env.VITE_APP_NAME || 'Laravel';

createInertiaApp({
    title: (title) => `\${title} - \${appName}`,
    resolve: (name) => resolvePageComponent(`./Pages/\${name}.jsx`, import.meta.glob('./Pages/**/*.jsx')),
    setup({ el, App, props }) {
        const root = createRoot(el);
        root.render(<App {...props} />);
    },
    progress: {
        color: '#4B5563',
    },
});
JS;
            $context->files()->put('resources/js/app.jsx', $stub);
            // Create a dummy page so resolvePageComponent doesn't fail
            $context->files()->put('resources/js/Pages/Welcome.jsx', <<<JS
import React from 'react';
import { Head } from '@inertiajs/react';

export default function Welcome() {
    return (
        <>
            <Head title="Welcome" />
            <div className="min-h-screen flex items-center justify-center bg-gray-100">
                <h1 className="text-4xl font-bold text-gray-800">Inertia + React Working!</h1>
            </div>
        </>
    );
}
JS);
        } else {
            $stub = <<<JS
import './bootstrap';
import React from 'react';
import { createRoot } from 'react-dom/client';

function App() {
    return <div>React initialized without Inertia.</div>;
}

const root = document.getElementById('app');
if (root) {
    createRoot(root).render(<App />);
}
JS;
            $context->files()->put('resources/js/app.jsx', $stub);
        }

        $context->log("React and Vite React plugin installed.");
    }
}
