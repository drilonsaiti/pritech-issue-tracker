<?php

namespace Database\Factories;

use App\Models\Project;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Project>
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
        $start_date = fake()->dateTimeBetween('-2 months', 'now');
        $deadline = fake()->dateTimeBetween($start_date, '+3 months');
        return [
            //
            'name' => fake()->unique()->words(3, true),
            'description' => fake()->paragraph(),
            'start_date' => $start_date,
            'deadline' => $deadline
        ];
    }
}
