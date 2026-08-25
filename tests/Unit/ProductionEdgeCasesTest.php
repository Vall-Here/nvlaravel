<?php

use App\Services\EnvironmentDoctor;
use App\Services\RollbackManager;
use Illuminate\Support\Facades\File;

uses(Tests\TestCase::class);

test('environment doctor checks binary availability', function () {
    $doctor = new EnvironmentDoctor();
    expect($doctor->isBinaryAvailable('php'))->toBeTrue();
});

test('rollback manager deletes project directory on failure', function () {
    $tempDir = base_path('tests/temp_rollback_test');
    File::ensureDirectoryExists($tempDir);
    File::put($tempDir . '/test.txt', 'sample');

    expect(File::isDirectory($tempDir))->toBeTrue();

    $rollback = new RollbackManager($tempDir);
    $rollback->rollback();

    expect(File::isDirectory($tempDir))->toBeFalse();
});
