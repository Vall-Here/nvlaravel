<?php

declare(strict_types=1);

namespace App\Handlers\Frontend;

use App\Services\StarterContext;

final class InstallVue
{
    public function handle(StarterContext $context): void
    {
        $context->npm()->install(['vue', '@vitejs/plugin-vue']);

        if ($context->runtime()->isEnabled('breeze')) {
            $context->log("Vue configuration skipped (managed by Laravel Breeze).");
            return;
        }

        if ($context->runtime()->isEnabled('inertia')) {
            $context->npm()->install(['@inertiajs/vue3']);
            $stub = <<<JS
import './bootstrap';
import { createApp, h } from 'vue';
import { createInertiaApp } from '@inertiajs/vue3';
import { resolvePageComponent } from 'laravel-vite-plugin/inertia-helpers';

const appName = import.meta.env.VITE_APP_NAME || 'Laravel';

createInertiaApp({
    title: (title) => `\${title} - \${appName}`,
    resolve: (name) => resolvePageComponent(`./Pages/\${name}.vue`, import.meta.glob('./Pages/**/*.vue')),
    setup({ el, App, props, plugin }) {
        return createApp({ render: () => h(App, props) })
            .use(plugin)
            .mount(el);
    },
    progress: {
        color: '#4B5563',
    },
});
JS;
            $context->files()->put('resources/js/app.js', $stub);
            // Create a dummy page
            $context->files()->put('resources/js/Pages/Welcome.vue', <<<VUE
<script setup>
import { Head } from '@inertiajs/vue3';
</script>

<template>
    <Head title="Welcome" />
    <div class="min-h-screen flex items-center justify-center bg-gray-100">
        <h1 class="text-4xl font-bold text-gray-800">Inertia + Vue Working!</h1>
    </div>
</template>
VUE);
        } else {
            $stub = <<<JS
import './bootstrap';
import { createApp, h } from 'vue';

const root = document.getElementById('app');
if (root) {
    createApp({ render: () => h('div', 'Vue initialized without Inertia.') }).mount(root);
}
JS;
            $context->files()->put('resources/js/app.js', $stub);

            // Update welcome.blade.php for standalone Vue + Blade
            $context->files()->modify('resources/views/welcome.blade.php', function (string $content) {
                if (!str_contains($content, 'id="app"')) {
                    $content = preg_replace('/(<body[^>]*>)/', "$1\n        <div id=\"app\"></div>", $content);
                }
                return $content;
            });
        }

        $context->log("Vue.js and Vite Vue plugin installed.");
    }
}
