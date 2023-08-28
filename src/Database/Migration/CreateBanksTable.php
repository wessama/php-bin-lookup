<?php

namespace WessamA\BinLookup\Database\Migration;

class CreateBanksTable extends Migration
{
    public function up(): void
    {
        $this->pdo->exec('
            CREATE TABLE banks (
                id INTEGER PRIMARY KEY,
                title TEXT NOT NULL,
                country_code TEXT NOT NULL,
                country TEXT NOT NULL,
                city TEXT,
                bank TEXT,
                branch TEXT,
                address TEXT,
                code TEXT,
                swift_code TEXT,
                logo BLOB
            )
        ');
    }

    public function down(): void
    {
        $this->pdo->exec('DROP TABLE banks');
    }
}
