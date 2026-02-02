<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Perspective;

class PerspectiveSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $perspectives = [
            [
                'name' => 'Financeira',
                'description' => 'Perspectiva financeira: "Para termos sucesso financeiro, como devemos aparecer para nossos investidores?"',
                'color' => '#16a34a', // Green
                'icon' => 'heroicon-o-currency-dollar',
                'order' => 1,
            ],
            [
                'name' => 'Clientes',
                'description' => 'Perspectiva do cliente: "Para alcançar nossa visão, como devemos ser vistos pelos nossos clientes?"',
                'color' => '#2563eb', // Blue
                'icon' => 'heroicon-o-users',
                'order' => 2,
            ],
            [
                'name' => 'Processos Internos',
                'description' => 'Perspectiva dos processos internos: "Para satisfazer nossos acionistas e clientes, em quais processos de negócios devemos nos destacar?"',
                'color' => '#7c3aed', // Purple
                'icon' => 'heroicon-o-cog-6-tooth',
                'order' => 3,
            ],
            [
                'name' => 'Aprendizado e Crescimento',
                'description' => 'Perspectiva do aprendizado e crescimento: "Para alcançar nossa visão, como sustentaremos nossa capacidade de mudar e melhorar?"',
                'color' => '#ea580c', // Orange
                'icon' => 'heroicon-o-academic-cap',
                'order' => 4,
            ],
        ];

        foreach ($perspectives as $perspective) {
            Perspective::firstOrCreate(
                ['name' => $perspective['name']],
                $perspective
            );
        }
    }
}
