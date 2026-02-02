<?php

namespace Tests\Feature;

use App\Filament\Resources\Tasks\TaskResource;
use App\Models\Project;
use App\Models\Task;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

class TaskResourceTest extends TestCase
{
    use RefreshDatabase;

    public function test_can_render_task_edit_page()
    {
        $user = User::factory()->create();
        
        // Grant permission (assuming Spatie Permission)
        // Or if using Silo/Gate based on string, we might need to seed
        // Ideally we mock or create permission
        $permission = \Spatie\Permission\Models\Permission::firstOrCreate(['name' => 'Update:Task']);
        $user->givePermissionTo($permission);
        $this->actingAs($user);
        
        $project = Project::factory()->create();
        $task = Task::factory()->create([
            'project_id' => $project->id,
        ]);

        $this->get(TaskResource::getUrl('edit', ['record' => $task]))
            ->assertSuccessful();
    }

    public function test_can_render_cost_items_relation_manager()
    {
        $this->actingAs(User::factory()->create());
        
        $project = Project::factory()->create();
        $task = Task::factory()->create([
            'project_id' => $project->id,
        ]);

        Livewire::test(\App\Filament\Resources\Tasks\RelationManagers\CostItemsRelationManager::class, [
            'ownerRecord' => $task,
            'pageClass' => \App\Filament\Resources\Tasks\Pages\EditTask::class,
        ])
        ->assertSuccessful();
    }
}
