<?php
// migrate.php
// Usage:
//   php migrate.php make create_users_table
//   php migrate.php migrate
//   php migrate.php rollback
//   php migrate.php status

declare(strict_types=1);
require_once __DIR__ . '/../vendor/autoload.php';

use Library\Framework\Core\Env;
use Library\Framework\Database\QueryBuilder;

$migrationsDir = __DIR__ . '/../database/migrations';

// ---------- use user's Env parser ----------
function parseEnvFile($path)
{
    // expects your Env class to be available/autoloaded
    $envParser = new Env($path);
    return $envParser;
}

function createDatabaseIfNotExists($env): void
{
    $dbName = $env->get('DB_DATABASE');
    $dbUser = $env->get('DB_USERNAME');
    $dbPass = $env->get('DB_PASSWORD');

    // optional host/port keys; fallback to sensible defaults used in your DSN
    $host = $env->get('DB_HOST') ?: 'db';
    $port = $env->get('DB_PORT') ?: '5432';

    // connect to the maintenance DB 'postgres'
    $maintenanceDsn = "pgsql:host={$host};port={$port};dbname=postgres";

    try {
        $pdo = new PDO($maintenanceDsn, $dbUser, $dbPass, [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_EMULATE_PREPARES => false,
        ]);
    } catch (Exception $e) {
        fwrite(STDERR, "Unable to connect to Postgres maintenance DB: " . $e->getMessage() . PHP_EOL);
        exit(1);
    }

    // check existence
    try {
        $stmt = $pdo->prepare('SELECT 1 FROM pg_database WHERE datname = :name');
        $stmt->execute([':name' => $dbName]);
        $exists = (bool) $stmt->fetchColumn();
    } catch (Exception $e) {
        fwrite(STDERR, "Failed to query pg_database: " . $e->getMessage() . PHP_EOL);
        exit(1);
    }

    if ($exists) {
        echo "Database '{$dbName}' already exists.\n";
        return;
    }

    // create db (quoted to allow mixed-case or special chars)
    try {
        // you can optionally set OWNER/ENCODING here, e.g. "CREATE DATABASE \"{$dbName}\" WITH OWNER {$dbUser} ENCODING 'UTF8'"
        $pdo->exec('CREATE DATABASE "' . str_replace('"', '""', $dbName) . '"');
        echo "Database '{$dbName}' created successfully.\n";
    } catch (Exception $e) {
        fwrite(STDERR, "Failed to create database '{$dbName}': " . $e->getMessage() . PHP_EOL);
        exit(1);
    }
}

$env = parseEnvFile(__DIR__ . '/../.env');

createDatabaseIfNotExists($env);

$dsn = 'pgsql:host=db;port=5432;dbname=' . $env->get('DB_DATABASE');
$user = $env->get('DB_USERNAME');
$pass = $env->get('DB_PASSWORD');
// ------------------------------------------------

if (!is_dir($migrationsDir)) {
    if (!mkdir($migrationsDir, 0755, true)) {
        fwrite(STDERR, "Unable to create migrations directory: $migrationsDir\n");
        exit(1);
    }
}

// --- PDO connection used only for tracking migrations ---
try {
    $pdo = new PDO($dsn, $user, $pass, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_EMULATE_PREPARES => false,
    ]);
} catch (Exception $e) {
    fwrite(STDERR, "DB connection failed: " . $e->getMessage() . PHP_EOL);
    exit(1);
}

QueryBuilder::init($pdo);

function ensureMigrationsTable(PDO $pdo): void
{
    $sql = <<<SQL
CREATE TABLE IF NOT EXISTS migrations (
  id SERIAL PRIMARY KEY,
  migration VARCHAR(255) UNIQUE NOT NULL,
  batch INTEGER NOT NULL,
  migrated_at TIMESTAMPTZ NOT NULL DEFAULT now()
);
SQL;
    $pdo->exec($sql);
}

