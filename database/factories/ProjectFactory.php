<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Project>
 */
class ProjectFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $startDate = fake()->dateTimeBetween('-1 month', '+1 week');
        $endDate = fake()->dateTimeBetween($startDate, '+2 months');

        return [
            'title' => fake()->sentence(3),
            'description' => fake()->paragraph(),
            'status' => fake()->randomElement(['pending', 'in_progress', 'completed']),
            'budget' => fake()->randomFloat(2, 200, 50000),
            'start_date' => $startDate,
            'end_date' => $endDate,
        ];
    }
}
