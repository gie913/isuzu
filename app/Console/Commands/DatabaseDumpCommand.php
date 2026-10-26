<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;
use Symfony\Component\Process\Process;

class DatabaseDumpCommand extends Command
{
    protected $signature = 'db:dump
        {--connection= : The database connection to dump (defaults to config database.default)}
        {--path= : Custom output file path (defaults to storage/app/backups/<database>_<timestamp>.sql)}';

    protected $description = 'Dump the database to a SQL or backup file';

    public function handle(): int
    {
        $connectionName = (string) ($this->option('connection') ?: config('database.default'));
        $config = config("database.connections.{$connectionName}");

        if (! is_array($config)) {
            $this->error("Database connection [{$connectionName}] is not configured.");

            return self::FAILURE;
        }

        $driver = (string) ($config['driver'] ?? '');
        $database = (string) ($config['database'] ?? 'database');
        $dbNameLabel = basename(str_replace('\\', '/', $database)) ?: 'database';

        $outputPath = (string) ($this->option('path') ?: storage_path(
            'app/backups/' . $dbNameLabel . '_' . now()->format('Ymd_His') . ($driver === 'sqlite' ? '.sqlite' : '.sql')
        ));

        File::ensureDirectoryExists(dirname($outputPath));

        if ($driver === 'sqlite') {
            if ($database === ':memory:') {
                $this->error('Cannot dump an in-memory SQLite database to a file without an active persistent file.');

                return self::FAILURE;
            }

            if (! File::exists($database)) {
                $this->error("SQLite database file not found at [{$database}].");

                return self::FAILURE;
            }

            File::copy($database, $outputPath);
            $this->info("Database dumped successfully to: {$outputPath}");

            return self::SUCCESS;
        }

        if ($driver === 'pgsql') {
            $command = [
                'pg_dump',
                '--host=' . ($config['host'] ?? '127.0.0.1'),
                '--port=' . ($config['port'] ?? '5432'),
                '--username=' . ($config['username'] ?? 'postgres'),
                '--dbname=' . $database,
                '--no-owner',
                '--no-privileges',
                '--clean',
                '--if-exists',
                '--file=' . $outputPath,
            ];

            $env = [
                'PGPASSWORD' => (string) ($config['password'] ?? ''),
            ];

            return $this->runDumpProcess($command, $env, $outputPath);
        }

        if (in_array($driver, ['mysql', 'mariadb'], true)) {
            $command = [
                'mysqldump',
                '--host=' . ($config['host'] ?? '127.0.0.1'),
                '--port=' . ($config['port'] ?? '3306'),
                '--user=' . ($config['username'] ?? 'root'),
                '--single-transaction',
                '--routines',
                '--triggers',
                '--result-file=' . $outputPath,
                $database,
            ];

            $env = [];
            if (! empty($config['password'])) {
                $env['MYSQL_PWD'] = (string) $config['password'];
            }

            return $this->runDumpProcess($command, $env, $outputPath);
        }

        $this->error("Unsupported database driver [{$driver}].");

        return self::FAILURE;
    }

    private function runDumpProcess(array $command, array $env, string $outputPath): int
    {
        $process = new Process($command, base_path(), $env, null, null);
        $process->run();

        if (! $process->isSuccessful()) {
            $this->error('Database dump failed: ' . trim($process->getErrorOutput() ?: $process->getOutput()));

            return self::FAILURE;
        }

        $this->info("Database dumped successfully to: {$outputPath}");

        return self::SUCCESS;
    }
}
