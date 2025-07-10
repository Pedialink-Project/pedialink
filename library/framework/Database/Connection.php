<?php

namespace Library\Framework\Database;

use PDO;
use PDOException;
use PDOStatement;

/**
 * Database Connection handler
 * 
 * Creates and establishes connection with the database instance
 * 
 * NOTE: Only supports postgres sql database (for now). This class
 * is not fully complete yet. Will improve later!
 * 
 * COMMENT: Discuss with mani for other database integrations
 * Don't forget to abstract this into a base class if you want to
 * extend this class for other database integrations.
 */
class Connection
{
    /**
     * @var PDO
     */
    protected PDO $pdo;

    /**
     * @param array $config Database config file
     * @throws \PDOException
     */
    public function __construct(array $config)
    {
        $defaultConnection = config('database.default');

        $dsn = sprintf(
            '%s:host=%s;port=%d;dbname=%s',
            $defaultConnection,
            $config[$defaultConnection]['host'],
            $config[$defaultConnection]['port'],
            $config[$defaultConnection]['database']
        );

        try {
            $this->pdo = new PDO(
                $dsn,
                $config[$defaultConnection]['username'],
                $config[$defaultConnection]['password'],
                [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]
            );
        } catch (PDOException $e) {
            // Handle connection error
            throw new PDOException('Database connection failed: ' . $e->getMessage());
        }
    }

    /**
     * Get current PDO instance
     * @return PDO
     */
    public function pdo()
    {
        return $this->pdo;
    }

    /**
     * Execute basic query (sample method)
     * @param string $sql
     * @param array $params
     * @return bool|PDOStatement
     */
    public function query(string $sql, array $params = []): PDOStatement
    {
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute($params);
        return $stmt;
    }
}