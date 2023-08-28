#!/usr/bin/env php
<?php

require_once __DIR__ . '/vendor/autoload.php';

use WessamA\BinLookup\ConfigLoader;
use WessamA\BinLookup\Database\SQLiteManager;
use WessamA\BinLookup\Service\BankLogoService;

// Load environment variables
$config = new ConfigLoader;

// Initialize the BankLogoService
try {
    $dbManager = new SQLiteManager($config->getConfig());
} catch (Exception $e) {
    echo 'Error: ' . $e->getMessage() . "\n";
    exit(1);
}

$bankLogoService = new BankLogoService($dbManager, $config->getConfig());

echo 'Enter a BIN: ';
$bin = trim(fgets(STDIN));

if (empty($bin)) {
    echo "Error: BIN cannot be empty.\n";
    exit(1);
}

try {
    $bank = $bankLogoService->fetchAndSaveLogo($bin);

    if ($bank) {
        echo "Successfully fetched and saved the logo for BIN: {$bin}.\n";
    } else {
        echo "Failed to fetch the logo for BIN: {$bin}.\n";
    }
} catch (Exception $e) {
    echo 'Error: ' . $e->getMessage() . "\n";
    exit(1);
}
