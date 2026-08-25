# nvlaravel

Composable project scaffolding CLI for Laravel applications. Enables multi-layered stack combinations (Blade, Livewire, Alpine, HTMX, Inertia, React, Vue, Tailwind CSS, Flowbite, Shadcn) with automatic dependency resolution and atomic rollback.

## Supported Stack & Libraries

nvlaravel provides a composable configuration model allowing selection across multiple stack layers:

| Layer | Technology / Library | Options & Packages |
| --- | --- | --- |
| **Framework Version** | Laravel Framework | `latest` (v13+), `12`, `11` |
| **Server Rendering** | Blade, Inertia.js | `blade`, `inertia` |
| **Reactivity** | Livewire, Alpine.js, HTMX | `livewire` (v3), `alpine`, `htmx` |
| **JS Frameworks** | React, Vue.js | `react`, `vue` |
| **CSS Frameworks** | Tailwind CSS, Bootstrap | `tailwind` (v4 / v3), `bootstrap` |
| **UI Libraries** | Flowbite, Shadcn, DaisyUI | `flowbite`, `shadcn`, `daisyui` |
| **Authentication** | Breeze, Fortify, Socialite | `breeze`, `fortify`, `socialite` |
| **Permissions** | Spatie Permission | `spatie-permission` |
| **Media Management** | Spatie MediaLibrary | `spatie-medialibrary` |
| **Realtime** | Laravel Reverb | `reverb` |
| **Dev & Profiling** | Telescope, Debugbar, Horizon | `telescope`, `debugbar`, `horizon` |
| **Architecture** | Modular Domain Architecture | `modular-architecture` (Domain Services, Actions, DTOs) |

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
- `classic`: Blade + Livewire v3 + Alpine.js + Tailwind CSS + Vite
- `inertia-react`: Inertia.js + React + TypeScript + Tailwind CSS + Vite
- `inertia-vue`: Inertia.js + Vue.js + TypeScript + Tailwind CSS + Vite
- `htmx-stack`: Blade + HTMX + Alpine.js + Tailwind CSS + Vite

### CLI Options

| Flag | Description | Values |
| --- | --- | --- |
| `--preset` | Pre-configured stack preset | `classic`, `inertia-react`, `inertia-vue`, `htmx-stack` |
| `--laravel-version` | Target Laravel version | `latest`, `12`, `11` |
| `--tailwind-version` | Target Tailwind version | `v4`, `v3` |

## Technical Architecture

The core engine follows a 4-layer execution pipeline:

1. **Blueprint (`App\Configuration\Blueprint`)**: Registry loading technology definitions, dependencies, and conflict matrices from `config/generator.php`.
2. **RuntimeConfig (`App\Configuration\RuntimeConfig`)**: State container holding user selections.
3. **Resolver (`App\Dependency\Resolver`)**: Dependency resolution using recursive topological sorting and circular dependency detection.
4. **ExecutionPlan (`App\Planning\ExecutionPlan`)**: Sequence generator ordering task handlers.
5. **Executor (`App\Engine\Executor`)**: Handler pipeline running composer, npm, and stub transformations.

### Safety Mechanisms

- **EnvironmentDoctor (`App\Services\EnvironmentDoctor`)**: Pre-check for PHP, Composer, and NPM binaries and minimum versions.
- **RollbackManager (`App\Services\RollbackManager`)**: Atomic rollback deleting partially created directories upon any failure.

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
