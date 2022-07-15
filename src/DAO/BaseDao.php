<?php

namespace App\DAO;

use Doctrine\DBAL\DriverManager;

/**
 * @codeCoverageIgnore
 */
class BaseDao
{
    /** @var \Doctrine\DBAL\Connection  */
    protected $connection;
    protected $dbConfig;

    public function __construct(array $dbConfig)
    {
        $this->dbConfig = $dbConfig['php'];
    }

    /**
     * @codeCoverageIgnore
     */
    protected function connect(): void
    {
        if (!$this->connection) {
            $this->connection = DriverManager::getConnection($this->dbConfig);
        }
    }

    protected function fetchAll(string $sql, array $params = [], $types = []): array
    {
        $this->connect();
        return $this->connection->fetchAllAssociative($sql, $params, $types);
    }

    protected function fetchOne(string $sql, array $params = [], $types = []): string
    {
        $this->connect();
        return $this->connection->fetchOne($sql, $params, $types);
    }

    protected function insert(string $table, array $params = [], array $types = []): int
    {
        $this->connect();
        return $this->connection->insert($table, $params, $types);
    }

    protected function execute(string $sql, array $params = [], array $types = [])
    {
        $this->connect();
        return $this->connection->executeQuery($sql, $params, $types);
    }
}
