<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class ExportTable extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:export-table';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Export PostgreSQL File using PgDump';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $command = sprintf(
            'pg_dump -U %s -h %s -p %s -d %s -Fc -f %s',
            env('DB_USERNAME'),
            env('DB_HOST'),
            env('DB_PORT'),
            env('DB_DATABASE'),
            public_path('export/daily-backup.sql')
        );
        exec($command);

    }
}
