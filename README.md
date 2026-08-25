# nvlaravel

Composable project scaffolding CLI for Laravel applications. Enables multi-layered stack combinations with automatic dependency resolution and atomic rollback.

## Supported Configuration Layers

nvlaravel scaffolds projects based on composable options across the following domains. All options can be combined freely.

### Rendering

| Option | Package |
| --- | --- |
| Blade Templating Engine | Laravel built-in |
| Inertia.js (with SSR support) | `inertiajs/inertia-laravel` |

### Reactivity

| Option | Package |
| --- | --- |
| Livewire v3 (navigate, polls, uploads, lazy loading, validation, events) | `livewire/livewire` |
| Livewire Flux | `livewire/flux` |
| Livewire Power Grid Tables | `power-components/livewire-powergrid` |
| Alpine.js (with plugins: persist, intersect, focus, collapse, mask, anchor) | npm `alpinejs` |
| HTMX (with alpine-morph extension) | npm `htmx.org` |
| Turbo | npm `@hotwired/turbo` |

### JavaScript Frameworks

| Option | Package |
| --- | --- |
| React (TypeScript, latest) | npm `react` |
| Vue.js (TypeScript, latest) | npm `vue` |

### JavaScript Utilities

| Utility | Package |
| --- | --- |
| Axios | npm `axios` |
| Lodash | npm `lodash` |
| Day.js | npm `dayjs` |
| Zod | npm `zod` |
| Alpine AJAX | npm `@imacrayon/alpine-ajax` |

### CSS Frameworks

| Option | Package |
| --- | --- |
| Tailwind CSS v4 (with forms, typography, container queries, dark mode) | npm `tailwindcss` |
| Tailwind CSS v3 (PostCSS + tailwind.config.js) | npm `tailwindcss@3` |
| Bootstrap | npm `bootstrap` |

### UI Libraries

| Option | Package |
| --- | --- |
| Flowbite (with datepicker, modal, dropdown, tooltip plugins) | npm `flowbite` |
| Shadcn UI | npm `shadcn-ui` |
| DaisyUI | npm `daisyui` |
| Preline UI | npm `preline` |
| Headless UI | npm `@headlessui/vue` or `@headlessui/react` |

### UI Components

| Component | Package |
| --- | --- |
| Charts (Chart.js) | npm `chart.js` |
| Rich Text Editor (Tiptap) | npm `@tiptap/vue-3` |
| Datepicker (Flatpickr) | npm `flatpickr` |
| Sortable (SortableJS) | npm `sortablejs` |
| Maps (Mapbox) | npm `mapbox-gl` |
| File Upload (Drag & Drop) | Built-in Livewire |
| Data Tables (Power Grid) | `power-components/livewire-powergrid` |
| Select (Tom Select) | npm `tom-select` |
| Toast Notifications (SweetAlert2) | npm `sweetalert2` |

### Icons & Notifications

| Option | Package |
| --- | --- |
| Heroicons (with Blade Icons integration) | `blade-ui-kit/blade-heroicons` |
| Toast Notifications (Notyf) | npm `notyf` |

### Authentication

| Option | Package |
| --- | --- |
| Laravel Breeze (UI scaffolding) | `laravel/breeze` |
| Laravel Fortify (headless auth with login, register, password reset, email verification, 2FA) | `laravel/fortify` |
| Laravel Socialite (OAuth: Google, GitHub) | `laravel/socialite` |
| Magic Link login | Configurable |
| API Auth via Sanctum (SPA + token) | `laravel/sanctum` |
| API Auth via Passport | `laravel/passport` |

### Authorization

| Option | Package |
| --- | --- |
| Spatie Permission (Roles & Permissions, Teams support) | `spatie/laravel-permission` |
| Laravel Policies | Built-in |

### Multi-tenancy & User Management

| Option | Package |
| --- | --- |
| Multi-tenancy (single database strategy) | `stancl/tenancy` |
| User Impersonation | `lab404/laravel-impersonate` |

### API

| Option | Notes |
| --- | --- |
| REST API with versioning (v1 prefix) | Built-in routing |
| Pagination, filtering, sorting | Built-in |
| API Resources (JSON response wrappers) | Built-in |
| Rate limiting (60 req/min default) | Built-in |
| API Documentation (Scramble, Swagger UI) | `dedoc/scramble` |
| Query Builder | `spatie/laravel-query-builder` |

### Realtime

| Option | Package |
| --- | --- |
| Laravel Reverb (WebSocket server) | `laravel/reverb` |
| Laravel Echo (frontend client) | npm `laravel-echo` |

### Database

| Option | Notes |
| --- | --- |
| PostgreSQL / MySQL / SQLite | Configurable |
| Eloquent ORM | Built-in |
| ULID primary keys | Built-in |
| Soft deletes, foreign keys, indexes, transactions | Built-in |
| Database seeding with Faker (locale: id_ID) | Built-in |
| Database backup (scheduled daily to S3) | `spatie/laravel-backup` |

### Infrastructure

| Option | Package / Notes |
| --- | --- |
| Cache (Redis) | Built-in |
| Queue (Redis + Horizon monitor) | `laravel/horizon` |
| File storage (Local, S3, Public) | Built-in |
| Full-text search (Meilisearch + Scout) | `laravel/scout` + `meilisearch/meilisearch-php` |
| Mail (SMTP + Markdown templates, Resend provider) | Built-in |
| SMS (Twilio) | Configurable |
| CDN (CloudFront) | Configurable |
| Deployment (Forge, GitHub Actions, Docker/Sail) | Configurable |

