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
        Schema::create('project_canvases', function (Blueprint $table) {
            $table->id();
            $table->foreignId('project_id')->nullable()->constrained('projects')->nullOnDelete();
            $table->string('name');
            $table->text('description')->nullable();
            
            $table->string('status')->default('draft'); // draft, converted
            
            $table->foreignId('owner_id')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('project_canvas_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('project_canvas_id')->constrained('project_canvases')->cascadeOnDelete();
            
            $table->string('section'); // Enum: ProjectCanvasSection
            $table->string('title');
            $table->text('content')->nullable();
            $table->string('color')->default('yellow'); // yellow, green, red, etc. (post-it color)
            
            $table->integer('order')->default(0);
            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('project_canvas_tables');
    }
};
