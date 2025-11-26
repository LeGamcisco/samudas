<?php

namespace App\Console\Commands;

use App\Models\DasLog;
use App\Models\Measurement;
use App\Models\Sensor;
use App\Models\Stack;
use Carbon\Carbon;
use Illuminate\Console\Command;

class Avg1Hour extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:avg1hour {startTime?} {endTime?}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Averaging 1 hour';

    /**
     * Execute the console command.
     */

    protected $startAt;
    protected $endAt;

    public function handle()
    {
        $runtimeStart = microtime(true);

         // Preparing variable for backdate
        $startTime = $this->argument('startTime');
        $endTime = $this->argument('endTime');

        $startAt = $startTime ?? now()->subHour()->format("Y-m-d H:00:00");
        $endAt = $endTime ?? now()->format("Y-m-d H:00:00");

        $this->startAt = $startAt;
        $this->endAt = $endAt;

        if(Carbon::parse($startAt)->format("i") != 0 || Carbon::parse($endAt)->format("i") != 0){
            return $this->warn("Please run this command at 00 minute");
        }

        $sensors = Sensor::orderBy("id")->get();
        foreach($sensors as $sensor){
            $dasLogs = DasLog::whereRaw("time_group >= '{$startAt}' and time_group < '{$endAt}' and parameter_id = {$sensor->parameter_id}")->get();
            $measured = $dasLogs->avg("measured");
            $correction = $measured;
            if($sensor->o2_correction == 1){
                $correction = $this->correction($measured,  $sensor->stack_id);
            }
            $avgPerHour = [
                "parameter_id" => $sensor->parameter_id,
                "unit_id" => $sensor->unit_id,
                "measured" => round($measured, 3),
                "corrected" => round($correction, 3),
                "time_group" => $endAt
            ];
            Measurement::updateOrCreate([
                "parameter_id" => $sensor->parameter_id,
                "time_group" => $endAt
            ],$avgPerHour);
        }
        $runtime = round(microtime(true) - $runtimeStart, 3);
        $this->info("Runtime: {$runtime}s");
    }

    public function getO2($stackId){
        $dasLogs =  DasLog::whereRaw("time_group >= '{$this->startAt}' and time_group < '{$this->endAt}' and parameter_id in (select parameter_id from sensors where extra_parameter=1 and stack_id = '{$stackId}')")->get();
        return round($dasLogs->avg("measured"), 3);
    }

    public function correction($measured, $stackId){
        $o2Reference = Stack::find($stackId)->o2_reference ?? 7;
        $o2Measured = $this->getO2($stackId);
        // Formula Correction =  $measured * (21 - $o2Reference) / (21 - $o2Measured)
        return round($measured * (21 - $o2Reference) / (21 - $o2Measured), 3);
    }


}