function listMigrationFiles(string $dir): array
{
    $files = glob(rtrim($dir, '/') . '/*.php') ?: [];
    // ignore other helper files by convention (none expected in migrations dir)
    sort($files, SORT_STRING);
    return array_values($files);
}

function migrationBasenameFromFile(string $path): string
{
    return basename($path, '.php'); // e.g. 20251125123456_create_users_table
}

function classNameFromBasename(string $basename): string
{
    $safe = preg_replace('/[^A-Za-z0-9_]/', '_', $basename);
    // migration classes sit in namespace Database\Migrations
    return "\\Database\\Migrations\\Migration_{$safe}";
}

function currentMaxBatch(PDO $pdo): int
{
    $stmt = $pdo->query("SELECT COALESCE(MAX(batch), 0) as mb FROM migrations");
    $r = $stmt->fetch(PDO::FETCH_ASSOC);
    return intval($r['mb'] ?? 0);
}

function appliedMigrations(PDO $pdo): array
{
    $stmt = $pdo->query("SELECT migration FROM migrations");
    $rows = $stmt->fetchAll(PDO::FETCH_COLUMN, 0);
    return array_flip($rows ?: []);
}

// ---------------- Commands ----------------
function cmd_make(array $argv, string $migrationsDir): void
{
    $name = $argv[2] ?? '';
    if (trim($name) === '') {
        echo "Usage: php migrate.php make migration_name (use underscores, e.g. create_users_table)\n";
        exit(1);
    }
    $name = preg_replace('/[^a-zA-Z0-9_]/', '_', $name);
    $ts = date('YmdHis');
    $base = "{$ts}_{$name}";
    $file = $migrationsDir . '/' . $base . '.php';
    if (file_exists($file)) {
        echo "Migration already exists: $file\n";
        exit(1);
    }

    $class = "Migration_{$base}";
    $namespace = "Database\\Migrations";

    $content = <<<PHP
<?php
namespace {$namespace};

/**
 * Migration: {$base}
 *
 * Implementations should use your application's static DB/query layer
 * inside up() and down(). This file intentionally does NOT reference
 * any query builder to remain neutral — call into your app's DB as needed.
 */
class {$class} implements \\Library\\Framework\\Database\\Migration
{
    public function up(): void
    {
        // TODO: write SQL or call your app's DB helper to apply this migration.
        // Example (comment-only): // QueryBuilder::raw("CREATE TABLE ...");
    }

    public function down(): void
    {
        // TODO: revert changes made in up()
    }
}
PHP;

    file_put_contents($file, $content);
    echo "Created migration: $file\n";
}

function cmd_status(PDO $pdo, string $migrationsDir): void
{
    ensureMigrationsTable($pdo);
    $applied = appliedMigrations($pdo);
    $files = listMigrationFiles($migrationsDir);
    if (empty($files)) {
        echo "No migrations found in $migrationsDir\n";
        return;
    }
    echo str_pad("Migration", 60) . "  Status\n";
    echo str_repeat('-', 80) . "\n";
    foreach ($files as $f) {
        $name = migrationBasenameFromFile($f);
        $status = isset($applied[$name]) ? 'Applied' : 'Pending';
        echo str_pad($name, 60) . "  $status\n";
    }
}

