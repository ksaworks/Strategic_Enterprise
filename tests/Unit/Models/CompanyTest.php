<?php

use App\Models\Company;

test('it can have a parent company', function () {
    $parent = Company::factory()->create();
    $child = Company::factory()->create(['parent_id' => $parent->id]);

    expect($child->parent)->toBeInstanceOf(Company::class)
        ->and($child->parent->id)->toBe($parent->id);
});

test('it can have children companies', function () {
    $parent = Company::factory()->create();
    $child = Company::factory()->create(['parent_id' => $parent->id]);

    expect($parent->children)->toHaveCount(1)
        ->and($parent->children->first()->id)->toBe($child->id);
});
