<?php

namespace Novius\LaravelFilamentNews\Tests;

use Novius\LaravelFilamentNews\LaravelFilamentNewsServiceProvider;
use Novius\LaravelLinkable\LaravelLinkableServiceProvider;
use Novius\LaravelMeta\LaravelMetaServiceProvider;
use Novius\LaravelPublishable\LaravelPublishableServiceProvider;
use Novius\LaravelTranslatable\LaravelTranslatableServiceProvider;
use Orchestra\Testbench\TestCase as Orchestra;

abstract class TestCase extends Orchestra
{
    protected function setUp(): void
    {
        parent::setup();

        $this->loadLaravelMigrations();
        $this->artisan('lang:add', ['locales' => 'fr']);
        $this->artisan('lang:add', ['locales' => 'en']);
    }

    protected function getPackageProviders($app): array
    {
        return [
            LaravelFilamentNewsServiceProvider::class,
            LaravelTranslatableServiceProvider::class,
            LaravelPublishableServiceProvider::class,
            LaravelMetaServiceProvider::class,
            LaravelLinkableServiceProvider::class,
            \LaravelLang\Config\ServiceProvider::class,
            \LaravelLang\Locales\ServiceProvider::class,
            \LaravelLang\Publisher\ServiceProvider::class,
        ];
    }
}
