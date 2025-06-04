<?php

namespace Novius\LaravelFilamentNews\Filament;

use Filament\Contracts\Plugin;
use Filament\Panel;
use Filament\Resources\Resource;
use InvalidArgumentException;
use Novius\LaravelFilamentNews\Facades\News;

class NewsPlugin implements Plugin
{
    public function __construct()
    {
        if (! is_subclass_of(News::getPostResource(), Resource::class)) {
            throw new InvalidArgumentException('The post resource must be a subclass of '.Resource::class);
        }
        if (! is_subclass_of(News::getCategoryResource(), Resource::class)) {
            throw new InvalidArgumentException('The post resource must be a subclass of '.Resource::class);
        }
        if (! is_subclass_of(News::getTagResource(), Resource::class)) {
            throw new InvalidArgumentException('The post resource must be a subclass of '.Resource::class);
        }
    }

    public static function make(): static
    {
        return app(static::class);
    }

    public function getId(): string
    {
        return 'laravel-filament-news';
    }

    public function register(Panel $panel): void
    {
        $panel->resources([
            News::getPostResource(),
            News::getCategoryResource(),
            News::getTagResource(),
        ]);
    }

    public function boot(Panel $panel): void {}
}
