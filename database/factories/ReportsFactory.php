<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Reports>
 */
class ReportsFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'kd_report' => null,
            'latitude' => -8.172367,
            'longitude' => 113.700592,
            'address' => null,
            'description' => $this->faker->sentence,
            'user_id' => 1,
            'status' => 'pending',
            'id_category' => 1,
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }
}