### Observability

| Option | Package |
| --- | --- |
| Logging (daily driver, JSON format) | Built-in |
| Debugbar | `barryvdh/laravel-debugbar` |
| Telescope | `laravel/telescope` |
| Ray (Spatie) | `spatie/ray` |
| Error tracking (Sentry) | `sentry/sentry-laravel` |
| Metrics (Prometheus) | Configurable |
| Distributed tracing (OpenTelemetry) | Configurable |
| Uptime monitoring (Oh Dear) | Configurable |

### Packages

| Package | Composer |
| --- | --- |
| Spatie Media Library (conversions, responsive images) | `spatie/laravel-medialibrary` |
| Spatie Activity Log | `spatie/laravel-activitylog` |
| Spatie Settings | `spatie/laravel-settings` |
| Spatie Query Builder | `spatie/laravel-query-builder` |
| Spatie Sluggable | `spatie/laravel-sluggable` |
| Spatie Translatable | `spatie/laravel-translatable` |
| Spatie Sitemap | `spatie/laravel-sitemap` |
| Spatie Schedule Monitor | `spatie/laravel-schedule-monitor` |
| Spatie Model States | `spatie/laravel-model-states` |
| Spatie Laravel Health | `spatie/laravel-health` |
| Spatie Backup | `spatie/laravel-backup` |
| SEO Tools | `artesaos/seotools` |
| Payments (Midtrans, Xendit, Stripe/Cashier) | `laravel/cashier` |
| Notifications (Mail, Database, Slack, WhatsApp, FCM) | `netflie/whatsapp-cloud-api` |
| PDF Generation (DomPDF) | `barryvdh/laravel-dompdf` |
| Image Processing | `intervention/image` |
| Excel Import/Export | `maatwebsite/excel` |
| Geocoding | `geocoder-php/geocoder` |
| Idempotency / Fractal | `spatie/laravel-fractal` |

### Testing & Code Quality

| Option | Package |
| --- | --- |
| Pest PHP (Unit, Feature, API tests) | `pestphp/pest` |
| Browser testing (Laravel Dusk) | `laravel/dusk` |
| Code coverage (min. 70%) | Built-in |
| Static analysis (PHPStan level 5 + Larastan) | `nunomaduro/larastan` |
| Code formatting (Laravel Pint) | `laravel/pint` |
| Refactoring (Rector) | `rector/rector` |

### Code Architecture

| Option | Notes |
| --- | --- |
| Modular domain structure | `app/Services`, `app/Actions`, `app/Data`, `app/Queries`, `app/Repositories` |
| Service, Action, DTO, Query, Policy, Event patterns | Built-in |

---

## Installation

Install globally via Composer:

```bash
composer global require Vall-Here/nvlaravel
```

Ensure your global Composer bin directory is in your system `PATH`.

## Usage

Create a new Laravel project using the interactive wizard:

```bash
nvlaravel new my-app
```

### Using Presets

```bash
nvlaravel new my-app --preset=classic
```

Available presets:

| Preset Key | Stack |
| --- | --- |
| `classic` | Blade + Livewire v3 + Alpine.js + Tailwind CSS + Vite |
| `inertia-react` | Inertia.js + React + TypeScript + Tailwind CSS + Vite |
| `inertia-vue` | Inertia.js + Vue.js + TypeScript + Tailwind CSS + Vite |
| `htmx-stack` | Blade + HTMX + Alpine.js + Tailwind CSS + Vite |

### CLI Options

| Flag | Description | Values |
| --- | --- | --- |
| `--preset` | Pre-configured stack preset | `classic`, `inertia-react`, `inertia-vue`, `htmx-stack` |
| `--laravel-version` | Target Laravel version | `latest`, `12`, `11` |
| `--tailwind-version` | Target Tailwind CSS version | `v4`, `v3` |

## Technical Architecture

The core engine follows a 4-layer execution pipeline:

1. **Blueprint (`App\Configuration\Blueprint`)**: Registry loading technology definitions, dependencies, and conflict matrices from `config/generator.php`.
2. **RuntimeConfig (`App\Configuration\RuntimeConfig`)**: State container holding user selections.
3. **Resolver (`App\Dependency\Resolver`)**: Dependency resolution using recursive topological sorting and circular dependency detection.
4. **ExecutionPlan (`App\Planning\ExecutionPlan`)**: Sequence generator ordering task handlers.
5. **Executor (`App\Engine\Executor`)**: Handler pipeline running composer, npm, and stub transformations.

### Safety Mechanisms

- **EnvironmentDoctor (`App\Services\EnvironmentDoctor`)**: Pre-check for PHP, Composer, and NPM binaries and minimum versions before any execution.
- **RollbackManager (`App\Services\RollbackManager`)**: Atomic rollback deleting partially created directories upon any failure during scaffolding.

## Development & Testing

Run unit tests with Pest:

```bash
./vendor/bin/pest
```

Compile standalone Phar executable:

```bash
php -d phar.readonly=0 build_phar.php
```

## License

MIT License.
