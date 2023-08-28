<?php

namespace WessamA\BinLookup\Database\Migration;

use WessamA\BinLookup\Database\SQLiteManager;

class MigrationRunner
{
    private SQLiteManager $dbManager;

    public function __construct(SQLiteManager $dbManager)
    {
        $this->dbManager = $dbManager;
    }

    public function run(): void
    {
        $migrations = [
            new CreateBanksTable($this->dbManager->getPDO()),
            new CreateBinCodesTable($this->dbManager->getPDO()),
            new CreateJobsTable($this->dbManager->getPDO()),
            // Add other migrations here as needed
        ];

        foreach ($migrations as $migration) {
            $migration->up();
        }
    }

    public function rollback(): void
    {
        $migrations = [
            new CreateBanksTable($this->dbManager->getPDO()),
            new CreateBinCodesTable($this->dbManager->getPDO()),
            new CreateJobsTable($this->dbManager->getPDO()),
            // Add other migrations here as needed
        ];

        // Reverse the migrations to run the down methods in the opposite order
        foreach (array_reverse($migrations) as $migration) {
            $migration->down();
        }
    }
}
