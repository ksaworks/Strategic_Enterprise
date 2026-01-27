<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        User::updateOrCreate([
            'email' => 'kelvinstaandrade@gmail.com',
        ], [
            'name' => 'Kelvin',
            'password' => Hash::make('password123'),
            'is_active' => true,
        ]);
    }
}
