<?php

namespace Database\Seeders;

use App\Models\Unit;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class UnitSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Unit::truncate();
        Unit::insert([
            ['name' => 'ppm'],
            ['name' => 'μg/m3'],
            ['name' => 'mg/m3'],
            ['name' => 'l/min'],
            ['name' => 'm3/min'],
            ['name' => 'm3/h'],
            ['name' => 'Nm3/h'],
            ['name' => 'minutes'],
            ['name' => 'ton'],
            ['name' => '%'],
            ['name' => 'm/sec'],
            ['name' => 'C'],
            ['name' => 'kg/h'],
        ]);
    }
}
