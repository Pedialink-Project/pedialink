<?php

namespace Library\Framework\Core;
use Library\Framework\Database\Connection;
use Library\Framework\Database\QueryBuilder;
use PDO;

abstract class Model
{
    /** @var PDO */
    protected static $pdo;

    /**
     * Name of the table that corresponds with the
     * model
     * @var string
     */
    protected static string $table;

    /**
     * Name of primary key column. Default is 'id'
     * @var string
     */
    protected static string $primaryKey = 'id';

    /**
     * Attributes (column names) fetched from the
     * database
     * @var array
     */
    protected array $attributes = [];

    /**
     * Fillable attributes for mass assignments.
     * 
     * Note for CS 28 members: Only mention columns
     * that can be viewed on frontend
     * @var array
     */
    protected array $fillable = [];

    /**
     * Initialize the shared PDO instance
     * 
     * NOTE: This method will be initialized in bootstrap/app.php
     * 
     * @param \Library\Framework\Database\Connection $connection
     * @return void
     */
    public static function init(Connection $connection): void
    {
        static::$pdo = $connection->pdo();
    }

    /**
     * Create a new query builder for the model
     * @return QueryBuilder
     */
    public static function query(): QueryBuilder
    {
        return new QueryBuilder(
            static::getTable(), static::class
        );
    }

    /**
     * Hydrate values from the corresponding data tables to this model.
     * 
     * Note: This method bypasses the fillable attribute and should only
     * be used internally (hopefully within query builder class). if
     * anyone have doubts about this method, ask in the whatsapp group!
     * 
     * @param array $data
     * @return Model
     */
    public function hydrate(array $data): static
    {
        $this->attributes = $data;
        return $this;
    }

    /**
     * Find model instance by the primary key (id)
     * @param mixed $id
     * @return object|null
     */
    public static function find($id): ?static
    {
        return static::query()
            ->where(static::$primaryKey, '=', $id)
            ->first();
    }

    /**
     * Get all instances of the model
     * @return object[]
     */
    public static function all(): array
    {
        return static::query()->get();
    }

    /**
     * Fill values from corresponding data tables to this model and vice
     * versa.
     * 
     * Note: This method is more recommended to use since it is safe and does
     * not expose data not included in fillable attribute of the particular
     * model.
     * 
     * @param array $data
     * @return Model
     */
    public function fill(array $data): static
    {
        foreach ($this->fillable as $column) {
            if (array_key_exists($column, $data)) {
                $this->attributes[$column] = $data[$column];
            }
        }
        return $this;
    }

    /**
     * Save changes to the model in the table
     * 
     * NOTE: This method needs to be further tested.
     * Behavior of this method can be unpredictable.
     * 
     * @return int primary key of the inserted row
     */
    public function save(): int
    {
        $primaryKey = static::$primaryKey;

        // If primary key value present, decide update vs insert by checking existence
        if (isset($this->attributes[$primaryKey])) {
            $id = $this->attributes[$primaryKey];

            // check if record exists
            $existing = static::query()
                ->where($primaryKey, '=', $id)
                ->first();

            if ($existing) {
                // existing -> update (don't try to insert)
                // remove the pk from update payload
                unset($this->attributes[$primaryKey]);
                $affected = static::query()
                    ->where($primaryKey, '=', $id)
                    ->update($this->attributes);

                return (int)$id;
            }

            // PK is provided but row does not exist -> treat as insert including the supplied pk
            $newId = static::query()->insert($this->attributes);

            // insert() for Postgres should return the inserted id (see QueryBuilder)
            // If insert returns 0 (unknown), fall back to the supplied id
            $this->attributes[$primaryKey] = $newId ?: $id;
            return (int)$this->attributes[$primaryKey];
        }

        // No primary key provided - normal insert
        $newId = static::query()->insert($this->attributes);
        $this->attributes[$primaryKey] = $newId;
        return (int)$newId;
    }

    /**
     * Delete model instance from the table
     * @return bool
     */
    public function delete(): bool
    {
        if (!isset($this->attributes[static::$primaryKey])) {
            return false;
        }
        return static::query()
            ->where(static::$primaryKey, '=', $this->attributes[static::$primaryKey])
            ->delete();
    }

    /**
     * Gets table name of the model (if mentioned)
     * 
     * If no default table name is set up a pattern
     * guessing logic to guess the table name.
     * 
     * Note for CS 28 members: Use the convention for table
     * creation. Further details about this convention
     * will be explained in group after we agree 
     * on naming conventions!
     *  
     * @return string
     */
    protected static function getTable(): string
    {
        if (static::$table) {
            return static::$table;
        }
        // Guess table name from class name.
        // Ex: App\Models\User => users
        // Ex: App\Models\TestUser => test_users
        $class = basename(str_replace('\\', '/', static::class));
        return strtolower(preg_replace('/(?<!^)[A-Z]/', '_$0', $class)) . 's';
    }

    /**
     * Magic method that allows you to dynamically
     * access inaccessible property from the object.
     * 
     * For cs 28 members: https://stackoverflow.com/questions/4713680/php-get-and-set-magic-methods
     * 
     * @param mixed $key
     * @return int|mixed|null
     */
    public function __get($key)
    {
        return $this->attributes[$key] ?? null;
    }

    /**
     * Magic method that allows you to dynamically set
     * inaccessible property from the object
     * 
     * For cs 28 members: https://stackoverflow.com/questions/4713680/php-get-and-set-magic-methods
     * 
     * @param mixed $key
     * @param mixed $value
     * @return void
     */
    public function __set($key, $value)
    {
        $this->attributes[$key] = $value;
    }
}