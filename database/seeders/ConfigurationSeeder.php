<?php

namespace Database\Seeders;

use App\Models\Configuration;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ConfigurationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $config = Configuration::find(1);
        if (!$config) {
            Configuration::create([
                'name' => 'Samu DAS',
                'server_ip' => '127.0.0.1',
                'server_apikey' => base64_encode("fortech-apikey"),
                'server_url' => 'http://127.0.0.1/api/value-logs',
                'day_backup' => 1,
                'is_rca' => 0,
                'rca_stack' => null,
            ]);
        }
    }
}
