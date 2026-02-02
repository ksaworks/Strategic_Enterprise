<?php

namespace Tests\Feature;

use App\Filament\Resources\ProjectDemands\Pages\ListProjectDemands;
use App\Models\ProjectDemand;
use App\Models\User;
use App\Models\Company;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

class ProjectDemandWorkflowTest extends TestCase
{
    use RefreshDatabase;

    public function setUp(): void
    {
        parent::setUp();
        // Ensure we have a company for the fallback in conversion logic
        Company::factory()->create();
    }

    public function test_can_create_demand()
    {
        $user = User::factory()->create();
        
        // Simulating Resource Form filling is complex, testing model directly for basic CRUD
        $demand = ProjectDemand::create([
            'title' => 'Nova Demanda Teste',
            'description' => 'Descrição da demanda',
            'requester_id' => $user->id,
            'status' => 'draft',
            'priority' => \App\Enums\Priority::MEDIUM,
        ]);

        $this->assertDatabaseHas('project_demands', [
            'id' => $demand->id,
            'title' => 'Nova Demanda Teste',
            'status' => 'draft',
        ]);
    }

    public function test_can_transition_status_via_table_actions()
    {
        $user = User::factory()->create();
        $demand = ProjectDemand::factory()->create([
            'requester_id' => $user->id,
            'status' => 'draft',
        ]);

        // Submit Action
        Livewire::test(ListProjectDemands::class)
            ->callTableAction('submit', $demand);

        $this->assertEquals('submitted', $demand->fresh()->status);

        // Analyze Action
        Livewire::test(ListProjectDemands::class)
            ->callTableAction('analyze', $demand);

        $this->assertEquals('analyzing', $demand->fresh()->status);

        // Approve Action
        Livewire::test(ListProjectDemands::class)
            ->callTableAction('approve', $demand);

        $this->assertEquals('approved', $demand->fresh()->status);
    }

    public function test_can_convert_demand_to_project()
    {
        $user = User::factory()->create();
        $this->actingAs($user); // Set auth for conversion action
        
        $demand = ProjectDemand::factory()->create([
            'requester_id' => $user->id,
            'status' => 'approved',
            'title' => 'Demanda Aprovada para Projeto',
        ]);

        // Convert Action
        Livewire::test(ListProjectDemands::class)
            ->callTableAction('convert', $demand);

        $demand->refresh();
        
        $this->assertEquals('converted', $demand->status);
        $this->assertNotNull($demand->project_id);
        
        $this->assertDatabaseHas('projects', [
            'id' => $demand->project_id,
            'name' => 'Demanda Aprovada para Projeto',
            'status' => 0, // Pending
        ]);
    }
}
