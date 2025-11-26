<?php

namespace Database\Seeders;

use App\Models\DasLog;
use App\Models\Sensor;
use Carbon\Carbon;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DasLogSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $sensors = Sensor::all();
        $startAt = now()->format('Y-m-d H:00:00');
        $endAt = now()->addHour()->format('Y-m-d H:00:00');
        $init = $startAt;

        foreach ($sensors as $sensor) {
            while ($startAt < $endAt) {
                DasLog::factory(1)->create([
                    "parameter_id" => $sensor->parameter_id, 
                    "unit_id" => $sensor->unit_id,
                    "time_group" => $startAt,
                ]);
                $startAt = Carbon::parse($startAt)->addMinute()->format('Y-m-d H:i:00');
            }
            $startAt = $init;
        }
    }
}
