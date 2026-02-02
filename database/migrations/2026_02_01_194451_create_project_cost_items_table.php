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
        Schema::create('project_cost_items', function (Blueprint $table) {
            $table->id();
            
            $table->foreignId('task_id')
                ->constrained('tasks')
                ->cascadeOnDelete();
            
            $table->string('name', 255)
                ->comment('Nome do item de custo');
            
            $table->string('category', 50)
                ->default('material')
                ->comment('Categoria: labor, material, service, equipment, other');
            
            $table->string('unit', 30)
                ->nullable()
                ->comment('Unidade de medida: m², kg, un, h, etc');
            
            $table->decimal('quantity', 12, 2)
                ->default(1);
            
            $table->decimal('unit_price', 12, 2)
                ->default(0);
            
            $table->decimal('total_price', 14, 2)
                ->virtualAs('quantity * unit_price')
                ->comment('Custo total calculado');
            
            $table->text('description')
                ->nullable();
            
            $table->timestamps();
            $table->softDeletes();
            
            $table->index('task_id');
            $table->index('category');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('project_cost_items');
    }
};
