<?php

namespace Tests\Feature;

use App\Models\LessonLearned;
use App\Models\Project;
use App\Models\ProjectViability;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ProjectViabilityTest extends TestCase
{
    use RefreshDatabase;

    public function test_can_create_project_viability()
    {
        $user = User::factory()->create();
        $project = Project::factory()->create();

        // Testing direct model creation as we trust Filament for form handling
        $viability = ProjectViability::create([
            'project_id' => $project->id,
            'technical_feasibility' => 'Possible',
            'financial_return' => 50000,
            'payback_period' => 12,
            'score' => 85,
            'decision' => 'approved',
            'analyzed_by_id' => $user->id,
            'analyzed_at' => now(),
        ]);

        $this->assertDatabaseHas('project_viabilities', [
            'project_id' => $project->id,
            'score' => 85,
            'decision' => 'approved',
        ]);
        
        $this->assertTrue($project->viabilities->contains($viability));
    }

    public function test_can_create_lesson_learned()
    {
        $user = User::factory()->create();
        $project = Project::factory()->create();

        $this->actingAs($user);

        $lesson = LessonLearned::create([
            'project_id' => $project->id,
            'category' => 'risk',
            'type' => 'negative',
            'description' => 'Unforeseen weather delays',
            'reported_by_id' => $user->id,
        ]);

        $this->assertDatabaseHas('project_lessons_learned', [
            'id' => $lesson->id,
            'category' => 'risk',
            'type' => 'negative',
        ]);

        $this->assertTrue($project->lessonsLearned->contains($lesson));
    }

    public function test_viability_defaults()
    {
        $project = Project::factory()->create();
        $viability = ProjectViability::create([
            'project_id' => $project->id,
        ]);
        
        // Refresh to get default values from DB if any
        $viability->refresh();

        $this->assertEquals(0, $viability->score);
        $this->assertEquals('on_hold', $viability->decision);
    }
}