function cmd_migrate(PDO $pdo, string $migrationsDir): void
{
    ensureMigrationsTable($pdo);
    $files = listMigrationFiles($migrationsDir);
    $applied = appliedMigrations($pdo);

    $toRun = [];
    foreach ($files as $f) {
        $name = migrationBasenameFromFile($f);
        if (!isset($applied[$name]))
            $toRun[] = [$name, $f];
    }
    if (empty($toRun)) {
        echo "Nothing to migrate. All migrations are applied.\n";
        return;
    }
    $batch = currentMaxBatch($pdo) + 1;
    echo "Running " . count($toRun) . " migrations (batch $batch)...\n";

    foreach ($toRun as [$name, $path]) {
        echo "-> Applying $name ... ";
        // load migration class file
        require_once $path;
        $class = classNameFromBasename($name);
        if (!class_exists($class)) {
            fwrite(STDERR, "Expected class '{$class}' not found in $path\n");
            exit(1);
        }
        try {
            // IMPORTANT: we intentionally do not start a transaction here,
            // because your application's static DB layer might use a separate connection.
            // If you want transactions controlled by this script, you can add them later.
            /** @var \\Library\\Framework\\Database\\Migration \$instance */
            $instance = new $class();
            $instance->up();

            $stmt = $pdo->prepare("INSERT INTO migrations (migration, batch) VALUES (:m, :b)");
            $stmt->execute([':m' => $name, ':b' => $batch]);

            echo "OK\n";
        } catch (Exception $e) {
            echo "FAILED\n";
            fwrite(STDERR, "Error applying $name: " . $e->getMessage() . PHP_EOL);
            exit(1);
        }
    }
    echo "Migrations applied successfully.\n";
}

function cmd_rollback(PDO $pdo, string $migrationsDir, bool $complete = false): void
{
    ensureMigrationsTable($pdo);
    $stmt = $pdo->query("SELECT COALESCE(MAX(batch), 0) AS mb FROM migrations");
    $mb = intval($stmt->fetchColumn());
    if ($mb === 0) {
        echo "Nothing to rollback.\n";
        return;
    }
    echo "Rolling back batch $mb ...\n";

    $tows = null;
    if ($complete) {
        $stmt = $pdo->query("SELECT migration FROM migrations ORDER BY id DESC");
        $rows = $stmt->fetchAll(PDO::FETCH_COLUMN, 0);

    } else {
        $stmt = $pdo->prepare("SELECT migration FROM migrations WHERE batch = :b ORDER BY id DESC");
        $stmt->execute([':b' => $mb]);
        $rows = $stmt->fetchAll(PDO::FETCH_COLUMN, 0);
    }

     if (!$rows) {
        
        echo "No migrations found \n";
        return;
    }

    // verify files exist
    foreach ($rows as $name) {
        $path = $migrationsDir . '/' . $name . '.php';
        if (!file_exists($path)) {
            fwrite(STDERR, "Missing migration file for $name: $path\nAborting rollback.\n");
            exit(1);
        }
    }

    foreach ($rows as $name) {
        $path = $migrationsDir . '/' . $name . '.php';
        echo "-> Reverting $name ... ";
        require_once $path;
        $class = classNameFromBasename($name);
        if (!class_exists($class)) {
            fwrite(STDERR, "Expected class '{$class}' not found in $path\n");
            exit(1);
        }
        try {
            $instance = new $class();
            $instance->down();

            // remove tracking row
            $del = $pdo->prepare("DELETE FROM migrations WHERE migration = :m");
            $del->execute([':m' => $name]);

            echo "OK\n";
        } catch (Exception $e) {
            echo "FAILED\n";
            fwrite(STDERR, "Error reverting $name: " . $e->getMessage() . PHP_EOL);
            exit(1);
        }
    }

    if ($complete) {
        echo "All migrations have been reversed.\n";
    } else {
        echo "Rollback of batch $mb completed.\n";
    }
}

// -------- dispatch ----------
$argv0 = $argv[0] ?? 'migrate.php';
$cmd = $argv[1] ?? 'status';

switch ($cmd) {
    case 'make':
        cmd_make($argv, $migrationsDir);
        break;
    case 'migrate':
        cmd_migrate($pdo, $migrationsDir);
        break;
    case 'rollback':
        cmd_rollback($pdo, $migrationsDir);
        break;
    case 'reset':
        cmd_rollback($pdo, $migrationsDir, true);
        break;
    case 'status':
        cmd_status($pdo, $migrationsDir);
        break;
    default:
        echo "Unknown command: $cmd\n";
        echo "Usage: php $argv0 [make|migrate|rollback|status]\n";
        break;
}
