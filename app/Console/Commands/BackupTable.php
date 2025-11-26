<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class BackupTable extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:backup-table';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '[Job] - Copy table & truncate active table every month at midnight.';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Starting table backup job.');
        $tables = ["das_logs","sensor_values"];
        foreach ($tables as $table) {
            $prefixName = now()->subMonth()->format('Y_m');
            DB::connection()->unprepared("create table if not exists {$table}_{$prefixName} as (select * from $table)");
            DB::connection()->unprepared("truncate table $table restart identity cascade");
        }
        $this->info('Table backup job completed.');
    }
}
