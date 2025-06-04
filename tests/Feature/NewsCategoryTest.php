<?php

namespace Novius\LaravelFilamentNews\Tests;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Novius\LaravelFilamentNews\Models\NewsCategory;

uses(RefreshDatabase::class);

it('has a name', function () {
    expect(NewsCategory::factory()->create()->name)->toBeString();
});

it('has a slug', function () {
    expect(NewsCategory::factory()->create()->slug)->toBeString();
});

it('has a locale', function () {
    expect(NewsCategory::factory()->create()->locale)->toBeString();
});
