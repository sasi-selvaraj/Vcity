<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Marketer>
 */
class MarketerFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => fake()->name(['male']),
            'email' => fake()->unique()->safeEmail(),
            'address'=>fake()->address(),
            'city'=>fake()->city(),
            'country'=>fake()->country(),
            'postal'=>fake()->postcode(),
        ];
    }
}
