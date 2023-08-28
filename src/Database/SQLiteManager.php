<?php

namespace WessamA\BinLookup\Database;

use Exception;
use PDO;

class SQLiteManager
{
    private PDO $pdo;

    /**
     * SQLiteManager constructor.
     *
     * @throws Exception
     */
    public function __construct(array $config)
    {
        $dbPath = $config['db_path'];

        if (! $dbPath) {
            throw new Exception('Environment variable BIN_LOOKUP_DB_PATH is not set.');
        }

        $this->pdo = new PDO('sqlite:' . $dbPath);
    }

    /**
     * Fetch a single record from the database.
     */
    public function fetchOne(string $query, array $params = []): array|bool
    {
        $stmt = $this->pdo->prepare($query);
        $stmt->execute($params);

        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    /**
     * Fetch multiple records from the database.
     */
    public function fetchAll(string $table): array|bool
    {
        $stmt = $this->pdo->prepare("SELECT * FROM {$table}");
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Find a record by its ID.
     */
    public function find(string $table, int $id): array|bool
    {
        $query = "SELECT * FROM {$table} WHERE id = :id";
        $stmt = $this->pdo->prepare($query);
        $stmt->execute([':id' => $id]);

        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    /**
     * Insert a new record into the database and return the inserted row.
     */
    public function insert(string $table, array $data): ?array
    {
        $columns = implode(', ', array_keys($data));
        $placeholders = ':' . implode(', :', array_keys($data));
        $query = "INSERT INTO {$table} ({$columns}) VALUES ({$placeholders})";
        $stmt = $this->pdo->prepare($query);

        if ($stmt->execute($data)) {
            $lastInsertId = $this->pdo->lastInsertId();

            return $this->find($table, $lastInsertId);
        }

        return null;
    }

    /**
     * Update records in the database.
     */
    public function update(string $table, array $data, array $conditions): bool
    {
        $setPart = implode(', ', array_map(fn ($col) => "{$col} = :{$col}", array_keys($data)));
        $conditionPart = implode(' AND ', array_map(fn ($col) => "{$col} = :{$col}", array_keys($conditions)));
        $query = "UPDATE {$table} SET {$setPart} WHERE {$conditionPart}";
        $stmt = $this->pdo->prepare($query);

        return $stmt->execute(array_merge($data, $conditions));
    }

    /**
     * Delete records from the database.
     */
    public function delete(string $table, array $conditions): bool
    {
        $conditionPart = implode(' AND ', array_map(fn ($col) => "{$col} = :{$col}", array_keys($conditions)));
        $query = "DELETE FROM {$table} WHERE {$conditionPart}";
        $stmt = $this->pdo->prepare($query);

        return $stmt->execute($conditions);
    }

    /**
     * Begin a transaction.
     */
    public function beginTransaction(): bool
    {
        return $this->pdo->beginTransaction();
    }

    /**
     * Commit a transaction.
     */
    public function commit(): bool
    {
        return $this->pdo->commit();
    }

    /**
     * Rollback a transaction.
     */
    public function rollback(): bool
    {
        return $this->pdo->rollBack();
    }

    public function getPDO(): PDO
    {
        return $this->pdo;
    }
}
