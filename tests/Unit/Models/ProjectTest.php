<?php

use App\Models\Company;
use App\Models\Project;
use App\Models\User;

test('it belongs to a company', function () {
    $company = Company::factory()->create();
    $project = Project::factory()->create(['company_id' => $company->id]);

    expect($project->company)->toBeInstanceOf(Company::class)
        ->and($project->company->id)->toBe($company->id);
});

test('it belongs to an owner', function () {
    $user = User::factory()->create();
    $project = Project::factory()->create(['owner_id' => $user->id]);

    expect($project->owner)->toBeInstanceOf(User::class)
        ->and($project->owner->id)->toBe($user->id);
});

test('it casts dates correctly', function () {
    $project = Project::factory()->create([
        'start_date' => '2026-01-26',
        'is_active' => 1,
    ]);

    expect($project->start_date)->toBeInstanceOf(\Carbon\Carbon::class)
        ->and($project->is_active)->toBeTrue();
});
