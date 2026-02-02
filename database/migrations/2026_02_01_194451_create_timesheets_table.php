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
        Schema::create('timesheets', function (Blueprint $table) {
            $table->id();
            
            $table->foreignId('user_id')
                ->constrained()
                ->cascadeOnDelete();
            
            $table->foreignId('task_id')
                ->constrained('tasks')
                ->cascadeOnDelete();
            
            $table->date('work_date');
            
            $table->decimal('hours', 5, 2)
                ->comment('Horas trabalhadas');
            
            $table->time('start_time')->nullable();
            $table->time('end_time')->nullable();
            
            $table->text('description')
                ->nullable()
                ->comment('Descrição do trabalho realizado');
            
            $table->decimal('hourly_rate', 10, 2)
                ->nullable()
                ->comment('Valor hora no momento do registro');
            
            $table->decimal('labor_cost', 12, 2)
                ->virtualAs('hours * COALESCE(hourly_rate, 0)')
                ->comment('Custo calculado da mão de obra');
            
            $table->string('status', 30)
                ->default('pending')
                ->comment('Status: pending, approved, rejected');
            
            $table->foreignId('approved_by_id')
                ->nullable()
                ->constrained('users')
                ->nullOnDelete();
            
            $table->timestamp('approved_at')->nullable();
            
            $table->text('rejection_reason')->nullable();
            
            $table->timestamps();
            $table->softDeletes();
            
            $table->index(['user_id', 'work_date']);
            $table->index(['task_id', 'status']);
            $table->unique(['user_id', 'task_id', 'work_date'], 'unique_user_task_date');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('timesheets');
    }
};
