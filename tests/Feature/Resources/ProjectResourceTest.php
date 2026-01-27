<?php

use App\Filament\Resources\Projects\ProjectResource;
use App\Models\Project;
use App\Models\User;
use Spatie\Permission\Models\Role;

beforeEach(function () {
    app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

    $this->user = User::factory()->create();
    $role = Role::firstOrCreate(['name' => 'super_admin', 'guard_name' => 'web']);
    $this->user->assignRole($role);

    // Explicit permissions removed as Policy handles super_admin check
});

test('can render index page', function () {
    $this->actingAs($this->user)
        ->get(ProjectResource::getUrl('index'))
        ->assertSuccessful();
});

test('can render create page', function () {
    $this->actingAs($this->user)
        ->get(ProjectResource::getUrl('create'))
        ->assertSuccessful();
});

test('can render edit page', function () {
    $project = Project::factory()->create();

    // Debug permission
    dump('Can Update: ' . ($this->user->can('Update:Project') ? 'YES' : 'NO'));
    dump('Can View: ' . ($this->user->can('View:Project') ? 'YES' : 'NO'));

    $this->actingAs($this->user)
        ->get(ProjectResource::getUrl('edit', ['record' => $project]))
        ->assertSuccessful();
});
