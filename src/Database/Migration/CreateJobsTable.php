<?php

namespace WessamA\BinLookup\Database\Migration;

class CreateJobsTable extends Migration
{
    /**
     * Apply the migration.
     */
    public function up(): void
    {
        $this->pdo->exec("
            CREATE TABLE jobs (
                id INTEGER PRIMARY KEY,
                bin_code TEXT NOT NULL,
                status TEXT NOT NULL DEFAULT 'pending',
                attempts INTEGER NOT NULL DEFAULT 0,
                last_error_message TEXT,
                created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
                updated_at DATETIME DEFAULT CURRENT_TIMESTAMP
            )
        ");
    }

    /**
     * Reverse the migration.
     */
    public function down(): void
    {
        $this->pdo->exec('DROP TABLE jobs');
    }
}
