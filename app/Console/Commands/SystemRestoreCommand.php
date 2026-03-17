<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;
use Symfony\Component\Process\Process;

class SystemRestoreCommand extends Command
{
    protected $signature = 'system:restore {file} {--force}';
    protected $description = 'Restaurer une sauvegarde SQL';

    public function handle(): int
    {
        if (!$this->option('force')) {
            $this->warn('Ajoute --force pour confirmer.');
            return self::INVALID;
        }

        $file = $this->argument('file');

        if (!File::exists($file)) {
            $this->error('Fichier introuvable : ' . $file);
            return self::FAILURE;
        }

        $db = config('database.connections.' . config('database.default'));

        if (($db['driver'] ?? null) !== 'mysql') {
            $this->error('Cette V1 prend en charge MySQL.');
            return self::FAILURE;
        }

        $mysqlBin = env('MYSQL_CLIENT_PATH', 'mysql');

        $sql = File::get($file);

        $process = new Process([
            $mysqlBin,
            '--user=' . ($db['username'] ?? ''),
            '--password=' . ($db['password'] ?? ''),
            '--host=' . ($db['host'] ?? '127.0.0.1'),
            '--port=' . ($db['port'] ?? 3306),
            $db['database'],
        ]);

        $process->setInput($sql);
        $process->setTimeout(3600);
        $process->run();

        if (!$process->isSuccessful()) {
            $this->error($process->getErrorOutput() ?: 'Échec restauration.');
            return self::FAILURE;
        }

        $this->info('Restauration terminée.');
        return self::SUCCESS;
    }
}