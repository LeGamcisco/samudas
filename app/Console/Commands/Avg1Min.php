<?php

namespace App\Console\Commands;

use App\Models\DasLog;
use App\Models\Sensor;
use App\Models\SensorValue;
use Illuminate\Console\Command;

class Avg1Min extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:avg1min';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Command run successfully.');
        $minAgo = now()->subMinutes(1)->format('Y-m-d H:i:00');
        $now = now()->format('Y-m-d H:i:00');

        $parameters = Sensor::where(["is_show" => 1])->get();
        foreach ($parameters as $parameter) {
            $this->info("sensor_id = '{$parameter->id}' and created_at >= '{$minAgo}' and created_at <= '{$now}'");
            $sensorValue = SensorValue::whereRaw("sensor_id = '{$parameter->id}' and created_at >= '{$minAgo}' and created_at <= '{$now}'");
            $matchCase = ["parameter_id" => $parameter->parameter_id, "time_group" => $now, "is_sent" => 0];
            $this->info("[$parameter->name] count: ".$sensorValue->count());
            $avgMeasured = $sensorValue->avg("measured");
            $avgRaw = $sensorValue->avg("raw");
            // if($avgMeasured == null && $avgRaw == null) continue;
            if($avgMeasured == null && $avgRaw == null){
                DasLog::updateOrCreate($matchCase,$matchCase + [
                    "unit_id" => $parameter->unit_id,
                    "measured" => 0,
                    "raw" => 0,
                    "measured_at" => now()->format("Y-m-d H:i:s"),
                ]);
                continue;
            }
            DasLog::updateOrCreate($matchCase,$matchCase + [
                "unit_id" => $parameter->unit_id,
                "measured" => round($avgMeasured,3),
                "raw" => round($avgRaw,3),
                "measured_at" => now()->format("Y-m-d H:i:s"),
            ]);
        }

    }
}
