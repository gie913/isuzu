<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;
use Symfony\Component\Process\Process;

class DatabaseRestoreCommand extends Command
{
    protected $signature = 'db:restore
        {file? : Path to the SQL or SQLite backup file to restore (defaults to latest file in storage/app/backups)}
        {--connection= : The database connection to restore into (defaults to config database.default)}
        {--force : Force the operation to run without confirmation}';

    protected $description = 'Restore the database from a SQL or backup file';

    public function handle(): int
    {
        $connectionName = (string) ($this->option('connection') ?: config('database.default'));
        $config = config("database.connections.{$connectionName}");

        if (! is_array($config)) {
            $this->error("Database connection [{$connectionName}] is not configured.");

            return self::FAILURE;
        }

        $inputPath = $this->resolveBackupFile($this->argument('file'));

        if ($inputPath === null || ! File::exists($inputPath)) {
            $this->error($inputPath
                ? "Backup file not found at [{$inputPath}]."
                : 'No backup file specified and no backups found in storage/app/backups.');

            return self::FAILURE;
        }

        $driver = (string) ($config['driver'] ?? '');
        $database = (string) ($config['database'] ?? 'database');

        if (! $this->option('force') && ! $this->confirm("Are you sure you want to restore [{$inputPath}] into [{$database}] ({$connectionName})?")) {
            $this->comment('Database restore cancelled.');

            return self::SUCCESS;
        }

        if ($driver === 'sqlite') {
            if ($database === ':memory:') {
                $this->error('Cannot restore file into an in-memory SQLite database via file copy.');

                return self::FAILURE;
            }

            File::ensureDirectoryExists(dirname($database));
            File::copy($inputPath, $database);
            $this->info("Database restored successfully from: {$inputPath}");

            return self::SUCCESS;
        }

        if ($driver === 'pgsql') {
            $command = [
                'psql',
                '--host=' . ($config['host'] ?? '127.0.0.1'),
                '--port=' . ($config['port'] ?? '5432'),
                '--username=' . ($config['username'] ?? 'postgres'),
                '--dbname=' . $database,
                '--file=' . $inputPath,
            ];

            $env = [
                'PGPASSWORD' => (string) ($config['password'] ?? ''),
            ];

            return $this->runRestoreProcess($command, $env, $inputPath);
        }

        if (in_array($driver, ['mysql', 'mariadb'], true)) {
            $command = [
                'mysql',
                '--host=' . ($config['host'] ?? '127.0.0.1'),
                '--port=' . ($config['port'] ?? '3306'),
                '--user=' . ($config['username'] ?? 'root'),
                $database,
            ];

            $env = [];
            if (! empty($config['password'])) {
                $env['MYSQL_PWD'] = (string) $config['password'];
            }

            $process = new Process($command, base_path(), $env, File::get($inputPath), null);
            $process->run();

            if (! $process->isSuccessful()) {
                $this->error('Database restore failed: ' . trim($process->getErrorOutput() ?: $process->getOutput()));

                return self::FAILURE;
            }

            $this->info("Database restored successfully from: {$inputPath}");

            return self::SUCCESS;
        }

        $this->error("Unsupported database driver [{$driver}].");

        return self::FAILURE;
    }

    private function resolveBackupFile(?string $file): ?string
    {
        if ($file !== null && trim($file) !== '') {
            if (File::exists($file)) {
                return $file;
            }

            $inBackups = storage_path('app/backups/' . ltrim($file, '/'));
            if (File::exists($inBackups)) {
                return $inBackups;
            }

            return $file;
        }

        $backupDir = storage_path('app/backups');
        if (! File::isDirectory($backupDir)) {
            return null;
        }

        $files = collect(File::files($backupDir))
            ->sortByDesc(fn (\SplFileInfo $f) => $f->getMTime())
            ->values();

        return $files->first()?->getPathname();
    }

    private function runRestoreProcess(array $command, array $env, string $inputPath): int
    {
        $process = new Process($command, base_path(), $env, null, null);
        $process->run();

        if (! $process->isSuccessful()) {
            $this->error('Database restore failed: ' . trim($process->getErrorOutput() ?: $process->getOutput()));

            return self::FAILURE;
        }

        $this->info("Database restored successfully from: {$inputPath}");

        return self::SUCCESS;
    }
}
