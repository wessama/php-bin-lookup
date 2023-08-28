#!/usr/bin/env php
<?php

require 'vendor/autoload.php';

use WessamA\BinLookup\ConfigLoader;
use WessamA\BinLookup\Database\Migration\MigrationRunner;
use WessamA\BinLookup\Database\SQLiteManager;

// Load environment variables
$config = new ConfigLoader;

// Initialize the BankLogoService
try {
    $dbManager = new SQLiteManager($config->getConfig());
} catch (Exception $e) {
    echo 'Error: ' . $e->getMessage() . "\n";
    exit(1);
}

$migrationRunner = new MigrationRunner($dbManager);

// Check CLI arguments
$command = $argv[1] ?? null;

switch ($command) {
    case 'run':
        $migrationRunner->run();
        echo "Migrations run successfully.\n";
        break;
    case 'rollback':
        $migrationRunner->rollback();
        echo "Migrations rolled back successfully.\n";
        break;
    default:
        echo "Usage: php migrate.php [run|rollback]\n";
        break;
}
