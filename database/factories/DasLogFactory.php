<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\DasLog>
 */
class DasLogFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            "measured" => $this->faker->numberBetween(0, 20),
            "raw" => $this->faker->numberBetween(0, 100),
            "is_sent" => 1,
            "time_group" => $this->faker->dateTimeBetween('-1 month', 'now')->format('Y-m-d H:i:00'),
        ];
    }
}
