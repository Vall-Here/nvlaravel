<?php

use App\Configuration\Blueprint;
use App\Configuration\RuntimeConfig;
use App\Dependency\Resolver;

uses(Tests\TestCase::class);

test('resolver auto resolves dependencies for livewire and flowbite', function () {
    $blueprint = Blueprint::fromConfig();
    $runtime = new RuntimeConfig();
    $runtime->setProject('test-project', '/tmp');

    // User only selects flowbite and livewire
    $runtime->enable('flowbite');
    $runtime->enable('livewire');

    $resolver = new Resolver($blueprint);
    $resolved = $resolver->resolve($runtime);

    // Flowbite requires tailwind & vite
    // Livewire requires blade & vite
    // Everything requires laravel base
    expect($resolved)->toContain('laravel');
    expect($resolved)->toContain('vite');
    expect($resolved)->toContain('blade');
    expect($resolved)->toContain('tailwind');
    expect($resolved)->toContain('livewire');
    expect($resolved)->toContain('flowbite');

    $viteIndex = array_search('vite', $resolved, true);
    $tailwindIndex = array_search('tailwind', $resolved, true);
    $flowbiteIndex = array_search('flowbite', $resolved, true);

    expect($viteIndex)->toBeLessThan($flowbiteIndex);
    expect($tailwindIndex)->toBeLessThan($flowbiteIndex);
});

test('resolver detects conflicts between tailwind and bootstrap', function () {
    $blueprint = Blueprint::fromConfig();
    $runtime = new RuntimeConfig();
    $runtime->setProject('conflict-project', '/tmp');

    $runtime->enable('tailwind');
    $runtime->enable('bootstrap');

    $resolver = new Resolver($blueprint);
    
    expect(fn() => $resolver->resolve($runtime))
        ->toThrow(RuntimeException::class, 'Conflict detected');
});
