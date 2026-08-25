<?php

declare(strict_types=1);

namespace App\Commands;

use App\Configuration\Blueprint;
use App\Configuration\RuntimeConfig;
use App\Dependency\Resolver;
use App\Engine\Executor;
use App\Planning\ExecutionPlan;
use App\Services\ComposerManager;
use App\Services\EnvironmentDoctor;
use App\Services\FileModifier;
use App\Services\NpmManager;
use App\Services\StarterContext;
use Illuminate\Support\Facades\File;
use LaravelZero\Framework\Commands\Command;
use function Laravel\Prompts\text;
use function Laravel\Prompts\select;
use function Laravel\Prompts\multiselect;
use function Laravel\Prompts\confirm;
use function Laravel\Prompts\info;
use function Laravel\Prompts\note;
use function Laravel\Prompts\warning;
use function Laravel\Prompts\error;
use function Laravel\Prompts\spin;
use function Laravel\Prompts\table;

final class NewProjectCommand extends Command
{
    protected $signature = 'new {name?} {--preset=} {--laravel-version=} {--tailwind-version=}';

    protected $description = 'Scaffold a new composable Laravel application';

    public function handle(): int
    {
        note(<<<ASCII
                  _                           _ 
  _ ____   _| | __ _ _ __ __ ___   _____| |
 | '_ \ \ / / |/ _` | '__/ _` \ \ / / _ \ |
 | | | \ V /| | (_| | | | (_| |\ V /  __/ |
 |_| |_|\_/ |_|\__,_|_|  \__,_| \_/ \___|_|
                                            
      Composable Laravel Generator
ASCII);

        // Step 1: Environment Health Check
        $doctor = new EnvironmentDoctor();
        $issues = $doctor->checkPrerequisites();
        if (!empty($issues)) {
            error('Environment Health Check Failed:');
            foreach ($issues as $issue) {
                warning($issue);
            }
            return 1;
        }

        $blueprint = Blueprint::fromConfig();

        $projectName = $this->argument('name') ?? text(
            label: 'What is your project name?',
            placeholder: 'my-app',
            required: true,
            validate: fn (string $value) => match (true) {
                strlen($value) < 3 => 'Project name must be at least 3 characters.',
                !preg_match('/^[a-z0-9\-]+$/', $value) => 'Name can only contain lowercase letters, numbers, and dashes.',
                default => null,
            }
        );

        $runtime = new RuntimeConfig();
        $runtime->setProject($projectName, getcwd());

        // Step 2: Validate Target Directory Existence
        if (File::isDirectory($runtime->projectPath()) && count(File::allFiles($runtime->projectPath())) > 0) {
            $overwrite = confirm(
                label: "Target directory [{$projectName}] is not empty. Overwrite and delete existing contents?",
                default: false
            );
            if (!$overwrite) {
                warning('Generation aborted by user.');
                return 0;
            }
            \Illuminate\Support\Facades\File::deleteDirectory($runtime->projectPath());
        }

        $presetArg = $this->option('preset');

        if ($presetArg !== null && $presetArg !== '') {
            $preset = $blueprint->preset($presetArg);
            if (empty($preset)) {
                error("Preset [{$presetArg}] not found.");
                return 1;
            }
            $runtime->enableMany($preset['technologies']);
        } else {
            $mode = select(
                label: 'How would you like to configure your project?',
                options: [
                    'preset' => 'Choose from Pre-configured Presets',
                    'custom' => 'Custom Composable Builder (Select layer by layer)',
                ],
                default: 'preset'
            );

            if ($mode === 'preset') {
                $presetOptions = [];
                foreach ($blueprint->allPresets() as $key => $presetData) {
                    $presetOptions[$key] = $presetData['name'];
                }

                $selectedPresetKey = select(
                    label: 'Select a Preset',
                    options: $presetOptions,
                );

                $preset = $blueprint->preset($selectedPresetKey);
                $runtime->enableMany($preset['technologies']);
            } else {
                $laravelVersion = $this->option('laravel-version') ?? select(
                    label: 'Select Laravel Framework Version',
                    options: [
                        'latest' => 'Laravel Latest (v13+)',
                        '12' => 'Laravel 12',
                        '11' => 'Laravel 11',
                    ],
                    default: 'latest'
                );
                $runtime->setOption('laravel', 'version', $laravelVersion);

                $rendering = select(
                    label: 'Server Rendering Engine',
                    options: [
                        'blade' => 'Blade Templating Engine',
                        'inertia' => 'Inertia.js Monolith Adapter',
                    ],
                    default: 'blade'
                );
                $runtime->enable($rendering);

                $reactivity = multiselect(
                    label: 'Reactivity Layers (Select all that apply)',
                    options: [
                        'livewire' => 'Laravel Livewire (Server Reactivity)',
                        'alpine' => 'Alpine.js (Client State & Interactivity)',
                        'htmx' => 'HTMX (HTML-driven AJAX)',
                    ],
                    default: ['livewire', 'alpine']
                );
                $runtime->enableMany($reactivity);

                $jsFrameworks = multiselect(
                    label: 'JavaScript Component Libraries (Optional)',
                    options: [
                        'react' => 'React',
                        'vue' => 'Vue.js',
                    ],
                    default: []
                );
                $runtime->enableMany($jsFrameworks);

                $cssFramework = select(
                    label: 'CSS Framework',
                    options: [
                        'tailwind' => 'Tailwind CSS',
                        'bootstrap' => 'Bootstrap CSS',
                    ],
                    default: 'tailwind'
                );
                $runtime->enable($cssFramework);

                if ($cssFramework === 'tailwind') {
                    $tailwindVersion = $this->option('tailwind-version') ?? select(
                        label: 'Select Tailwind CSS Version',
                        options: [
                            'v4' => 'Tailwind CSS v4 (@tailwindcss/vite)',
                            'v3' => 'Tailwind CSS v3 (PostCSS + tailwind.config.js)',
                        ],
                        default: 'v4'
                    );
                    $runtime->setOption('tailwind', 'version', $tailwindVersion);

                    $uiLibraries = multiselect(
                        label: 'Tailwind UI Component Libraries (Select all that apply)',
                        options: [
                            'flowbite' => 'Flowbite UI',
                            'shadcn' => 'Shadcn UI',
                            'daisyui' => 'DaisyUI',
                        ],
                        default: []
                    );
                    $runtime->enableMany($uiLibraries);
                }

                $packages = multiselect(
                    label: 'Backend & Infrastructure Packages (Select all that apply)',
                    options: [
                        'breeze' => 'Laravel Breeze (Auth UI Scaffolding)',
                        'fortify' => 'Laravel Fortify (Headless Auth)',
                        'socialite' => 'Laravel Socialite (Google & GitHub OAuth)',
                        'spatie-permission' => 'Spatie Permission (Roles & Permissions)',
                        'spatie-medialibrary' => 'Spatie MediaLibrary (File Management)',
                        'reverb' => 'Laravel Reverb (Realtime WebSockets)',
                        'telescope' => 'Laravel Telescope (Dev Debug Assistant)',
                        'debugbar' => 'Barryvdh Debugbar (Execution Profiler)',
                        'horizon' => 'Laravel Horizon (Redis Queue Monitor)',
                        'modular-architecture' => 'Modular Domain Folders (Services, Actions, DTOs)',
                    ],
                    default: ['modular-architecture']
                );
                $runtime->enableMany($packages);
            }
        }

        $resolver = new Resolver($blueprint);
        
        try {
            $resolvedIds = spin(
                fn () => $resolver->resolve($runtime),
                'Resolving dependencies and calculating execution plan...'
            );
        } catch (\Exception $e) {
            error($e->getMessage());
            return 1;
        }

        $plan = new ExecutionPlan($runtime, $blueprint, $resolvedIds);

        $this->displayPlanPreview($plan, $blueprint);

        if (!$this->option('no-interaction')) {
            $shouldProceed = confirm(
                label: 'Proceed with project generation?',
                default: true
            );

            if (!$shouldProceed) {
                warning('Generation cancelled by user.');
                return 0;
            }
        }

        $context = new StarterContext(
            $runtime,
            new ComposerManager(),
            new NpmManager(),
            new FileModifier(),
            $this->output
        );

        $executor = new Executor($context);
        $rollbackManager = new \App\Services\RollbackManager($runtime->projectPath());
        
        try {
            spin(
                fn() => $executor->execute($plan),
                'Scaffolding your project...'
            );
        } catch (\Throwable $e) {
            error('Execution failed: ' . $e->getMessage());
            warning('Rolling back changes and cleaning up incomplete project directory...');
            $rollbackManager->rollback();
            info('Rollback complete. Workspace is clean.');
            return 1;
        }

        note("✨ Project [{$projectName}] successfully generated!");
        info("Location: {$runtime->projectPath()}");
        info("Run: cd {$projectName} && php artisan serve");

        return 0;
    }

    private function displayPlanPreview(ExecutionPlan $plan, Blueprint $blueprint): void
    {
        $selectedTechRows = array_map(
            fn($id) => [$blueprint->name($id)], 
            $plan->userSelectedIds()
        );
        table(['Selected Technologies'], $selectedTechRows);

        $autoResolved = $plan->autoResolvedIds();
        if (!empty($autoResolved)) {
            $autoResolvedRows = array_map(
                fn($id) => [$blueprint->name($id)], 
                $autoResolved
            );
            table(['Auto-Resolved Dependencies'], $autoResolvedRows);
        }

        $executionRows = [];
        $step = 1;
        foreach ($plan->resolvedIds() as $id) {
            $executionRows[] = [$step, "Install & configure " . $blueprint->name($id)];
            $step++;
        }
        table(['Step', 'Execution Plan'], $executionRows);
    }
}
