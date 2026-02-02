<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('project_demands', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->text('description')->nullable();
            $table->text('justification')->nullable();
            $table->foreignId('requester_id')->constrained('users');
            $table->string('status')->default('draft'); // draft, submitted, analyzing, approved, rejected, converted
            $table->string('priority')->default('medium'); // low, medium, high
            $table->foreignId('project_id')->nullable()->constrained('projects')->nullOnDelete();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('project_demands');
    }
};
