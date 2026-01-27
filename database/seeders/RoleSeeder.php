<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;

class RoleSeeder extends Seeder
{
    public function run(): void
    {
        $user = User::where('email', 'kelvinstaandrade@gmail.com')->first();
        if ($user) {
            $user->assignRole('super_admin');
        }
    }
}
