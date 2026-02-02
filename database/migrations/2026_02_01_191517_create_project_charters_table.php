<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('project_charters', function (Blueprint $table) {
            $table->id();
            
            // Relacionamentos
            $table->foreignId('project_id')->unique()->constrained()->onDelete('cascade');
            $table->foreignId('created_by_id')->constrained('users');
            $table->foreignId('approved_by_id')->nullable()->constrained('users')->nullOnDelete();
            
            // Identificação
            $table->string('title');
            $table->string('version')->default('1.0');
            $table->string('status')->default('draft'); // CharterStatus enum
            $table->dateTime('approved_at')->nullable();
            
            // Conteúdo (Rich Text / Long Text)
            $table->longText('objective')->nullable()->comment('Objetivo do projeto');
            $table->longText('scope')->nullable()->comment('Escopo');
            $table->longText('out_of_scope')->nullable()->comment('Fora do escopo');
            $table->longText('deliverables')->nullable()->comment('Entregas principais');
            $table->longText('stakeholders')->nullable()->comment('Partes interessadas');
            $table->longText('constraints')->nullable()->comment('Restrições');
            $table->longText('assumptions')->nullable()->comment('Premissas');
            $table->longText('risks')->nullable()->comment('Riscos iniciais');
            $table->longText('budget_summary')->nullable()->comment('Resumo orçamentário');
            $table->longText('timeline_summary')->nullable()->comment('Resumo do cronograma');
            $table->longText('approval_justification')->nullable()->comment('Justificativa aprovação/rejeição');
            
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('project_charters');
    }
};
