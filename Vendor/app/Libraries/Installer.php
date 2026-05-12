<?php

declare(strict_types=1);

namespace App\Libraries;

use CodeIgniter\Database\BaseConnection;
use mysqli;
use SQLite3;

/**
 * Web installer: connection test, Database.php patch, SQL import, install flag.
 */
class Installer
{
    /** @var array<string, string> */
    public const DRIVER_LABELS = [
        'MySQLi'  => 'MySQL / MariaDB (MySQLi)',
        'Postgre' => 'PostgreSQL',
        'SQLite3' => 'SQLite 3',
        'SQLSRV'  => 'Microsoft SQL Server (sqlsrv)',
    ];

    /**
     * Base table names in preset SQL (longest first). Prefixed during install import when DBPrefix is set.
     *
     * @var list<string>
     */
    private const PRESET_TABLES = ['tech_stack_items', 'about_us_items', 'services_items', 'products_items', 'values_items', 'contact_items', 'seo_pages', 'site_settings', 'users'];

    /**
     * Preset CMS / core tables created during schema import (logical names, without DB prefix).
     *
     * @return list<string>
     */
    public static function presetTableNames(): array
    {
        return self::PRESET_TABLES;
    }

    public static function normalizeDbPrefix(string $prefix): string
    {
        return preg_replace('/[^a-zA-Z0-9_]/', '', trim($prefix));
    }

    public static function flagPath(): string
    {
        return WRITEPATH . '.installed';
    }

    public static function isInstalled(): bool
    {
        return is_file(self::flagPath());
    }

    /**
     * Active default connection driver (e.g. Postgre, MySQLi). Used by CLI helpers only.
     */
    public static function defaultDatabaseDriver(): string
    {
        $dbConfig  = config(\Config\Database::class);
        $groupName = $dbConfig->defaultGroup;

        return (string) ($dbConfig->{$groupName}['DBDriver'] ?? '');
    }

    /**
     * @param array{hostname?:string,port?:int,username?:string,password?:string,database?:string,schema?:string,DBPrefix?:string} $params
     */
    public static function testConnection(string $driver, array $params): void
    {
        if (! isset(self::DRIVER_LABELS[$driver])) {
            throw new \InvalidArgumentException('Unsupported database driver.');
        }

        try {
            $config = self::buildRuntimeConnectionConfig($driver, $params);
            $db = \Config\Database::connect($config, false);
            $db->initialize();
            $db->query('SELECT 1');
        } catch (\Throwable $e) {
            throw new \RuntimeException($e->getMessage(), (int) $e->getCode(), $e);
        }
    }

    /**
     * @param array{hostname?:string,port?:int,username?:string,password?:string,database?:string,schema?:string,DBPrefix?:string} $params
     */
    public static function writeDatabaseConfig(string $driver, array $params): void
    {
        if (! isset(self::DRIVER_LABELS[$driver])) {
            throw new \InvalidArgumentException('Unsupported database driver.');
        }

        $path = APPPATH . 'Config/Database.php';
        $content = file_get_contents($path);
        if ($content === false) {
            throw new \RuntimeException('Unable to read Config/Database.php.');
        }

        [$start, $end] = self::locateDefaultArraySpan($content);
        $block      = self::renderDefaultArrayDeclaration($driver, $params);
        $newContent = substr($content, 0, $start) . $block . substr($content, $end);

        if (file_put_contents($path, $newContent) === false) {
            throw new \RuntimeException('Unable to write Config/Database.php. Check filesystem permissions.');
        }
    }

