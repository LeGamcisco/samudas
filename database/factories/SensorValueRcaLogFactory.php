<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\SensorValueRcaLog>
 */
class SensorValueRcaLogFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            "measured" => $this->faker->randomFloat(2, 0, 100),
            "raw" => $this->faker->randomFloat(2, 0, 100),
            "corrected" => $this->faker->randomFloat(2, 0, 100),
            "created_at" => $this->faker->dateTimeBetween('-1 month', 'now'),
        ];
    }
}
