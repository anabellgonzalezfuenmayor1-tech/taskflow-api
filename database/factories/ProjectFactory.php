<?php

namespace Database\Factories;

use App\Enums\ProjectStatus;
use App\Models\Project;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Project>
 */
class ProjectFactory extends Factory
{
    public function definition(): array
    {
        $start = fake()->dateTimeBetween('-1 month', '+1 month');

        return [
            'name' => ucfirst(fake()->unique()->words(3, true)),
            'description' => fake()->paragraph(),
            'start_date' => $start,
            'end_date' => fake()->dateTimeBetween($start, '+6 months'),
            'status' => fake()->randomElement(ProjectStatus::cases()),
            'created_by' => User::factory(),
        ];
    }
}
