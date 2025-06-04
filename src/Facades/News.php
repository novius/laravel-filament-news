<?php

namespace Novius\LaravelFilamentNews\Facades;

use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Facade;
use LaravelLang\Locales\Data\LocaleData;
use Novius\LaravelFilamentNews\Services\NewsService;

/**
 * @method static Collection<string, LocaleData> locales()
 * @method static string getPostModel()
 * @method static string getPostResource()
 * @method static string getCategoryModel()
 * @method static string getCategoryResource()
 * @method static string getTagModel()
 * @method static string getTagResource()
 *
 * @see NewsService
 */
class News extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return NewsService::class;
    }
}
