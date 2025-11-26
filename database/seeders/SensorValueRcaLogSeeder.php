<?php

namespace Database\Seeders;

use App\Models\Sensor;
use App\Models\SensorValueRcaLog;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class SensorValueRcaLogSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $sensors = Sensor::all();
        foreach ($sensors as $sensor) {
            SensorValueRcaLog::factory(100)->create(['sensor_id' => $sensor->id]);
        }
    }
}
