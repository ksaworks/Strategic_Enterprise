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
        Schema::create('project_expenses', function (Blueprint $table) {
            $table->id();
            
            $table->foreignId('project_id')
                ->constrained()
                ->cascadeOnDelete();
            
            $table->foreignId('task_id')
                ->nullable()
                ->constrained('tasks')
                ->nullOnDelete();
            
            $table->foreignId('cost_item_id')
                ->nullable()
                ->constrained('project_cost_items')
                ->nullOnDelete();
            
            $table->foreignId('created_by_id')
                ->constrained('users');
            
            $table->string('description', 255);
            
            $table->string('category', 50)
                ->default('material')
                ->comment('Categoria: labor, material, service, equipment, other');
            
            $table->decimal('amount', 14, 2);
            
            $table->date('expense_date');
            
            $table->string('status', 30)
                ->default('pending')
                ->comment('Status: pending, approved, rejected');
            
            $table->foreignId('approved_by_id')
                ->nullable()
                ->constrained('users')
                ->nullOnDelete();
            
            $table->timestamp('approved_at')->nullable();
            
            $table->text('notes')->nullable();
            
            $table->string('receipt_path')->nullable();
            
            $table->timestamps();
            $table->softDeletes();
            
            $table->index(['project_id', 'status']);
            $table->index('expense_date');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('project_expenses');
    }
};
