<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     * 
     * Tarefa 8.1: Adiciona campos de hierarquia para projetos
     * - parent_id: permite projetos serem filhos de outros (programa → projeto → subprojeto)
     * - type: distingue entre project, program e portfolio
     * - department_id: vincula projeto a um departamento específico
     * - created_by_id: rastreia quem criou o projeto
     */
    public function up(): void
    {
        Schema::table('projects', function (Blueprint $table) {
            // Hierarquia de projetos (programa/portfolio → projeto → subprojeto)
            $table->foreignId('parent_id')
                ->nullable()
                ->after('id')
                ->comment('Projeto pai para hierarquia')
                ->constrained('projects')
                ->nullOnDelete();

            // Tipo de projeto
            $table->string('type', 20)
                ->default('project')
                ->after('parent_id')
                ->comment('Tipo: project, program, portfolio');

            // Vínculo com departamento
            $table->foreignId('department_id')
                ->nullable()
                ->after('company_id')
                ->comment('Departamento responsável')
                ->constrained('departments')
                ->nullOnDelete();

            // Criador do projeto
            $table->foreignId('created_by_id')
                ->nullable()
                ->after('owner_id')
                ->comment('Usuário que criou o projeto')
                ->constrained('users')
                ->nullOnDelete();

            // Índices para performance
            $table->index(['parent_id']);
            $table->index(['type']);
            $table->index(['department_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('projects', function (Blueprint $table) {
            // Remover foreign keys primeiro
            $table->dropConstrainedForeignId('parent_id');
            $table->dropConstrainedForeignId('department_id');
            $table->dropConstrainedForeignId('created_by_id');
            
            // Remover coluna type
            $table->dropColumn('type');
        });
    }
};
