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

test('resolver detects conflicts between react and vue', function () {
    $blueprint = Blueprint::fromConfig();
    $runtime = new RuntimeConfig();
    $runtime->setProject('conflict-js-project', '/tmp');

    $runtime->enable('react');
    $runtime->enable('vue');

    $resolver = new Resolver($blueprint);
    
    expect(fn() => $resolver->resolve($runtime))
        ->toThrow(RuntimeException::class, 'Conflict detected');
});

test('resolver ensures database-migrate and git run at the very end', function () {
    $blueprint = Blueprint::fromConfig();
    $runtime = new RuntimeConfig();
    $runtime->setProject('ordering-project', '/tmp');

    $runtime->enable('git');
    $runtime->enable('database-migrate');
    $runtime->enable('blade');

    $resolver = new Resolver($blueprint);
    $resolved = $resolver->resolve($runtime);

    $dbIndex = array_search('database-migrate', $resolved, true);
    $gitIndex = array_search('git', $resolved, true);
    $bladeIndex = array_search('blade', $resolved, true);

    expect($bladeIndex)->toBeLessThan($dbIndex);
    expect($dbIndex)->toBeLessThan($gitIndex);
    expect($gitIndex)->toBe(count($resolved) - 1);
});

test('resolver ensures breeze runs before ui libraries and packages', function () {
    $blueprint = Blueprint::fromConfig();
    $runtime = new RuntimeConfig();
    $runtime->setProject('breeze-ordering', '/tmp');

    $runtime->enable('flowbite');
    $runtime->enable('breeze');
    $runtime->enable('spatie-permission');

    $resolver = new Resolver($blueprint);
    $resolved = $resolver->resolve($runtime);

    $breezeIndex = array_search('breeze', $resolved, true);
    $flowbiteIndex = array_search('flowbite', $resolved, true);
    $spatieIndex = array_search('spatie-permission', $resolved, true);

    expect($breezeIndex)->toBeLessThan($flowbiteIndex);
    expect($breezeIndex)->toBeLessThan($spatieIndex);
});

test('resolver auto resolves vue when inertia is enabled without a js framework', function () {
    $blueprint = Blueprint::fromConfig();
    $runtime = new RuntimeConfig();
    $runtime->setProject('inertia-fallback', '/tmp');

    $runtime->enable('inertia');

    $resolver = new Resolver($blueprint);
    $resolved = $resolver->resolve($runtime);

    expect($resolved)->toContain('inertia');
    expect($resolved)->toContain('vue');
    expect($resolved)->toContain('vite');
});

test('resolver ensures frontend frameworks run before ui libraries and packages regardless of input order', function () {
    $blueprint = Blueprint::fromConfig();
    $runtime = new RuntimeConfig();
    $runtime->setProject('layer-ordering', '/tmp');

    // Enable in deliberately inverted order
    $runtime->enable('flowbite');
    $runtime->enable('reverb');
    $runtime->enable('react');

    $resolver = new Resolver($blueprint);
    $resolved = $resolver->resolve($runtime);

    $reactIndex = array_search('react', $resolved, true);
    $flowbiteIndex = array_search('flowbite', $resolved, true);
    $reverbIndex = array_search('reverb', $resolved, true);

    expect($reactIndex)->toBeLessThan($flowbiteIndex);
    expect($flowbiteIndex)->toBeLessThan($reverbIndex);
});
