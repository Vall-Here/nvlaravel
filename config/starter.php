<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Project
    |--------------------------------------------------------------------------
    */

    'project' => [

        'type' => 'web',

        'architecture' => 'monolith',

        'php' => [
            'version' => '>=8.2',
        ],

        'laravel' => [
            'version' => 'latest',
        ],

        'package_manager' => 'composer',

        'node' => [
            'package_manager' => 'npm',
        ],
    ],


    /*
    |--------------------------------------------------------------------------
    | Backend
    |--------------------------------------------------------------------------
    */

    'backend' => [

        'routing' => [
            'web' => true,
            'api' => true,
            'console' => true,
        ],

        'rendering' => [

            'blade' => [
                'enabled' => true,
                'components' => true,
                'layouts' => true,
                'partials' => true,
            ],

            'inertia' => [
                'enabled' => false,

                'adapter' => null,

                'ssr' => false,

                'versioning' => true,
            ],
        ],

        'api' => [

            'enabled' => true,

            'style' => 'rest',

            'versioning' => [
                'enabled' => true,
                'prefix' => 'v1',
            ],

            'pagination' => true,

            'filtering' => true,

            'sorting' => true,

            'resources' => true,

            'auth_guard' => [

                'sanctum' => [
                    'enabled' => true,

                    'spa' => true,

                    'tokens' => true,
                ],

                'passport' => [
                    'enabled' => false,
                ],
            ],

            'documentation' => [

                'enabled' => true,

                'driver' => 'scramble',

                'ui' => 'swagger',
            ],

            'rate_limiting' => [

                'enabled' => true,

                'max_attempts' => 60,

                'decay_minutes' => 1,
            ],
        ],

        'auth' => [

            'enabled' => true,

            'driver' => 'fortify',

            'features' => [

                'login' => true,

                'register' => true,

                'password_reset' => true,

                'email_verification' => true,

                'two_factor' => false,

                'social_login' => [

                    'enabled' => false,

                    'driver' => 'socialite',

                    'providers' => [
                        'google',
                        'github',
                    ],
                ],

                'magic_link' => false,
            ],
        ],

        'authorization' => [

            'enabled' => true,

            'driver' => 'spatie',

            'roles' => [
                'admin',
                'manager',
                'user',
            ],

            'permissions' => true,
        ],

        'multitenancy' => [

            'enabled' => false,

            'driver' => 'stancl/tenancy',

            'strategy' => 'single-database',
        ],

        'impersonation' => [

            'enabled' => false,

            'composer' => 'lab404/laravel-impersonate',
        ],
    ],


    /*
    |--------------------------------------------------------------------------
    | Frontend
    |--------------------------------------------------------------------------
    */

    'frontend' => [

        /*
        |--------------------------------------------------------------
        | Server Rendering
        |--------------------------------------------------------------
        */

        'rendering' => [

            'blade' => [

                'enabled' => true,

                'components' => true,

                'anonymous_components' => true,

                'layouts' => true,

                'slots' => true,
            ],


            'inertia' => [

                'enabled' => false,

                'adapter' => null,

                'ssr' => false,

                'progress_bar' => true,

                'shared_props' => true,
            ],
        ],


        /*
        |--------------------------------------------------------------
        | Reactive Technologies
        |--------------------------------------------------------------
        */

        'reactivity' => [

            'livewire' => [

                'enabled' => true,

                'version' => 'latest',

                'features' => [

                    'navigate' => true,

                    'polling' => true,

                    'uploads' => true,

                    'pagination' => true,

                    'lazy_loading' => true,

                    'validation' => true,

                    'events' => true,
                ],

                'packages' => [

                    'livewire_flux' => false,

                    'livewire_forms' => false,

                    'livewire_tables' => 'power-grid',
                ],
            ],


            'alpine' => [

                'enabled' => true,

                'plugins' => [

                    'persist' => true,

                    'intersect' => true,

                    'focus' => true,

                    'collapse' => true,

                    'mask' => false,

                    'anchor' => false,
                ],
            ],


            'htmx' => [

                'enabled' => false,

                'extensions' => [
                    'alpine-morph',
                ],
            ],


            'turbo' => [

                'enabled' => false,
            ],
        ],


        /*
        |--------------------------------------------------------------
        | JavaScript
        |--------------------------------------------------------------
        */

        'javascript' => [

            'runtime' => 'typescript',

            'frameworks' => [

                'react' => [

                    'enabled' => false,

                    'version' => 'latest',

                    'typescript' => true,
                ],


                'vue' => [

                    'enabled' => false,

                    'version' => 'latest',

                    'typescript' => true,
                ],
            ],


            'utilities' => [

                'lodash' => false,

                'axios' => true,

                'dayjs' => false,

                'zod' => false,

                'alpine_ajax' => false,
            ],
        ],


        /*
        |--------------------------------------------------------------
        | CSS
        |--------------------------------------------------------------
        */

        'css' => [

            'framework' => 'tailwind',

            'tailwind' => [

                'enabled' => true,

                'dark_mode' => 'class',

                'forms' => true,

                'typography' => true,

                'aspect_ratio' => false,

                'container_queries' => true,
            ],
        ],


        /*
        |--------------------------------------------------------------
        | UI
        |--------------------------------------------------------------
        */

        'ui' => [

            'primary' => 'flowbite',

            'libraries' => [

                'flowbite' => [

                    'enabled' => true,

                    'version' => 'latest',

                    'plugins' => [
                        'datepicker',
                        'modal',
                        'dropdown',
                        'tooltip',
                    ],
                ],

                'preline' => [
                    'enabled' => false,
                ],

                'daisyui' => [
                    'enabled' => false,
                ],

                'shadcn' => [

                    'enabled' => false,

                    'framework' => null,

                    'components' => [
                        'button',
                        'dialog',
                        'dropdown',
                        'table',
                        'form',
                    ],
                ],

                'headlessui' => [
                    'enabled' => false,
                ],
            ],

            'icons' => [

                'library' => 'heroicons',

                'blade_icons' => true,
            ],

            'notifications_ui' => [

                'toast' => 'notyf',
            ],
        ],


        /*
        |--------------------------------------------------------------
        | Components
        |--------------------------------------------------------------
        */

        'components' => [

            'charts' => [

                'enabled' => false,

                'library' => 'chartjs',
            ],

            'editor' => [

                'enabled' => false,

                'library' => 'tiptap',
            ],

            'datepicker' => [

                'enabled' => false,

                'library' => 'flatpickr',
            ],

            'sortable' => [

                'enabled' => false,

                'library' => 'sortablejs',
            ],

            'maps' => [

                'enabled' => false,

                'provider' => 'mapbox',
            ],

            'file_upload' => [

                'enabled' => true,

                'drag_drop' => true,

                'preview' => true,

                'multiple' => true,
            ],

            'tables' => [

                'enabled' => false,

                'library' => 'power-grid',
            ],

            'select' => [

                'enabled' => false,

                'library' => 'tom-select',
            ],

            'notifications_toast' => [

                'enabled' => false,

                'library' => 'sweetalert2',
            ],
        ],


        /*
        |--------------------------------------------------------------
        | Realtime Frontend
        |--------------------------------------------------------------
        */

        'realtime' => [

            'enabled' => true,

            'echo' => [

                'enabled' => true,

                'broadcaster' => 'reverb',
            ],
        ],


        /*
        |--------------------------------------------------------------
        | Build
        |--------------------------------------------------------------
        */

        'build' => [

            'bundler' => 'vite',

            'hot_reload' => true,

            'source_maps' => true,

            'minify' => true,

            'code_splitting' => true,

            'manifest' => true,
        ],
    ],


    /*
    |--------------------------------------------------------------------------
    | Database
    |--------------------------------------------------------------------------
    */

    'database' => [

        'driver' => 'pgsql',

        'connection' => 'default',

        'orm' => 'eloquent',

        'primary_key' => [

            'type' => 'ulid',

            'auto_generate' => true,
        ],

        'features' => [

            'timestamps' => true,

            'soft_deletes' => true,

            'foreign_keys' => true,

            'indexes' => true,

            'transactions' => true,
        ],

        'seeding' => [

            'factories' => true,

            'faker_locale' => 'id_ID',
        ],

        'backup' => [

            'enabled' => false,

            'composer' => 'spatie/laravel-backup',

            'destination' => 's3',

            'schedule' => 'daily',
        ],
    ],


    /*
    |--------------------------------------------------------------------------
    | Infrastructure
    |--------------------------------------------------------------------------
    */

    'infrastructure' => [

        'cache' => [

            'enabled' => true,

            'driver' => 'redis',
        ],

        'queue' => [

            'enabled' => true,

            'driver' => 'redis',

            'monitor' => 'horizon',
        ],

        'filesystem' => [

            'default' => 's3',

            'disks' => [

                'local' => true,

                's3' => true,

                'public' => true,
            ],
        ],

        'search' => [

            'enabled' => true,

            'driver' => 'meilisearch',

            'scout' => true,
        ],

        'realtime' => [

            'enabled' => true,

            'driver' => 'reverb',
        ],

        'mail' => [

            'driver' => 'smtp',

            'markdown_templates' => true,

            'transactional_provider' => 'resend',
        ],

        'sms' => [

            'enabled' => false,

            'provider' => 'twilio',
        ],

        'cdn' => [

            'enabled' => false,

            'provider' => 'cloudfront',
        ],

        'deployment' => [

            'driver' => 'forge',

            'ci_cd' => 'github-actions',

            'containerized' => false,

            'docker' => [
                'enabled' => false,

                'sail' => true,
            ],
        ],
    ],


    /*
    |--------------------------------------------------------------------------
    | Observability
    |--------------------------------------------------------------------------
    */

    'observability' => [

        'logging' => [

            'enabled' => true,

            'driver' => 'daily',

            'json' => true,
        ],

        'debugging' => [

            'debugbar' => true,

            'telescope' => true,

            'ray' => false,
        ],

        'error_tracking' => [

            'enabled' => true,

            'driver' => 'sentry',
        ],

        'metrics' => [

            'enabled' => true,

            'driver' => 'prometheus',
        ],

        'tracing' => [

            'enabled' => false,

            'driver' => 'opentelemetry',
        ],

        'uptime' => [

            'enabled' => false,

            'provider' => 'oh-dear',
        ],
    ],


    /*
    |--------------------------------------------------------------------------
    | Testing
    |--------------------------------------------------------------------------
    */

    'testing' => [

        'framework' => 'pest',

        'types' => [

            'unit' => true,

            'feature' => true,

            'browser' => false,

            'api' => true,

            'architecture' => false,
        ],

        'browser' => [

            'driver' => 'dusk',
        ],

        'coverage' => [

            'enabled' => true,

            'minimum' => 70,
        ],

        'static_analysis' => [

            'phpstan' => [
                'enabled' => true,

                'level' => 5,
            ],

            'larastan' => true,
        ],

        'formatting' => [

            'pint' => true,

            'rector' => false,
        ],
    ],


    /*
    |--------------------------------------------------------------------------
    | Code Architecture
    |--------------------------------------------------------------------------
    */

    'architecture' => [

        'style' => 'modular',

        'patterns' => [

            'service' => true,

            'action' => true,

            'repository' => false,

            'dto' => true,

            'data' => true,

            'query' => true,

            'policy' => true,

            'event' => true,

        ],

        'directories' => [

            'services' => 'app/Services',

            'actions' => 'app/Actions',

            'data' => 'app/Data',

            'queries' => 'app/Queries',

            'repositories' => 'app/Repositories',

        ],
    ],


    /*
    |--------------------------------------------------------------------------
    | Packages
    |--------------------------------------------------------------------------
    */

    'packages' => [

        'spatie_permission' => [

            'enabled' => true,

            'composer' => 'spatie/laravel-permission',

            'features' => [

                'roles' => true,

                'permissions' => true,

                'teams' => false,
            ],
        ],


        'media_library' => [

            'enabled' => true,

            'composer' => 'spatie/laravel-medialibrary',

            'conversions' => true,

            'responsive_images' => true,
        ],


        'excel' => [

            'enabled' => false,

            'composer' => 'maatwebsite/excel',

            'imports' => true,

            'exports' => true,
        ],


        'activity_log' => [

            'enabled' => true,

            'composer' => 'spatie/laravel-activitylog',

            'model_events' => true,

            'user_tracking' => true,
        ],


        'settings' => [

            'enabled' => false,

            'composer' => 'spatie/laravel-settings',
        ],


        'query_builder' => [

            'enabled' => true,

            'composer' => 'spatie/laravel-query-builder',
        ],


        'sluggable' => [

            'enabled' => true,

            'composer' => 'spatie/laravel-sluggable',
        ],


        'translatable' => [

            'enabled' => false,

            'composer' => 'spatie/laravel-translatable',

            'locales' => [
                'id',
                'en',
            ],
        ],


        'sitemap' => [

            'enabled' => false,

            'composer' => 'spatie/laravel-sitemap',
        ],


        'schedule_monitor' => [

            'enabled' => false,

            'composer' => 'spatie/laravel-schedule-monitor',
        ],


        'seo' => [

            'enabled' => false,

            'composer' => 'artesaos/seotools',
        ],


        'payments' => [

            'enabled' => false,

            'driver' => 'midtrans',

            'providers' => [

                'midtrans' => false,

                'xendit' => false,

                'stripe' => [
                    'enabled' => false,

                    'composer' => 'laravel/cashier',
                ],
            ],
        ],


        'notifications' => [

            'channels' => [

                'mail' => true,

                'database' => true,

                'broadcast' => false,

                'slack' => false,

                'whatsapp' => [
                    'enabled' => false,

                    'composer' => 'netflie/whatsapp-cloud-api',
                ],

                'fcm' => false,
            ],
        ],


        'pdf' => [

            'enabled' => false,

            'composer' => 'barryvdh/laravel-dompdf',
        ],


        'image_processing' => [

            'enabled' => true,

            'composer' => 'intervention/image',
        ],


        'geocoding' => [

            'enabled' => false,

            'composer' => 'geocoder-php/geocoder',
        ],


        'health' => [

            'enabled' => false,

            'composer' => 'spatie/laravel-health',
        ],


        'backup' => [

            'enabled' => false,

            'composer' => 'spatie/laravel-backup',
        ],


        'model_states' => [

            'enabled' => false,

            'composer' => 'spatie/laravel-model-states',
        ],


        'idempotency' => [

            'enabled' => false,

            'composer' => 'spatie/laravel-fractal',
        ],
    ],
];