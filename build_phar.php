<?php

declare(strict_types=1);

$baseDir = __DIR__;
$buildsDir = $baseDir . '/builds';
$tempPhar = $buildsDir . '/nvlaravel.phar';
$finalBinary = $buildsDir . '/nvlaravel';

if (!is_dir($buildsDir)) {
    mkdir($buildsDir, 0755, true);
}

if (file_exists($tempPhar)) {
    @unlink($tempPhar);
}
if (file_exists($finalBinary)) {
    @unlink($finalBinary);
}

echo "Building nvlaravel standalone Phar executable (fast mode)...\n";

$phar = new Phar($tempPhar, 0, 'nvlaravel.phar');
$phar->startBuffering();

// Include app, bootstrap, config, vendor, nvlaravel, composer.json natively in C engine
$regex = '/^' . preg_quote($baseDir, '/') . '\/(app|bootstrap|config|vendor|nvlaravel|composer\.json)(\/.*)?$/i';
$phar->buildFromDirectory($baseDir, $regex);

$stub = <<<'STUB'
#!/usr/bin/env php
<?php
Phar::mapPhar('nvlaravel.phar');
require 'phar://nvlaravel.phar/nvlaravel';
__HALT_COMPILER();
STUB;

$phar->setStub($stub);
$phar->stopBuffering();

rename($tempPhar, $finalBinary);

echo "✨ SUCCESSFULLY BUILT: " . realpath($finalBinary) . "\n";
echo "📦 Size: " . round(filesize($finalBinary) / (1024 * 1024), 2) . " MB\n";
