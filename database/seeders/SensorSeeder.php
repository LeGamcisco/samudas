<?php

namespace Database\Seeders;

use App\Models\Sensor;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class SensorSeeder extends Seeder
{
    
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Sensor::truncate();
        Sensor::insert([
            [
                "code" => "dust",
                "parameter_id" => 1,
                "extra_parameter" => 2,
                "o2_correction" => 1,
                "is_has_reference" => 1,
                "name" => "Dust",
                "unit_id" => 3,
                "stack_id" => 1,
                "analyzer_ip" => "127.0.0.1",
                "port" => "502|0",
                "formula" => "round(data[50]/100/20+0.8,2)",
                "is_show" => 1
            ],
            [
                "code" => "opacity",
                "parameter_id" => 2,
                "extra_parameter" => 2,
                "o2_correction" => 1,
                "is_has_reference" => 0,
                "name" => "Opacity",
                "unit_id" => 3,
                "stack_id" => 1,
                "analyzer_ip" => "127.0.0.1",
                "port" => "502|0",
                "formula" => "round(data[9]/10/20+0.9,2)",
                "is_show" => 1
            ],
            [
                "code" => "flow_rate",
                "extra_parameter" => 0,
                "o2_correction" => 0,
                "is_has_reference" => 0,
                "parameter_id" => 3,
                "name" => "Flowrate",
                "unit_id" => 11,
                "stack_id" => 1,
                "analyzer_ip" => "127.0.0.1",
                "port" => "502|1",
                "formula" => "round((3.14 * 2.21 * 2.21) * (data[1]/100),2)",
                "is_show" => 1
            ],
            [
                "code" => "o2",
                "extra_parameter" => 1,
                "o2_correction" => 0,
                "is_has_reference" => 0,
                "parameter_id" => 4,
                "name" => "O2",
                "unit_id" => 10,
                "stack_id" => 1,
                "analyzer_ip" => "127.0.0.1",
                "port" => "502|2",
                "formula" => "round(data[2]/10,2)",
                "is_show" => 1
            ],
            [
                "code" => "trs",
                "extra_parameter" => 0,
                "o2_correction" => 0,
                "is_has_reference" => 0,
                "parameter_id" => 5,
                "name" => "So2",
                "unit_id" => 3,
                "stack_id" => 1,
                "analyzer_ip" => "127.0.0.1",
                "port" => "502|3",
                "formula" => "round(data[3]/10 * 64 / 22.4,2)",
                "is_show" => 1
            ],
        ]);
        $sensors = Sensor::all();

        foreach ($sensors as $sensor) {
            $sensor->value()->delete();
            $sensor->value()->create([
                "sensor_id" => $sensor->id,
                "measured" => rand(1,99),
                "raw" => rand(1,99)
            ]);
        }
    }
}
