<?php

namespace Novius\LaravelFilamentNews;

use Illuminate\Support\ServiceProvider;
use Novius\LaravelFilamentNews\Console\FrontControllerCommand;
use Novius\LaravelFilamentNews\Services\NewsService;
use Novius\LaravelLinkable\Facades\Linkable;

class LaravelFilamentNewsServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        $this->app->bind(NewsService::class, static function () {
            return new NewsService(config('laravel-filament-news'));
        });

        $this->mergeConfigFrom(__DIR__.'/../config/laravel-filament-news.php', 'laravel-filament-news');
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        $this->app->booted(function () {
            Linkable::addModels(array_filter(config('laravel-filament-news.models')));
            Linkable::addRoutes(array_flip(array_filter([
                trans('laravel-filament-news::crud-post.resource_label') => config('laravel-filament-news.front_routes_name.posts'),
                trans('laravel-filament-news::crud-category.resource_label') => config('laravel-filament-news.front_routes_name.categories'),
            ])));
        });

        $this->loadMigrationsFrom(__DIR__.'/../database/migrations');
        $this->loadTranslationsFrom(__DIR__.'/../lang', 'laravel-filament-news');

        $this->publishes([
            __DIR__.'/../lang' => lang_path('vendor/laravel-filament-news'),
        ], 'lang');

        $this->publishes([
            __DIR__.'/../database/migrations' => database_path('migrations'),
        ], 'migrations');

        $this->publishes([
            __DIR__.'/../config/laravel-filament-news.php' => config_path('laravel-filament-news.php'),
        ], 'config');

        if ($this->app->runningInConsole()) {
            $this->commands([
                FrontControllerCommand::class,
            ]);
        }
    }
}