    /**
     * Run preset SQL for whatever {@see Config\Database::$default} is configured for.
     *
     * The web installer schema step always calls this (MySQL, PostgreSQL, SQLite, SQL Server).
     * The CLI command `php spark db:import-preset` gates on PostgreSQL only; it still ends up here once allowed.
     */
    public static function importPresetTables(): void
    {
        $dbConfig = config(\Config\Database::class);
        /** @var array<string, mixed> $group */
        $group  = $dbConfig->{$dbConfig->defaultGroup};
        $driver = (string) ($group['DBDriver'] ?? '');

        $dir = APPPATH . 'Database' . DIRECTORY_SEPARATOR . 'Source';
        if (! is_dir($dir)) {
            throw new \RuntimeException('Database Source folder is missing (app/Database/Source).');
        }

        $files = self::presetSqlFiles($driver, $dir);
        if ($files === []) {
            throw new \RuntimeException(
                'No preset SQL files found for driver "' . $driver . '". Add files matching: '
                . self::describePresetSqlPattern($driver)
            );
        }

        sort($files);

        $prefix        = self::normalizeDbPrefix((string) ($group['DBPrefix'] ?? ''));
        $sqlsrvSchema  = (string) ($group['schema'] ?? 'dbo');

        $db = \Config\Database::connect();
        foreach ($files as $file) {
            $sql = file_get_contents($file);
            if ($sql === false || trim($sql) === '') {
                continue;
            }
            $sql = self::applyInstallSqlPrefix($sql, $prefix, $driver, $sqlsrvSchema);
            self::executeSqlScript($db, $driver, $sql, basename($file));
        }
    }

    /**
     * True when any preset table name (including {@see Config\Database::$default.DBPrefix}) already exists.
     * Used to block a second "Import tables" submit without re-running scripts.
     */
    public static function presetTablesAlreadyExist(): bool
    {
        $dbConfig = config(\Config\Database::class);
        /** @var array<string, mixed> $group */
        $group  = $dbConfig->{$dbConfig->defaultGroup};
        $driver = (string) ($group['DBDriver'] ?? '');
        $prefix = self::normalizeDbPrefix((string) ($group['DBPrefix'] ?? ''));

        $db = \Config\Database::connect();

        foreach (self::PRESET_TABLES as $table) {
            $physical = $prefix . $table;
            if (self::physicalPresetTableExists($db, $driver, $group, $physical)) {
                return true;
            }
        }

        return false;
    }

    /**
     * @param array<string, mixed> $group
     */
    private static function physicalPresetTableExists(BaseConnection $db, string $driver, array $group, string $physicalName): bool
    {
        return match ($driver) {
            'MySQLi' => self::mysqlPresetTableExists($db, $physicalName),
            'SQLite3' => self::sqlitePresetTableExists($db, $physicalName),
            'Postgre' => self::postgresPresetTableExists($db, $physicalName),
            'SQLSRV' => self::sqlsrvPresetTableExists($db, $group, $physicalName),
            default => false,
        };
    }

    private static function mysqlPresetTableExists(BaseConnection $db, string $table): bool
    {
        $row = $db->query(
            'SELECT COUNT(*) AS c FROM information_schema.tables WHERE table_schema = DATABASE() AND table_name = ?',
            [$table]
        )->getRow();

        return isset($row->c) && (int) $row->c > 0;
    }

    private static function sqlitePresetTableExists(BaseConnection $db, string $table): bool
    {
        $row = $db->query(
            "SELECT 1 AS x FROM sqlite_master WHERE type = 'table' AND name = ? LIMIT 1",
            [$table]
        )->getRow();

        return $row !== null;
    }

    private static function postgresPresetTableExists(BaseConnection $db, string $table): bool
    {
        $table = strtolower($table);

        $row = $db->query(
            'SELECT EXISTS (
                SELECT FROM information_schema.tables
                WHERE table_schema = current_schema() AND table_name = ?
            ) AS e',
            [$table]
        )->getRow();

        if ($row === null || ! property_exists($row, 'e')) {
            return false;
        }

        $v = $row->e;

