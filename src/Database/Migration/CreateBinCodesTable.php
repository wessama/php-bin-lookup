<?php

namespace WessamA\BinLookup\Database\Migration;

class CreateBinCodesTable extends Migration
{
    /**
     * Apply the migration.
     */
    public function up(): void
    {
        $this->pdo->exec('
            CREATE TABLE bin_codes (
                id INTEGER PRIMARY KEY,
                bin_code TEXT NOT NULL UNIQUE,
                bank_id INTEGER,
                FOREIGN KEY (bank_id) REFERENCES banks(bank_id)
            )
        ');
    }

    /**
     * Reverse the migration.
     */
    public function down(): void
    {
        $this->pdo->exec('DROP TABLE bin_codes');
    }
}
