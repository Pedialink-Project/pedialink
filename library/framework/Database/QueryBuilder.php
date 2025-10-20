<?php

namespace Library\Framework\Database;
use PDO;
use PDOException;

/**
 * A query builder class to build queries.
 * Can be used with a model or as a standalone instance.
 * 
 * NOTE for cs 28 members: You can either use the minimal ORM
 * setup or write raw sql using the rawGet and rawExec methods!
 * 
 * I recommend using the minimal ORM methods for simple stuff
 * when working with models and only use raw methods for
 * complex queries.
 */
class QueryBuilder
{
    /**
     * PDO instance
     * @var PDO
     */
    protected PDO $pdo;

    /**
     * Name of the table
     * @var string
     */
    protected string $table;

    /**
     * Name of the model which corresponds
     * with the query builder
     * @var string
     */
    protected string $modelClass;

    /**
     * Array to store all where() calls in the
     * query builder instance
     * @var array
     */
    protected array $wheres = [];

    /**
     * Array to store all the values that correspond
     * to placeholders in the sql query
     * @var array
     */
    protected array $bindings = [];

    public function __construct(PDO $pdo, string $table, string $modelClass)
    {
        $this->pdo = $pdo;
        $this->table = $table;
        $this->modelClass = $modelClass;
    }

    /**
     * Shorthand method similar to `where` syntax in sql.
     * Can be chained in query builder instance.
     * @param string $column
     * @param string $operator
     * @param mixed $value
     * @throws \PDOException
     * @return QueryBuilder
     */
    public function where(string $column, string $operator, $value): static
    {
        // Basic operator whitelist to prevent SQL injection
        $allowed = ['=', '<', '>', '<=', '>=', '<>', '!=', 'LIKE'];

        // Check if operator is allowed
        if (!in_array(strtoupper($operator), $allowed, true)) {
            throw new PDOException("Invalid operator: {$operator}");
        }

        // Use placeholder
        $placeholder = ':' . $column . count($this->bindings);
        $this->wheres[] = "{$column} {$operator} {$placeholder}";
        $this->bindings[$placeholder] = $value;

        return $this;
    }

    /**
 * Add a WHERE IN condition
 * @param string $column
 * @param array $values
 * @return QueryBuilder
 */
public function whereIn(string $column, array $values): static
{
    if (empty($values)) {
        // No values, so condition will never match
        $this->wheres[] = "0 = 1";
        return $this;
    }

    $placeholders = [];
    foreach ($values as $index => $value) {
        $key = ":{$column}_in{$index}";
        $placeholders[] = $key;
        $this->bindings[$key] = $value;
    }

    $this->wheres[] = "{$column} IN (" . implode(',', $placeholders) . ")";
    return $this;
}


    /**
     * Retrieve array of model instances after
     * executing the built query
     * @return object[]
     */
    public function get(): array
    {
        $sql = "SELECT * FROM {$this->table}";
        if ($this->wheres) {
            $sql .= ' WHERE ' . implode(' AND ', $this->wheres);
        }
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute($this->bindings);

        $results = [];
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $model = new $this->modelClass;
            $model->hydrate($row);
            $results[] = $model;
        }

        return $results;
    }

    /**
     * Retrieve the first model (if available)
     * after executing the built query
     * @return object|null
     */
    public function first(): ?object
    {
        $sql = "SELECT * FROM {$this->table}";
        if ($this->wheres) {
            $sql .= ' WHERE ' . implode(' AND ', $this->wheres);
        }
        $sql .= ' LIMIT 1';
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute($this->bindings);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$row) {
            return null;
        }

        $model = new $this->modelClass;
        $model->hydrate($row);
        return $model;
    }

    /**
     * Insert new row in the table
     * @param array $data
     * @return int Returns primary key of the newly inserted row
     */
    public function insert(array $data): int
    {
        $cols = array_keys($data);
        $placeholders = [];

        foreach ($cols as $col) {
            $placeholders[] = ':' . $col;
            $this->bindings[':' . $col] = $data[$col];
        }

        $colsEscaped = array_map(fn($c) => "{$c}", $cols);
        $sql = sprintf(
            "INSERT INTO %s (%s) VALUES (%s)",
            $this->table,
            implode(', ', $colsEscaped),
            implode(', ', $placeholders)
        );

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute($this->bindings);

        return (int) $this->pdo->lastInsertId();
    }

    /**
     * Update the row of a table based on the
     * currently built query
     * @param array $data
     * @return bool Return success status of update query
     */
    public function update(array $data): bool
    {
        $sets = [];
        foreach ($data as $col => $val) {
            $placeholder = ':' . $col . count($this->bindings);
            $sets[] = "{$col} = {$placeholder}";
            $this->bindings[$placeholder] = $val;
        }

        $sql = sprintf(
            "UPDATE %s SET %s",
            $this->table,
            implode(', ', $sets)
        );

        if ($this->wheres) {
            $sql .= ' WHERE ' . implode(' AND ', $this->wheres);
        }

        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute($this->bindings);
    }

    /**
     * Delete rows from the table based
     * on the currently built query
     * @return bool Return success status of delete query
     */
    public function delete(): bool
    {
        $sql = sprintf(
            "DELETE FROM %s" .
            ($this->wheres ? ' WHERE ' . implode(' AND ', $this->wheres) : ''),
            $this->table
        );

        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute($this->bindings);
    }

    /**
     * Raw query execution with prepared statements
     * @param string $sql
     * @param array $params
     * @return bool|\PDOStatement
     */
    public function raw(string $sql, array $params = []): \PDOStatement
    {
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute($params);
        return $stmt;
    }


    /**
 * Get a single column from the results as an array
 * @param string $column
 * @return array
 */
public function pluck(string $column): array
{
    $sql = "SELECT {$column} FROM {$this->table}";
    if ($this->wheres) {
        $sql .= ' WHERE ' . implode(' AND ', $this->wheres);
    }

    $stmt = $this->pdo->prepare($sql);
    $stmt->execute($this->bindings);

    $results = [];
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        $results[] = $row[$column];
    }

    return $results;
}


    /**
     * Raw get method for fetching data with sql.
     * 
     * Note: Can be retrieved as models also!
     * 
     * @param string $sql
     * @param array $params
     * @param bool $asModel
     * @return array
     */
    public function rawGet(string $sql, array $params = [], bool $asModel = false): array
    {
        $stmt = $this->raw($sql, $params);
        if ($asModel) {
            $results = [];
            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                $model = new $this->modelClass;
                $model->hydrate($row);
                $results[] = $model;
            }
            return $results;
        }
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Raw execution methods for non select statements
     * @param string $sql
     * @param array $params
     * @return int Returns number of rows modified.
     */
    public function rawExec(string $sql, array $params = []): int
    {
        $stmt = $this->raw($sql, $params);
        return $stmt->rowCount();
    }
}