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
        Schema::create('swot_analyses', function (Blueprint $table) {
            $table->id();
            
            $table->foreignId('project_id')
                ->unique()
                ->constrained()
                ->cascadeOnDelete();
            
            $table->foreignId('created_by_id')
                ->constrained('users');
            
            $table->string('title', 255)->nullable();
            
            // Os 4 quadrantes do SWOT (Rich Text)
            $table->longText('strengths')
                ->nullable()
                ->comment('Forças - Pontos fortes internos');
            
            $table->longText('weaknesses')
                ->nullable()
                ->comment('Fraquezas - Pontos fracos internos');
            
            $table->longText('opportunities')
                ->nullable()
                ->comment('Oportunidades - Fatores externos positivos');
            
            $table->longText('threats')
                ->nullable()
                ->comment('Ameaças - Fatores externos negativos');
            
            // Análise cruzada (opcional para futuro)
            $table->longText('notes')
                ->nullable()
                ->comment('Notas adicionais e conclusões');
            
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('swot_analyses');
    }
};
