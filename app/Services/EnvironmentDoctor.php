<?php

declare(strict_types=1);

namespace App\Services;

use Symfony\Component\Process\Process;

final class EnvironmentDoctor
{
    /**
     * Perform all pre-flight diagnostic checks.
     *
     * @return array<int, array<string, mixed>> Array of diagnostic results
     */
    public function performChecks(): array
    {
        $results = [];

        // 1. PHP Version
        $phpVersion = PHP_VERSION;
        $phpPass = version_compare($phpVersion, '8.2.0', '>=');
        $results[] = [
            'Component' => 'PHP Version',
            'Status'    => $phpPass ? '<fg=green>OK</>' : '<fg=red>FAILED</>',
            'Details'   => $phpPass ? $phpVersion : "Current: $phpVersion (Requires >= 8.2)",
            'Critical'  => true,
            'Passed'    => $phpPass,
        ];

        // 2. Composer
        $composerPass = $this->isBinaryAvailable('composer');
        $results[] = [
            'Component' => 'Composer',
            'Status'    => $composerPass ? '<fg=green>OK</>' : '<fg=red>FAILED</>',
            'Details'   => $composerPass ? 'Available in PATH' : 'Not accessible',
            'Critical'  => true,
            'Passed'    => $composerPass,
        ];

        // 3. Node / NPM
        $npmPass = $this->isBinaryAvailable('npm');
        $results[] = [
            'Component' => 'Node / NPM',
            'Status'    => $npmPass ? '<fg=green>OK</>' : '<fg=red>FAILED</>',
            'Details'   => $npmPass ? 'Available in PATH' : 'Not accessible',
            'Critical'  => true,
            'Passed'    => $npmPass,
        ];

        // 4. Git
        $gitPass = $this->isBinaryAvailable('git');
        $results[] = [
            'Component' => 'Git CLI',
            'Status'    => $gitPass ? '<fg=green>OK</>' : '<fg=yellow>WARNING</>',
            'Details'   => $gitPass ? 'Available in PATH' : 'Not accessible (Git features disabled)',
            'Critical'  => false,
            'Passed'    => true, // Not strictly critical to run the tool, but we'll flag it
        ];

        // 5. Essential PHP Extensions
        $requiredExtensions = ['pdo_sqlite', 'mbstring', 'openssl', 'curl', 'xml', 'bcmath'];
        $missingExtensions = [];
        foreach ($requiredExtensions as $ext) {
            if (!extension_loaded($ext)) {
                $missingExtensions[] = $ext;
            }
        }
        
        $extPass = empty($missingExtensions);
        $results[] = [
            'Component' => 'PHP Extensions',
            'Status'    => $extPass ? '<fg=green>OK</>' : '<fg=red>FAILED</>',
            'Details'   => $extPass ? 'All core extensions loaded' : 'Missing: ' . implode(', ', $missingExtensions),
            'Critical'  => true,
            'Passed'    => $extPass,
        ];

        // 6. Database specific optional extensions
        $mysqlPass = extension_loaded('pdo_mysql');
        $results[] = [
            'Component' => 'MySQL Extension (pdo_mysql)',
            'Status'    => $mysqlPass ? '<fg=green>OK</>' : '<fg=gray>N/A</>',
            'Details'   => $mysqlPass ? 'Loaded' : 'Missing (Only required if using MySQL)',
            'Critical'  => false,
            'Passed'    => true,
        ];
        
        $zipPass = extension_loaded('zip');
        $results[] = [
            'Component' => 'Zip Extension',
            'Status'    => $zipPass ? '<fg=green>OK</>' : '<fg=yellow>WARNING</>',
            'Details'   => $zipPass ? 'Loaded' : 'Missing (Recommended for Composer)',
            'Critical'  => false,
            'Passed'    => true,
        ];

        // 7. Directory Write Permission
        $isWritable = is_writable(getcwd() ?: '.');
        $results[] = [
            'Component' => 'Write Permissions',
            'Status'    => $isWritable ? '<fg=green>OK</>' : '<fg=red>FAILED</>',
            'Details'   => $isWritable ? 'Current directory is writable' : 'Cannot write to current directory',
            'Critical'  => true,
            'Passed'    => $isWritable,
        ];

        // 8. Packagist Internet Reachability
        $hasInternet = false;
        try {
            $ip = gethostbyname('packagist.org');
            $hasInternet = ($ip !== 'packagist.org');
        } catch (\Throwable $e) {
            // Ignore
        }

        $results[] = [
            'Component' => 'Internet Connection',
            'Status'    => $hasInternet ? '<fg=green>OK</>' : '<fg=red>FAILED</>',
            'Details'   => $hasInternet ? 'Can reach packagist.org' : 'Cannot reach package registry',
            'Critical'  => true,
            'Passed'    => $hasInternet,
        ];

        return $results;
    }

    public function isBinaryAvailable(string $binary): bool
    {
        $cmd = (PHP_OS_FAMILY === 'Windows') ? ['where', $binary] : ['which', $binary];
        $process = new Process($cmd);
        $process->run();
        return $process->isSuccessful();
    }
}
