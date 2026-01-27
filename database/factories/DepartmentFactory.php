<?php

namespace Database\Factories;

use App\Models\Department;
use App\Models\Company;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class DepartmentFactory extends Factory
{
    protected $model = Department::class;

    public function definition(): array
    {
        return [
            'company_id' => Company::factory(),
            'manager_id' => User::factory(),
            'name' => $this->faker->words(2, true),
            'code' => $this->faker->unique()->bothify('DEP-###'),
            'is_active' => $this->faker->boolean(90),
        ];
    }
}