        return $v === true || $v === 't' || $v === '1' || $v === 1;
    }

    /**
     * @param array<string, mixed> $group
     */
    private static function sqlsrvPresetTableExists(BaseConnection $db, array $group, string $table): bool
    {
        $schema = trim((string) ($group['schema'] ?? 'dbo'));
        $row    = $db->query(
            'SELECT COUNT(*) AS c FROM INFORMATION_SCHEMA.TABLES WHERE TABLE_SCHEMA = ? AND TABLE_NAME = ?',
            [$schema, $table]
        )->getRow();

        return isset($row->c) && (int) $row->c > 0;
    }

    /**
     * Rewrites preset scripts so created tables match {@see Config\Database::$default.DBPrefix}.
     */
    public static function applyInstallSqlPrefix(string $sql, string $prefix, string $driver, string $sqlsrvSchema = 'dbo'): string
    {
        $prefix = self::normalizeDbPrefix($prefix);
        if ($prefix === '') {
            return $sql;
        }

        return match ($driver) {
            'MySQLi' => self::applyMysqlBacktickPrefix($sql, $prefix),
            'Postgre', 'SQLite3' => self::applyIdentifierWordPrefix($sql, $prefix),
            'SQLSRV' => self::applySqlsrvSchemaPrefix($sql, $prefix, $sqlsrvSchema),
            default => $sql,
        };
    }

    private static function applyMysqlBacktickPrefix(string $sql, string $prefix): string
    {
        foreach (self::PRESET_TABLES as $table) {
            $sql = str_replace('`' . $table . '`', '`' . $prefix . $table . '`', $sql);
        }

        return $sql;
    }

    private static function applyIdentifierWordPrefix(string $sql, string $prefix): string
    {
        foreach (self::PRESET_TABLES as $table) {
            $sql = preg_replace(
                '/(?<![a-zA-Z0-9_])' . preg_quote($table, '/') . '(?![a-zA-Z0-9_])/i',
                $prefix . $table,
                $sql
            ) ?? $sql;
        }

        return $sql;
    }

    private static function applySqlsrvSchemaPrefix(string $sql, string $prefix, string $schema): string
    {
        $schema = trim($schema) !== '' ? trim($schema) : 'dbo';

        foreach (self::PRESET_TABLES as $table) {
            $qualified       = $schema . '.' . $table;
            $qualifiedPrefix = $schema . '.' . $prefix . $table;
            $sql             = str_replace($qualified, $qualifiedPrefix, $sql);
            $sql             = str_replace(
                "N'" . $qualified . "'",
                "N'" . $qualifiedPrefix . "'",
                $sql
            );
        }

        return $sql;
    }

    /**
     * @return list<string>
     */
    public static function presetSqlFiles(string $driver, ?string $dir = null): array
    {
        $dir ??= APPPATH . 'Database' . DIRECTORY_SEPARATOR . 'Source';
        if (! is_dir($dir)) {
            return [];
        }

        $all = glob($dir . DIRECTORY_SEPARATOR . '*.sql');
        if ($all === false) {
            return [];
        }

        return match ($driver) {
            'MySQLi' => array_values(array_filter(
                $all,
                static fn (string $f): bool => ! preg_match('/\.(pgsql|sqlite|sqlsrv)\.sql$/i', basename($f))
            )),
            'Postgre' => array_values(array_filter(
                $all,
                static fn (string $f): bool => (bool) preg_match('/\.pgsql\.sql$/i', basename($f))
            )),
            'SQLite3' => array_values(array_filter(
                $all,
                static fn (string $f): bool => (bool) preg_match('/\.sqlite\.sql$/i', basename($f))
            )),
            'SQLSRV' => array_values(array_filter(
                $all,
                static fn (string $f): bool => (bool) preg_match('/\.sqlsrv\.sql$/i', basename($f))
            )),
            default => [],
        };
    }

    public static function describePresetSqlPattern(string $driver): string
    {
        return match ($driver) {
            'MySQLi' => '*.sql (excluding *.pgsql.sql, *.sqlite.sql, *.sqlsrv.sql)',
            'Postgre' => '*.pgsql.sql',
            'SQLite3' => '*.sqlite.sql',
            'SQLSRV' => '*.sqlsrv.sql',
            default => '*.sql',
        };
    }

    /**
     * @return array{0:int,1:int}
     */
    private static function locateDefaultArraySpan(string $content): array
    {
        $marker = 'public array $default = [';
        $start  = strpos($content, $marker);
        if ($start === false) {
            throw new \RuntimeException('Could not locate $default array in Config/Database.php.');
        }

        $arrayStart = strpos($content, '[', $start);
        if ($arrayStart === false) {
            throw new \RuntimeException('Malformed Config/Database.php.');
        }

        $depth = 0;
        $len   = strlen($content);
        for ($i = $arrayStart; $i < $len; $i++) {
            $ch = $content[$i];
            if ($ch === '[') {
                $depth++;
            } elseif ($ch === ']') {
                $depth--;
                if ($depth === 0) {
                    $end = $i + 1;
                    while ($end < $len && ctype_space($content[$end])) {
                        $end++;
                    }
                    if ($end < $len && $content[$end] === ';') {
                        $end++;
                    }

                    return [$start, $end];
                }
            }
        }

        throw new \RuntimeException('Could not parse Config/Database.php default array.');
    }

    /**
     * @param array{hostname?:string,port?:int,username?:string,password?:string,database?:string,schema?:string} $params
     */
    private static function renderDefaultArrayDeclaration(string $driver, array $params): string
    {
        return match ($driver) {
            'MySQLi' => self::renderMysqlDefault($params),
            'Postgre' => self::renderPostgresDefault($params),
            'SQLite3' => self::renderSqliteDefault($params),
            'SQLSRV' => self::renderSqlsrvDefault($params),
            default => throw new \InvalidArgumentException('Unsupported driver.'),
        };
    }

    private static function renderMysqlDefault(array $params): string
    {
        $h    = self::sq($params['hostname'] ?? '');
        $u    = self::sq($params['username'] ?? '');
        $p    = self::sq($params['password'] ?? '');
        $d    = self::sq($params['database'] ?? '');
        $pref = self::sq(self::normalizeDbPrefix($params['DBPrefix'] ?? ''));
        $port = (int) ($params['port'] ?? 3306);

        return <<<PHP
    public array \$default = [
        'DSN'          => '',
        'hostname'     => '{$h}',
        'username'     => '{$u}',
        'password'     => '{$p}',
        'database'     => '{$d}',
        'DBDriver'     => 'MySQLi',
        'DBPrefix'     => '{$pref}',
        'pConnect'     => false,
        'DBDebug'      => true,
        'charset'      => 'utf8mb4',
        'DBCollat'     => 'utf8mb4_general_ci',
        'swapPre'      => '',
        'encrypt'      => false,
        'compress'     => false,
        'strictOn'     => false,
        'failover'     => [],
        'port'         => {$port},
        'numberNative' => false,
        'foundRows'    => false,
        'dateFormat'   => [
            'date'     => 'Y-m-d',
            'datetime' => 'Y-m-d H:i:s',
            'time'     => 'H:i:s',
        ],
    ];

PHP;
    }

    private static function renderPostgresDefault(array $params): string
    {
        $h      = self::sq($params['hostname'] ?? '');
        $u      = self::sq($params['username'] ?? '');
        $p      = self::sq($params['password'] ?? '');
        $db     = self::sq($params['database'] ?? '');
        $schema = self::sq($params['schema'] ?? 'public');
        $pref   = self::sq(self::normalizeDbPrefix($params['DBPrefix'] ?? ''));
        $port   = (int) ($params['port'] ?? 5432);

        return <<<PHP
    public array \$default = [
        'DSN'        => '',
        'hostname'   => '{$h}',
        'username'   => '{$u}',
        'password'   => '{$p}',
        'database'   => '{$db}',
        'schema'     => '{$schema}',
        'DBDriver'   => 'Postgre',
        'DBPrefix'   => '{$pref}',
        'pConnect'   => false,
        'DBDebug'    => true,
        'charset'    => 'utf8',
        'swapPre'    => '',
        'failover'   => [],
        'port'       => {$port},
        'dateFormat' => [
            'date'     => 'Y-m-d',
            'datetime' => 'Y-m-d H:i:s',
            'time'     => 'H:i:s',
        ],
    ];

PHP;
    }

    private static function renderSqliteDefault(array $params): string
    {
        $path = self::sq($params['database'] ?? '');
        $pref = self::sq(self::normalizeDbPrefix($params['DBPrefix'] ?? ''));

        return <<<PHP
    public array \$default = [
        'DSN'          => '',
        'database'     => '{$path}',
        'DBDriver'     => 'SQLite3',
        'DBPrefix'     => '{$pref}',
        'DBDebug'      => true,
        'swapPre'      => '',
        'failover'     => [],
        'foreignKeys'  => true,
        'busyTimeout'  => 1000,
        'synchronous'  => null,
        'dateFormat'   => [
            'date'     => 'Y-m-d',
            'datetime' => 'Y-m-d H:i:s',
            'time'     => 'H:i:s',
        ],
    ];

PHP;
    }

    private static function renderSqlsrvDefault(array $params): string
    {
        $h      = self::sq($params['hostname'] ?? '');
        $u      = self::sq($params['username'] ?? '');
        $p      = self::sq($params['password'] ?? '');
        $db     = self::sq($params['database'] ?? '');
        $schema = self::sq($params['schema'] ?? 'dbo');
        $pref   = self::sq(self::normalizeDbPrefix($params['DBPrefix'] ?? ''));
        $port   = (int) ($params['port'] ?? 1433);

        return <<<PHP
    public array \$default = [
        'DSN'        => '',
        'hostname'   => '{$h}',
        'username'   => '{$u}',
        'password'   => '{$p}',
        'database'   => '{$db}',
        'schema'     => '{$schema}',
        'DBDriver'   => 'SQLSRV',
        'DBPrefix'   => '{$pref}',
        'pConnect'   => false,
        'DBDebug'    => true,
        'charset'    => 'utf8',
        'swapPre'    => '',
        'encrypt'    => false,
        'failover'   => [],
        'port'       => {$port},
        'dateFormat' => [
            'date'     => 'Y-m-d',
            'datetime' => 'Y-m-d H:i:s',
            'time'     => 'H:i:s',
        ],
    ];

PHP;
    }

    /**
     * Escape for embedding inside single-quoted PHP strings in Config/Database.php.
     */
    private static function sq(string $value): string
    {
        return str_replace(["\\", "'"], ["\\\\", "\\'"], $value);
    }

    /**
     * @param array{hostname?:string,port?:int,username?:string,password?:string,database?:string,schema?:string,DBPrefix?:string} $params
     *
     * @return array<string, mixed>
     */
    private static function buildRuntimeConnectionConfig(string $driver, array $params): array
    {
        $df     = [
            'date'     => 'Y-m-d',
            'datetime' => 'Y-m-d H:i:s',
            'time'     => 'H:i:s',
        ];
        $prefix = self::normalizeDbPrefix($params['DBPrefix'] ?? '');

        return match ($driver) {
            'MySQLi' => [
                'DSN'          => '',
                'hostname'     => $params['hostname'] ?? '',
                'username'     => $params['username'] ?? '',
                'password'     => $params['password'] ?? '',
                'database'     => $params['database'] ?? '',
                'DBDriver'     => 'MySQLi',
                'DBPrefix'     => $prefix,
                'pConnect'     => false,
                'DBDebug'      => true,
                'charset'      => 'utf8mb4',
                'DBCollat'     => 'utf8mb4_general_ci',
                'swapPre'      => '',
                'encrypt'      => false,
                'compress'     => false,
                'strictOn'     => false,
                'failover'     => [],
                'port'         => (int) ($params['port'] ?? 3306),
                'numberNative' => false,
                'foundRows'    => false,
                'dateFormat'   => $df,
            ],
            'Postgre' => [
                'DSN'        => '',
                'hostname'   => $params['hostname'] ?? '',
                'username'   => $params['username'] ?? '',
                'password'   => $params['password'] ?? '',
                'database'   => $params['database'] ?? '',
                'schema'     => $params['schema'] ?? 'public',
                'DBDriver'   => 'Postgre',
                'DBPrefix'   => $prefix,
                'pConnect'   => false,
                'DBDebug'    => true,
                'charset'    => 'utf8',
                'swapPre'    => '',
                'failover'   => [],
                'port'       => (int) ($params['port'] ?? 5432),
                'dateFormat' => $df,
            ],
            'SQLite3' => [
                'DSN'          => '',
                'database'     => $params['database'] ?? '',
                'DBDriver'     => 'SQLite3',
                'DBPrefix'     => $prefix,
                'DBDebug'      => true,
                'swapPre'      => '',
                'failover'     => [],
                'foreignKeys'  => true,
                'busyTimeout'  => 1000,
                'synchronous'  => null,
                'dateFormat'   => $df,
            ],
            'SQLSRV' => [
                'DSN'        => '',
                'hostname'   => $params['hostname'] ?? '',
                'username'   => $params['username'] ?? '',
                'password'   => $params['password'] ?? '',
                'database'   => $params['database'] ?? '',
                'schema'     => $params['schema'] ?? 'dbo',
                'DBDriver'   => 'SQLSRV',
                'DBPrefix'   => $prefix,
                'pConnect'   => false,
                'DBDebug'    => true,
                'charset'    => 'utf8',
                'swapPre'    => '',
                'encrypt'    => false,
                'failover'   => [],
                'port'       => (int) ($params['port'] ?? 1433),
                'dateFormat' => $df,
            ],
            default => throw new \InvalidArgumentException('Unsupported driver.'),
        };
    }

    private static function executeSqlScript(BaseConnection $db, string $driver, string $sql, string $label): void
    {
        match ($driver) {
            'MySQLi' => self::runMysqlMultiQuery($db, $sql, $label),
            'Postgre', 'SQLSRV' => self::runSplitStatements($db, $sql, $label),
            'SQLite3' => self::runSqliteBatch($db, $sql, $label),
            default => throw new \RuntimeException('Unsupported driver for SQL import: ' . $driver),
        };
    }

    private static function runMysqlMultiQuery(BaseConnection $db, string $sql, string $label): void
    {
        $mysqli = $db->connID;
        if (! $mysqli instanceof mysqli) {
            throw new \RuntimeException('MySQLi connection expected for preset import.');
        }

        if (! $mysqli->multi_query($sql)) {
            throw new \RuntimeException($mysqli->error . ' (file: ' . $label . ')');
        }

        do {
            if ($result = $mysqli->store_result()) {
                $result->free();
            }
        } while ($mysqli->more_results() && $mysqli->next_result());
    }

    private static function runSplitStatements(BaseConnection $db, string $sql, string $label): void
    {
        foreach (self::splitSqlStatements($sql) as $stmt) {
            if ($stmt === '') {
                continue;
            }
            try {
                $db->query($stmt);
            } catch (\Throwable $e) {
                throw new \RuntimeException($e->getMessage() . ' (file: ' . $label . ')');
            }
        }
    }

    private static function runSqliteBatch(BaseConnection $db, string $sql, string $label): void
    {
        $conn = $db->connID;
        if (! $conn instanceof SQLite3) {
            throw new \RuntimeException('SQLite3 connection expected for preset import.');
        }

        if ($conn->exec($sql) === false) {
            throw new \RuntimeException($conn->lastErrorMsg() . ' (file: ' . $label . ')');
        }
    }

    /**
     * @return list<string>
     */
    private static function splitSqlStatements(string $sql): array
    {
        $parts = preg_split('/;\s*\R/u', $sql) ?: [];
        $out   = [];
        foreach ($parts as $part) {
            $lines = [];
            foreach (preg_split('/\R/u', trim($part)) ?: [] as $line) {
                $trim = ltrim($line);
                if ($trim !== '' && str_starts_with($trim, '--')) {
                    continue;
                }
                $lines[] = $line;
            }
            $stmt = trim(implode("\n", $lines));
            if ($stmt !== '') {
                $out[] = $stmt;
            }
        }

        return $out;
    }

    public static function writeInstalledFlag(): void
    {
        $payload = json_encode([
            'installed_at' => gmdate('c'),
            'version'      => 1,
        ], JSON_THROW_ON_ERROR | JSON_PRETTY_PRINT);

        if (file_put_contents(self::flagPath(), $payload . "\n") === false) {
            throw new \RuntimeException('Unable to write install flag in writable/.installed.');
        }
    }
}
