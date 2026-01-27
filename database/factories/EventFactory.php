<?php

namespace Database\Factories;

use App\Models\Event;
use App\Models\User;
use App\Models\Project;
use Illuminate\Database\Eloquent\Factories\Factory;

class EventFactory extends Factory
{
    protected $model = Event::class;

    public function definition(): array
    {
        $start = $this->faker->dateTimeBetween('now', '+1 month');
        return [
            'title' => $this->faker->sentence(3),
            'description' => $this->faker->paragraph(),
            'start_datetime' => $start,
            'end_datetime' => (clone $start)->modify('+2 hours'),
            'all_day' => $this->faker->boolean(10),
            'location' => $this->faker->address(),
            'project_id' => Project::factory(),
            'user_id' => User::factory(),
            'status' => $this->faker->randomElement(['scheduled', 'completed', 'cancelled']),
            'color' => $this->faker->hexColor(),
        ];
    }
}
