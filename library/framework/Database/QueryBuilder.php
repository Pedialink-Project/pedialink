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
    protected static PDO $pdo;

    /**
     * Name of the table
     * @var string
     */
    protected string|null $table;

    /**
     * Name of the model which corresponds
     * with the query builder
     * @var string
     */
    protected string|null $modelClass;

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

    protected ?int $limit = null;
    protected ?int $offset = null;
    protected array $orderBys = [];
    protected array $joins = [];
    protected array $selects = [];

    public function __construct(?string $table = null, ?string $modelClass = null)
    {
        $this->table = $table;
        $this->modelClass = $modelClass;
    }

    /**
     * Initialize the query builder class
     * to be used globally
     * 
     * @param Connection $connection
     * @return void
     */
    public static function init(Connection|PDO $connection)
    {
        if ($connection instanceof Connection) {
            static::$pdo = $connection->pdo();
        } elseif ($connection instanceof PDO) {
            static::$pdo = $connection;
        } else {
            throw new \InvalidArgumentException('Argument must be Connection or PDO');
        }
    }

    /**
     * Add a JOIN clause
     * @param string $table Table to join
     * @param string $first First column (e.g., 'appointments.doctor_id')
     * @param string $operator Comparison operator (=, <, >, etc.)
     * @param string $second Second column (e.g., 'doctors.id')
     * @param string $type Type of join (INNER, LEFT, RIGHT)
     * @return $this
     */
    public function join(string $table, string $first, string $operator, string $second, string $type = 'INNER'): static
    {
        $allowed = ['=', '<', '>', '<=', '>=', '<>', '!='];
        if (!in_array(strtoupper($operator), $allowed, true)) {
            throw new PDOException("Invalid operator: {$operator}");
        }
        
        $type = strtoupper($type);
        if (!in_array($type, ['INNER', 'LEFT', 'RIGHT'], true)) {
            $type = 'INNER';
        }
        
        $this->joins[] = "{$type} JOIN {$table} ON {$first} {$operator} {$second}";
        return $this;
    }

    /**
     * Add a LEFT JOIN clause
     * @param string $table
     * @param string $first
     * @param string $operator
     * @param string $second
     * @return $this
     */
    public function leftJoin(string $table, string $first, string $operator, string $second): static
    {
        return $this->join($table, $first, $operator, $second, 'LEFT');
    }

    /**
     * Specify columns to select (useful with joins to avoid ambiguity)
     * @param string|array $columns
     * @return $this
     */
    public function select(string|array $columns): static
    {
        if (is_string($columns)) {
            $this->selects[] = $columns;
        } else {
            $this->selects = array_merge($this->selects, $columns);
        }
        return $this;
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
        $allowed = ['=', '<', '>', '<=', '>=', '<>', '!=', 'LIKE', 'ILIKE'];

        // Check if operator is allowed
        if (!in_array(strtoupper($operator), $allowed, true)) {
            throw new PDOException("Invalid operator: {$operator}");
        }

        // Sanitize column name for placeholder (replace dots with underscores)
        $placeholderName = str_replace('.', '_', $column);
        $placeholder = ':' . $placeholderName . count($this->bindings);
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

        // Sanitize column name for placeholder (replace dots with underscores)
        $placeholderName = str_replace('.', '_', $column);
        $placeholders = [];
        foreach ($values as $index => $value) {
            $key = ":{$placeholderName}_in{$index}";
            $placeholders[] = $key;
            $this->bindings[$key] = $value;
        }

        $this->wheres[] = "{$column} IN (" . implode(',', $placeholders) . ")";
        return $this;
    }

    /**
     * Add a WHERE NOT IN condition
     *
     * @param string $column
     * @param array $values
     * @return QueryBuilder
     */
    public function whereNotIn(string $column, array $values): static
    {
        if (empty($values)) {
            // No values to exclude → return all rows (no condition needed)
            return $this;
        }

        // Sanitize column name for placeholder (replace dots with underscores)
        $placeholderName = str_replace('.', '_', $column);
        $placeholders = [];
        foreach ($values as $index => $value) {
            $key = ":{$placeholderName}_notin{$index}";
            $placeholders[] = $key;
            $this->bindings[$key] = $value;
        }

        $this->wheres[] = "{$column} NOT IN (" . implode(',', $placeholders) . ")";
        return $this;
    }

    /**
     * Add a WHERE column IS NULL condition
     * @param string $column
     * @return QueryBuilder
     */
    public function whereNull(string $column): static
    {
        $this->wheres[] = "{$column} IS NULL";
        return $this;
    }

    /**
     * Add a WHERE column IS NOT NULL condition
     * @param string $column
     * @return QueryBuilder
     */
    public function whereNotNull(string $column): static
    {
        $this->wheres[] = "{$column} IS NOT NULL";
        return $this;
    }



    /**
     * Retrieve array of model instances after
     * executing the built query
     * @return object[]
     */
    public function get(): array
    {
        $selectCols = $this->selects ? implode(', ', $this->selects) : "{$this->table}.*";
        $sql = "SELECT {$selectCols} FROM {$this->table}";
        
        if ($this->joins) {
            $sql .= ' ' . implode(' ', $this->joins);
        }
        
        if ($this->wheres) {
            $sql .= ' WHERE ' . implode(' AND ', $this->wheres);
        }

        if ($this->orderBys) {
            $sql .= ' ORDER BY ' . implode(', ', $this->orderBys);
        }

        if ($this->limit !== null) {
            $sql .= " LIMIT {$this->limit}";
        }

        if ($this->offset !== null) {
            $sql .= " OFFSET {$this->offset}";
        }

        $stmt = static::$pdo->prepare($sql);
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
        $selectCols = $this->selects ? implode(', ', $this->selects) : "{$this->table}.*";
        $sql = "SELECT {$selectCols} FROM {$this->table}";

        if ($this->joins) {
            $sql .= ' ' . implode(' ', $this->joins);
        }

        if ($this->wheres) {
            $sql .= ' WHERE ' . implode(' AND ', $this->wheres);
        }

        if ($this->orderBys) {
            $sql .= ' ORDER BY ' . implode(', ', $this->orderBys);
        }

        $sql .= ' LIMIT 1';

        $stmt = static::$pdo->prepare($sql);
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

        $stmt = static::$pdo->prepare($sql);
        $stmt->execute($this->bindings);

        return (int) static::$pdo->lastInsertId();
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

        $stmt = static::$pdo->prepare($sql);

        foreach ($this->bindings as $ph => $val) {
            // detect booleans explicitly
            if (is_bool($val)) {
                $this->bindings[$ph] = $val ? 1 : 0;
            }
        }

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

        $stmt = static::$pdo->prepare($sql);
        return $stmt->execute($this->bindings);
    }

    /**
     * Raw query execution with prepared statements
     * @param string $sql
     * @param array $params
     * @return bool|\PDOStatement
     */
    public static function raw(string $sql, array $params = []): \PDOStatement
    {
        $stmt = static::$pdo->prepare($sql);
        $stmt->execute($params);
        return $stmt;
    }

    /**
     * Add ORDER BY clause
     * @param string $column
     * @param string $direction 'ASC'|'DESC'
     * @return $this
     */
    public function orderBy(string $column, string $direction = 'ASC'): static
    {
        $dir = strtoupper($direction) === 'DESC' ? 'DESC' : 'ASC';
        $this->orderBys[] = "{$column} {$dir}";
        return $this;
    }

    /**
     * Set LIMIT value
     * @param int $limit
     * @return $this
     */
    public function limit(int $limit): static
    {
        $this->limit = max(0, $limit);
        return $this;
    }

    /**
     * Set OFFSET value
     * @param int $offset
     * @return $this
     */
    public function offset(int $offset): static
    {
        $this->offset = max(0, $offset);
        return $this;
    }

    public function paginate(int $perPage = 15, ?int $page = null): Paginator
    {
        // determine current page from argument or _GET
        $page = $page ?? (isset($_GET['page']) ? (int) $_GET['page'] : 1);
        $page = max(1, (int) $page);
        $perPage = max(1, (int) $perPage);
        $offset = ($page - 1) * $perPage;

        // 1) Count query
        $countSql = "SELECT COUNT(*) AS total FROM {$this->table}";
        if ($this->joins) {
            $countSql .= ' ' . implode(' ', $this->joins);
        }
        if ($this->wheres) {
            $countSql .= ' WHERE ' . implode(' AND ', $this->wheres);
        }

        $countStmt = static::$pdo->prepare($countSql);
        $countStmt->execute($this->bindings);
        $countRow = $countStmt->fetch(PDO::FETCH_ASSOC);
        $total = $countRow ? (int) $countRow['total'] : 0;

        if ($total === 0) {
            // return empty paginator
            return new Paginator([], $total, $perPage, $page);
        }

        // 2) Select query with ordering + limit/offset (use separate binding array to avoid mutating internal bindings)
        $selectCols = $this->selects ? implode(', ', $this->selects) : "{$this->table}.*";
        $sql = "SELECT {$selectCols} FROM {$this->table}";
        if ($this->joins) {
            $sql .= ' ' . implode(' ', $this->joins);
        }
        if ($this->wheres) {
            $sql .= ' WHERE ' . implode(' AND ', $this->wheres);
        }
        if ($this->orderBys) {
            $sql .= ' ORDER BY ' . implode(', ', $this->orderBys);
        }

        // create unique placeholder names to avoid collisions and to support named bindings (consistent with your existing pattern)
        $limitKey = ':__limit';
        $offsetKey = ':__offset';

        $sql .= " LIMIT {$limitKey} OFFSET {$offsetKey}";

        // clone bindings and add pagination bindings
        $bindings = $this->bindings;
        $bindings[$limitKey] = $perPage;
        $bindings[$offsetKey] = $offset;

        $stmt = static::$pdo->prepare($sql);
        $stmt->execute($bindings);

        $results = [];
        while ($row = $stmt->fetch(\PDO::FETCH_ASSOC)) {
            $model = new $this->modelClass;
            $model->hydrate($row);
            $results[] = $model;
        }

        return new Paginator($results, $total, $perPage, $page);
    }


    /**
     * Get a single column from the results as an array
     * @param string $column
     * @return array
     */
    public function pluck(string $column): array
    {
        $sql = "SELECT {$column} FROM {$this->table}";
        if ($this->joins) {
            $sql .= ' ' . implode(' ', $this->joins);
        }
        if ($this->wheres) {
            $sql .= ' WHERE ' . implode(' AND ', $this->wheres);
        }

        $stmt = static::$pdo->prepare($sql);
        $stmt->execute($this->bindings);

        $results = [];
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            // Handle aliased columns (e.g., "doctors.name AS doctor_name")
            $colName = str_contains($column, ' AS ') 
                ? trim(substr($column, strpos($column, ' AS ') + 4))
                : (str_contains($column, '.') ? substr($column, strpos($column, '.') + 1) : $column);
            $results[] = $row[$colName] ?? array_values($row)[0] ?? null;
        }

        return $results;
    }


    /**
     * Raw get method for fetching data with sql.
     * 
     * @param string $sql
     * @param array $params
     * @return array
     */
    public static function rawGet(string $sql, array $params = []): array
    {
        $stmt = static::raw($sql, $params);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Raw get method that returns data as models
     * instead of normal array form
     * 
     * @param string $sql
     * @param array $params
     * @return object[]
     */
    public function rawGetAsModels(string $sql, array $params = []): array
    {
        $stmt = static::raw($sql, $params);
        $results = [];
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $model = new $this->modelClass;
            $model->hydrate($row);
            $results[] = $model;
        }
        return $results;
    }

    /**
     * Raw execution methods for non select statements
     * @param string $sql
     * @param array $params
     * @return int Returns number of rows modified.
     */
    public static function rawExec(string $sql, array $params = []): int
    {
        $stmt = static::raw($sql, $params);
        return $stmt->rowCount();
    }
}
