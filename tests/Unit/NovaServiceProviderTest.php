<?php

namespace Novius\LaravelFilamentNews\Tests;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Novius\LaravelFilamentNews\LaravelFilamentNewsServiceProvider;

uses(RefreshDatabase::class);

test('It creates the Filament Service Provider', function () {
    $provider = new LaravelFilamentNewsServiceProvider($this->app);

    expect($provider)->toBeInstanceOf(LaravelFilamentNewsServiceProvider::class);
});
