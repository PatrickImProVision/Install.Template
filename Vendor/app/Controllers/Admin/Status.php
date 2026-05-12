<?php

declare(strict_types=1);

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Libraries\Installer;
use CodeIgniter\Database\BaseConnection;

class Status extends BaseController
{
    public function index(): string
    {
        helper('url');

        $appConfig = config(\Config\App::class);
        $dbConfig  = config(\Config\Database::class);
        $groupName = $dbConfig->defaultGroup;
        /** @var array<string, mixed> $group */
        $group = $dbConfig->{$groupName};

        return view('admin/status/index', [
            'title'           => 'System status',
            'groupName'       => $groupName,
            'environment'     => ENVIRONMENT,
            'baseURL'         => (string) ($appConfig->baseURL ?? ''),
            'phpVersion'      => PHP_VERSION,
            'phpSapi'         => PHP_SAPI,
            'ciVersion'       => \CodeIgniter\CodeIgniter::CI_VERSION,
            'timezone'        => date_default_timezone_get(),
            'serverSoftware'  => $_SERVER['SERVER_SOFTWARE'] ?? '',
            'memoryLimit'     => ini_get('memory_limit') ?: '',
            'postMaxSize'     => ini_get('post_max_size') ?: '',
            'uploadMaxSize'   => ini_get('upload_max_filesize') ?: '',
            'sessionUser'     => session()->get('user_email'),
            'installFlag'     => $this->installFlagStatus(),
            'writable'        => $this->writableStatus(),
            'database'        => $this->databaseStatus($group, $groupName),
            'extensions'      => $this->extensionStatus(),
            'diskFreeBytes'   => @disk_free_space(WRITEPATH),
        ]);
    }

    /**
     * @return array{present: bool, path: string, readable: bool}
     */
    private function installFlagStatus(): array
    {
        $path = Installer::flagPath();

        return [
            'present'  => Installer::isInstalled(),
            'path'     => $path,
            'readable' => is_readable($path),
        ];
    }

    /**
     * @return list<array{label: string, path: string, ok: bool, detail: string}>
     */
    private function writableStatus(): array
    {
        $paths = [
            ['Writable root', WRITEPATH],
            ['Cache', WRITEPATH . 'cache'],
            ['Logs', WRITEPATH . 'logs'],
            ['Session', WRITEPATH . 'session'],
        ];

        $out = [];
        foreach ($paths as [$label, $path]) {
            $exists = is_dir($path) || is_file($path);
            $ok     = $exists && is_writable($path);
            $detail = ! $exists ? 'missing' : (! is_writable($path) ? 'not writable' : 'ok');

            $out[] = [
                'label'  => $label,
                'path'   => $path,
                'ok'     => $ok,
                'detail' => $detail,
            ];
        }

        return $out;
    }

    /**
     * @param array<string, mixed> $group
     *
     * @return array{
     *     connected: bool,
     *     error: string|null,
     *     driver: string,
     *     database: string,
     *     hostname: string,
     *     schema: string,
     *     prefix: string,
     *     tables: list<array{name: string, physical: string, exists: bool, rows: int|null, error: string|null}>
     * }
     */
    private function databaseStatus(array $group, string $groupName): array
    {
        $result = [
            'connected' => false,
            'error'     => null,
            'driver'    => (string) ($group['DBDriver'] ?? ''),
            'database'  => (string) ($group['database'] ?? ''),
            'hostname'  => (string) ($group['hostname'] ?? ''),
            'schema'    => (string) ($group['schema'] ?? ''),
            'prefix'    => '',
            'tables'    => [],
        ];

        try {
            $db = \Config\Database::connect($groupName);
            if (! $db instanceof BaseConnection) {
                $result['error'] = 'Invalid database connection.';

                return $result;
            }

            $db->initialize();
            $db->query('SELECT 1');
            $result['connected'] = true;
            $result['prefix']    = (string) ($db->getPrefix());

            foreach (Installer::presetTableNames() as $base) {
                $physical = $result['prefix'] . $base;
                $exists   = false;
                $rows     = null;
                $err      = null;

                try {
                    // Logical name (no prefix): CI applies DBPrefix for tableExists / Query Builder.
                    $exists = $db->tableExists($base);
                    if ($exists) {
                        $rows = $db->table($base)->countAllResults();
                    }
                } catch (\Throwable $e) {
                    $err = $e->getMessage();
                }

                $result['tables'][] = [
                    'name'     => $base,
                    'physical' => $physical,
                    'exists'   => $exists,
                    'rows'     => $rows,
                    'error'    => $err,
                ];
            }
        } catch (\Throwable $e) {
            $result['error'] = $e->getMessage();
        }

        return $result;
    }

    /**
     * @return list<array{name: string, loaded: bool}>
     */
    private function extensionStatus(): array
    {
        $names = ['curl', 'fileinfo', 'intl', 'json', 'mbstring', 'openssl', 'pdo', 'session'];

        $out = [];
        foreach ($names as $name) {
            $out[] = [
                'name'   => $name,
                'loaded' => extension_loaded($name),
            ];
        }

        return $out;
    }
}
