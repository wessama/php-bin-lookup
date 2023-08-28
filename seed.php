#!/usr/bin/env php
<?php

require 'vendor/autoload.php';

use WessamA\BinLookup\ConfigLoader;
use WessamA\BinLookup\Database\Seeder\BanksTableSeeder;
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

$bankSeeder = new BanksTableSeeder($dbManager);

// Check CLI arguments
$command = $argv[1] ?? null;

switch ($command) {
    case 'run':
        $bankSeeder->seedFromJsonFile();
        echo "Seeding completed successfully.\n";
        break;
    default:
        echo "Usage: php seed.php run\n";
        break;
}
