<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Schema;

class MigrateLegacyData extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:migrate-legacy {--fresh : Truncate tables before migrating}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Migrate data from legacy GPWeb database to Strategic Enterprise';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Starting Legacy Migration...');

        if ($this->option('fresh')) {
            if ($this->confirm('This will TRUNCATE all target tables. Are you sure?', true)) {
                $this->truncateTables();
            }
        }

        DB::beginTransaction();

        try {
            $this->migrateCompanies();
            $this->migrateDepartments();
            $this->migrateUsers();
            $this->migrateProjects();
            $this->migrateTasks();
            $this->migrateDocuments();

            DB::commit();
            $this->info('Migration completed successfully!');
        } catch (\Exception $e) {
            DB::rollBack();
            $this->error('Migration failed: ' . $e->getMessage());
            $this->error($e->getTraceAsString());
        }
    }

    private function truncateTables()
    {
        $this->info('Truncating tables...');
        Schema::disableForeignKeyConstraints();
        \App\Models\Document::truncate();
        \App\Models\Task::truncate();
        \App\Models\Project::truncate();
        \App\Models\Contact::truncate();
        \App\Models\Department::truncate();
        \App\Models\Company::truncate();
        \App\Models\User::truncate();
        Schema::enableForeignKeyConstraints();
    }

    private function migrateCompanies()
    {
        $this->info('Migrating Companies...');
        $legacyCias = DB::connection('legacy')->table('cias')->get();

        foreach ($legacyCias as $cia) {
            \App\Models\Company::create([
                'id' => $cia->cia_id,
                'name' => $cia->cia_nome,
                'email' => $cia->cia_email,
                'cnpj' => $cia->cia_cnpj, // Assuming mapping
                'phone' => $cia->cia_tel1,
                'address' => $cia->cia_endereco1,
                'city' => $cia->cia_cidade,
                'state' => $cia->cia_estado,
                'zip_code' => $cia->cia_cep,
                'is_active' => $cia->cia_ativo ?? true,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
        $this->info(count($legacyCias) . ' companies migrated.');
    }

    private function migrateDepartments()
    {
        $this->info('Migrating Departments...');
        $legacyDepts = DB::connection('legacy')->table('depts')->get();

        foreach ($legacyDepts as $dept) {
            \App\Models\Department::create([
                'id' => $dept->dept_id,
                'company_id' => $dept->dept_cia, // Direct mapping if IDs preserved
                'parent_id' => $dept->dept_superior != 0 ? $dept->dept_superior : null,
                'name' => $dept->dept_nome,
                'description' => $dept->dept_descricao,
                'is_active' => $dept->dept_ativo ?? true,
            ]);
        }
        $this->info(count($legacyDepts) . ' departments migrated.');
    }

    private function migrateUsers()
    {
        $this->info('Migrating Users...');
        $legacyUsers = DB::connection('legacy')
            ->table('usuarios')
            ->join('contatos', 'usuarios.usuario_contato', '=', 'contatos.contato_id')
            ->select('usuarios.*', 'contatos.contato_nomecompleto', 'contatos.contato_email')
            ->get();

        foreach ($legacyUsers as $user) {
            // Check if email is valid, if not generate dummy
            $email = filter_var($user->contato_email, FILTER_VALIDATE_EMAIL)
                ? $user->contato_email
                : $user->usuario_login . '@strategic.local';

            $newUser = \App\Models\User::create([
                'id' => $user->usuario_id,
                'name' => $user->contato_nomecompleto ?? $user->usuario_login,
                'email' => $email,
                'password' => Hash::make('strategic123'), // Default password
                'is_active' => $user->usuario_ativo ?? true,
                'created_at' => now(), // 'usuario_criacao' might not exist or be reliable
                'updated_at' => now(),
            ]);
        }
        $this->info(count($legacyUsers) . ' users migrated.');
    }

    private function migrateProjects()
    {
        $this->info('Migrating Projects...');
        $legacyProjects = DB::connection('legacy')->table('projetos')->get();

        foreach ($legacyProjects as $project) {
            \App\Models\Project::create([
                'id' => $project->projeto_id,
                'company_id' => $project->projeto_cia,
                'department_id' => $project->projeto_dept,
                'name' => $project->projeto_nome,
                'description' => $project->projeto_descricao,
                'start_date' => $project->projeto_data_inicio,
                'end_date' => $project->projeto_data_fim,
                'status' => $this->mapProjectStatus($project->projeto_status),
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
        $this->info(count($legacyProjects) . ' projects migrated.');
    }

    private function migrateTasks()
    {
        $this->info('Migrating Tasks...');
        $legacyTasks = DB::connection('legacy')->table('tarefas')->get();

        foreach ($legacyTasks as $task) {
            \App\Models\Task::create([
                'id' => $task->tarefa_id,
                'project_id' => $task->tarefa_projeto,
                'parent_id' => $task->tarefa_superior != 0 ? $task->tarefa_superior : null,
                'name' => $task->tarefa_nome,
                'description' => $task->tarefa_descricao,
                'start_date' => $task->tarefa_inicio,
                'due_date' => $task->tarefa_fim,
                'status' => $this->mapTaskStatus($task->tarefa_status, $task->tarefa_percentagem),
                'priority' => $this->mapPriority($task->tarefa_prioridade),
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
        $this->info(count($legacyTasks) . ' tasks migrated.');
    }

    private function migrateDocuments()
    {
        $this->info('Migrating Documents...');

        // Legacy 'arquivo' table logic
        // Assuming 'arquivo_nome' is title, 'arquivo_caminho' is file path

        // Note: The legacy 'arquivo' table might not map perfectly to polymorphic relations
        // We will try to link to projects or tasks if possible, otherwise leave orphans

        // Check if table exists (it was identified as 'arquivo' via source code)
        if (!Schema::connection('legacy')->hasTable('arquivo')) {
            $this->warn('Legacy "arquivo" table not found. Skipping document migration.');
            return;
        }

        $legacyFiles = DB::connection('legacy')->table('arquivo')->get();

        foreach ($legacyFiles as $file) {
            // Mapping Logic:
            // Legacy systems often linked files via separate tables (e.g., projeto_arquivo)
            // For simplicity in this first pass, we will migrate them as unattached or generic documents
            // unless we find a direct link column.

            \App\Models\Document::create([
                'id' => $file->arquivo_id,
                'title' => $file->arquivo_nome,
                'file_path' => 'legacy/' . $file->arquivo_nome, // Placeholder path
                'file_type' => $file->arquivo_tipo ?? 'unknown',
                'file_size' => $file->arquivo_tamanho ?? 0,
                'description' => $file->arquivo_descricao ?? null,
                // 'documentable_type' => ... (Cannot determine easily without join tables)
                // 'documentable_id' => ...
                'created_at' => $file->arquivo_data ?? now(),
                'updated_at' => now(),
            ]);
        }
        $this->info(count($legacyFiles) . ' documents migrated.');
    }

    private function mapProjectStatus($legacyStatus)
    {
        // Simple mapping, adjust based on real legacy values (0,1, etc)
        return match ($legacyStatus) {
            100 => 2, // Completed
            0 => 0,   // Planned / Not Started
            default => 1, // In Progress
        };
    }

    private function mapTaskStatus($legacyStatus, $percent)
    {
        if ($percent >= 100)
            return 2; // Completed
        return 1; // In Progress (Default)
    }

    private function mapPriority($legacyPriority)
    {
        return match ($legacyPriority) {
            1 => 0, // low
            3 => 9, // high
            default => 5, // medium
        };
    }
}
