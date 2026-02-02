<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     * 
     * Sprint 9 - Tarefa 9.2: Cria tabela de equipe de projeto
     * 
     * Baseado no legado GPWeb: tabela projeto_usuarios
     * Permite vincular múltiplos usuários a um projeto com papel e alocação.
     */
    public function up(): void
    {
        Schema::create('project_team', function (Blueprint $table) {
            $table->id();
            
            $table->foreignId('project_id')
                ->comment('Projeto')
                ->constrained('projects')
                ->cascadeOnDelete();
            
            $table->foreignId('user_id')
                ->comment('Membro da equipe')
                ->constrained('users')
                ->cascadeOnDelete();
            
            // Papel no projeto
            $table->string('role', 100)
                ->default('member')
                ->comment('Papel: manager, coordinator, analyst, developer, member, etc.');
            
            // Percentual de alocação (0-100%)
            $table->decimal('allocation_percentage', 5, 2)
                ->default(100.00)
                ->comment('Percentual de alocação no projeto');
            
            // Datas de participação
            $table->date('start_date')
                ->nullable()
                ->comment('Início da participação');
            
            $table->date('end_date')
                ->nullable()
                ->comment('Fim da participação');
            
            // Status
            $table->boolean('is_active')
                ->default(true)
                ->comment('Se está ativo na equipe');
            
            // Permissões especiais
            $table->boolean('can_edit')
                ->default(false)
                ->comment('Pode editar o projeto');
            
            $table->boolean('can_delete')
                ->default(false)
                ->comment('Pode excluir itens do projeto');
            
            $table->timestamps();
            
            // Impedir duplicação
            $table->unique(['project_id', 'user_id'], 'project_team_unique');
            
            // Índices
            $table->index('project_id');
            $table->index('user_id');
            $table->index('role');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('project_team');
    }
};
