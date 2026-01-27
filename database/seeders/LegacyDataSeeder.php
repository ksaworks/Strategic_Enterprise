<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class LegacyDataSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $this->command->info('Seeding Legacy (gpweb) Database...');

        $connection = DB::connection('legacy');

        // Clear tables
        $connection->statement('SET FOREIGN_KEY_CHECKS=0;');
        $connection->table('tarefas')->truncate();
        $connection->table('projetos')->truncate();
        $connection->table('depts')->truncate();
        $connection->table('usuarios')->truncate();
        $connection->table('contatos')->truncate();
        $connection->table('cias')->truncate();
        $connection->table('moeda')->truncate();
        $connection->statement('SET FOREIGN_KEY_CHECKS=1;');

        // 0. Create Currency (moeda)
        $connection->table('moeda')->insert([
            'moeda_id' => 1,
            'moeda_nome' => 'Real',
            'moeda_simbolo' => 'R$',
        ]);
        // $connection->table('arquivo')->truncate(); // Table might not exist yet if not created manually, skipping for safety or check first

        // 1. Create Companies (cias)
        $this->command->info('Creating 3 Legacy Companies...');
        $cias = [];
        for ($i = 1; $i <= 3; $i++) {
            $cias[] = [
                'cia_id' => $i,
                'cia_nome' => 'Legacy Company ' . $i,
                'cia_email' => 'contact@legacy' . $i . '.com',
                'cia_tel1' => '1199999000' . $i,
                'cia_ativo' => 1,
            ];
        }
        $connection->table('cias')->insert($cias);

        // 2. Create Departments (depts)
        $this->command->info('Creating Departments...');
        $depts = [];
        foreach ($cias as $cia) {
            $depts[] = [
                'dept_id' => $cia['cia_id'] * 10 + 1,
                'dept_cia' => $cia['cia_id'],
                'dept_nome' => 'IT Dept ' . $cia['cia_id'],
                'dept_descricao' => 'Information Technology',
                'dept_superior' => null,
                'dept_ativo' => 1,
            ];
            $depts[] = [
                'dept_id' => $cia['cia_id'] * 10 + 2,
                'dept_cia' => $cia['cia_id'],
                'dept_nome' => 'HR Dept ' . $cia['cia_id'],
                'dept_descricao' => 'Human Resources',
                'dept_superior' => null,
                'dept_ativo' => 1,
            ];
        }
        $connection->table('depts')->insert($depts);

        // 3. Create Contacts (contatos) & Users (usuarios)
        $this->command->info('Creating Contacts & Users...');

        $users = [];
        $contatos = [];

        for ($i = 1; $i <= 5; $i++) {
            // Create Contact first
            $contatos[] = [
                'contato_id' => $i,
                'contato_cia' => 1,
                'contato_nomecompleto' => 'Legacy User ' . $i,
                'contato_email' => 'user' . $i . '@legacy.com',
            ];

            // Create User linked to Contact
            $users[] = [
                'usuario_id' => $i,
                'usuario_contato' => $i,
                'usuario_login' => 'user' . $i,
                'usuario_senha' => md5('password'),
                'usuario_ativo' => 1,
                // 'usuario_criacao' => now(), // Assuming column might not exist or defaults, removed to be safe or check schema
            ];
        }
        $connection->table('contatos')->insert($contatos);
        $connection->table('usuarios')->insert($users);

        // 4. Create Projects (projetos)
        $this->command->info('Creating Projects...');
        $projects = [];
        for ($i = 1; $i <= 5; $i++) {
            $projects[] = [
                'projeto_id' => $i,
                'projeto_cia' => 1, // All in first company for simplicity
                'projeto_dept' => 11, // IT Dept
                'projeto_nome' => 'Legacy Project ' . $i,
                'projeto_descricao' => 'Description for project ' . $i,
                'projeto_data_inicio' => now()->subDays(rand(10, 100)),
                'projeto_data_fim' => now()->addDays(rand(10, 100)),
                // 'projeto_superior' => 0, // Optional
                // 'projeto_status' => rand(0, 100), // Status might be different in legacy
            ];
        }
        $connection->table('projetos')->insert($projects);

        // 5. Create Tasks (tarefas)
        $this->command->info('Creating Tasks...');
        $tasks = [];
        for ($i = 1; $i <= 10; $i++) {
            $tasks[] = [
                'tarefa_id' => $i,
                'tarefa_projeto' => rand(1, 5),
                'tarefa_nome' => 'Legacy Task ' . $i,
                'tarefa_descricao' => 'Task description ' . $i,
                'tarefa_inicio' => now(),
                'tarefa_fim' => now()->addDays(5),
                // 'tarefa_status' => 0,
                'tarefa_percentagem' => rand(0, 100),
                // 'tarefa_prioridade' => rand(1, 3),
                'tarefa_superior' => null,
            ];
        }
        $connection->table('tarefas')->insert($tasks);

        $this->command->info('Legacy Database Seeded Successfully!');
    }
}
