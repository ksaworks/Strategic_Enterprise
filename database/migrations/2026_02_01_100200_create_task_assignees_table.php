<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     * 
     * Tarefa 8.3: Cria tabela pivot para múltiplos designados por tarefa
     * 
     * Permite que uma tarefa tenha múltiplos responsáveis com percentual de alocação.
     * Legado: tabela tarefa_designados
     */
    public function up(): void
    {
        Schema::create('task_assignees', function (Blueprint $table) {
            $table->id();
            
            $table->foreignId('task_id')
                ->comment('Tarefa')
                ->constrained('tasks')
                ->cascadeOnDelete();
            
            $table->foreignId('user_id')
                ->comment('Usuário designado')
                ->constrained('users')
                ->cascadeOnDelete();
            
            // Percentual de alocação (0-100%)
            $table->decimal('allocation_percentage', 5, 2)
                ->default(100.00)
                ->comment('Percentual de alocação do usuário na tarefa');
            
            // Indica se é o responsável principal
            $table->boolean('is_primary')
                ->default(false)
                ->comment('Se é o responsável principal da tarefa');
            
            // Pode ser admin da tarefa (pode editar)
            $table->boolean('is_admin')
                ->default(false)
                ->comment('Se pode administrar a tarefa');
            
            $table->timestamps();
            
            // Impedir duplicação
            $table->unique(['task_id', 'user_id'], 'task_assignee_unique');
            
            // Índices
            $table->index('task_id');
            $table->index('user_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('task_assignees');
    }
};
