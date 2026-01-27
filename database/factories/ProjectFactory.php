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
        return [
            'company_id' => \App\Models\Company::factory(),
            'owner_id' => \App\Models\User::factory(),
            'name' => $this->faker->sentence(3),
            'code' => $this->faker->unique()->bothify('PROJ-####'),
            'description' => $this->faker->paragraph(),
            'start_date' => $this->faker->date(),
            'end_date' => $this->faker->date(),
            'status' => 'planning',
            'priority' => 'medium',
            'is_active' => true,
        ];
    }
}
