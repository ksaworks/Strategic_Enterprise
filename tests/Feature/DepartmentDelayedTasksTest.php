<?php

use App\Models\User;
use App\Models\Project;
use App\Models\Department;
use App\Models\Task;
use App\Models\Company;
use App\Models\Contact;

test('generates delayed tasks report successfully', function () {
    // Authenticate
    $user = User::factory()->create();
    $this->actingAs($user);

    // Create Data
    $company = Company::factory()->create();
    $department = Department::factory()->create(['company_id' => $company->id]);
    
    // Create Owner User
    $owner = User::factory()->create();
    
    // Link Owner to Department via Contact (CRITICAL for report logic)
    Contact::create([
        'company_id' => $company->id,
        'department_id' => $department->id,
        'user_id' => $owner->id,
        'first_name' => 'John',
        'last_name' => 'Doe',
        'email' => $owner->email,
    ]);

    $project = Project::factory()->create([
        'company_id' => $company->id,
        'owner_id' => $owner->id
    ]);
    
    // Create Delayed Task
    Task::factory()->create([
        'project_id' => $project->id,
        'owner_id' => $owner->id, // Task owner must remain the same for logic
        'name' => 'Delayed Task Alpha',
        'status' => 0, // Pending
        'end_date' => now()->subDays(5),
    ]);

    // Create On-Time Task
    Task::factory()->create([
        'project_id' => $project->id,
        'owner_id' => $owner->id,
        'name' => 'On Time Task Beta',
        'status' => 0, // Pending
        'end_date' => now()->addDays(5),
    ]);
    
    // Act
    $response = $this->get(route('reports.departments.delayed'));

    // Assert
    $response->assertStatus(200);
    $response->assertHeader('content-type', 'application/pdf');
    
    // Note: Can't easily inspect PDF content in test without library, but status 200 checks controller logic runs.
});
