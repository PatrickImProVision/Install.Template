<?php

declare(strict_types=1);

namespace App\Commands;

use App\Libraries\Installer;
use CodeIgniter\CLI\BaseCommand;
use CodeIgniter\CLI\CLI;

/**
 * Pushes preset schema using PostgreSQL scripts (*.pgsql.sql only).
 *
 * Does not replace the web installer: Install::schema still imports for whichever driver the wizard saved.
 * If tables already exist, the wizard skips re-import safely.
 */
class ImportPresetTables extends BaseCommand
{
    protected $group       = 'Database';
    protected $name        = 'db:import-preset';
    protected $description = 'Import preset tables to PostgreSQL (*.pgsql.sql). Requires default DBDriver Postgre.';

    public function run(array $params): int
    {
        if (Installer::defaultDatabaseDriver() !== 'Postgre') {
            CLI::error(
                'CLI preset push is restricted to PostgreSQL. '
                . 'Set Config\\Database default DBDriver to Postgre for this command, '
                . 'or use the web installer schema step for MySQL / SQLite / SQL Server.'
            );

            return EXIT_ERROR;
        }

        try {
            Installer::importPresetTables();
        } catch (\Throwable $e) {
            CLI::error($e->getMessage());

            return EXIT_ERROR;
        }

        CLI::write('Preset tables imported.', 'green');

        if (! Installer::isInstalled()) {
            CLI::write(
                'Installation is not finished (.installed missing). '
                . 'Continue the web wizard; the schema step will see existing tables and skip re-import — that is OK.',
                'yellow'
            );
        }

        return EXIT_SUCCESS;
    }
}
