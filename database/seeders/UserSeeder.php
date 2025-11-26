<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::create([
            'name' => 'Admin',
            'email' => 'admin@trusur.com',
            'password' => Hash::make('admin@trusur.com'),
        ]);
        User::create([
            'name' => 'Operator',
            'email' => 'operator@trusur.com',
            'password' => Hash::make('operator@trusur.com'),
        ]);
    }
}
