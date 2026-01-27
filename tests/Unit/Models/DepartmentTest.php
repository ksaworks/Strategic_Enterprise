<?php

use App\Models\Department;
use App\Models\Company;
use App\Models\User;

test('it belongs to a company', function () {
    $company = Company::factory()->create();
    $department = Department::factory()->create(['company_id' => $company->id]);

    expect($department->company)->toBeInstanceOf(Company::class)
        ->and($department->company->id)->toBe($company->id);
});

test('it belongs to a manager', function () {
    $user = User::factory()->create();
    $department = Department::factory()->create(['manager_id' => $user->id]);

    expect($department->manager)->toBeInstanceOf(User::class)
        ->and($department->manager->id)->toBe($user->id);
});

test('it can have a parent department', function () {
    $parent = Department::factory()->create();
    $child = Department::factory()->create(['parent_id' => $parent->id]);

    expect($child->parent)->toBeInstanceOf(Department::class)
        ->and($child->parent->id)->toBe($parent->id);
});

test('it can have children departments', function () {
    $parent = Department::factory()->create();
    $child = Department::factory()->create(['parent_id' => $parent->id]);

    expect($parent->children)->toHaveCount(1)
        ->and($parent->children->first()->id)->toBe($child->id);
});
