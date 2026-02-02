<?php

namespace Database\Factories;

use App\Models\Task;
use App\Models\Project;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class TaskFactory extends Factory
{
    protected $model = Task::class;

    public function definition(): array
    {
        return [
            'project_id' => Project::factory(),
            'owner_id' => User::factory(),
            'name' => $this->faker->sentence(4),
            'start_date' => $this->faker->date(),
            'end_date' => $this->faker->dateTimeBetween('+1 week', '+2 weeks')->format('Y-m-d'),
            'duration' => $this->faker->randomFloat(2, 1, 40),
            'description' => $this->faker->paragraph(),
            'status' => $this->faker->randomElement(\App\Enums\TaskStatus::cases()),
            'priority' => $this->faker->randomElement(\App\Enums\Priority::cases()),
            'progress' => $this->faker->numberBetween(0, 100),
            'is_milestone' => $this->faker->boolean(10),
        ];
    }
}
