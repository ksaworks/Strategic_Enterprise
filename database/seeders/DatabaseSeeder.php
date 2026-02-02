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
        // 1. Criar Usuário Admin
        $user = User::updateOrCreate([
            'email' => 'kelvinstaandrade@gmail.com',
        ], [
            'name' => 'Kelvin',
            'password' => Hash::make('password123'),
            'is_active' => true,
        ]);

        // 2. Gerar Permissões do Shield (Essentials)
        // Isso recria todas as permissões baseadas nos Resources e Pages
        $this->command->info('Generating Shield Permissions...');
        \Illuminate\Support\Facades\Artisan::call('shield:generate --all');
        $this->command->info('Shield Permissions Generated.');

        // 3. Garantir que o Role Super Admin existe
        $roleName = config('filament-shield.super_admin.name', 'super_admin');
        $role = \Spatie\Permission\Models\Role::firstOrCreate(['name' => $roleName, 'guard_name' => 'web']);

        // 4. Atribuir Role ao Usuário
        $user->assignRole($role);
        $this->command->info("Role {$roleName} assigned to {$user->name}.");
        
        // 5. Rodar outros seeders se necessário
        // $this->call(LegacyDataSeeder::class);
    }
}
