<?php

namespace Database\Factories;

use App\Models\ProjectDemand;
use App\Models\User;
use App\Enums\Priority;
use Illuminate\Database\Eloquent\Factories\Factory;

class ProjectDemandFactory extends Factory
{
    protected $model = ProjectDemand::class;

    public function definition(): array
    {
        return [
            'title' => $this->faker->sentence(4),
            'description' => $this->faker->paragraph(),
            'justification' => $this->faker->paragraph(),
            'requester_id' => User::factory(),
            'status' => 'draft',
            'priority' => Priority::MEDIUM,
        ];
    }
}
