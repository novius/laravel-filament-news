<?php

namespace Novius\LaravelFilamentNews\Filament\Resources\CategoryResource\Pages;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use LaravelLang\Locales\Facades\Locales;
use Novius\LaravelFilamentNews\Facades\News;
use Novius\LaravelFilamentNews\Models\NewsCategory;
use Novius\LaravelFilamentTranslatable\Filament\Resources\Pages\CreateRecord;

class CreateCategory extends CreateRecord
{
    public static function getResource(): string
    {
        return News::getCategoryResource();
    }

    protected function getHeaderActions(): array
    {
        return [

        ];
    }

    /**
     * @param  NewsCategory  $parent
     */
    protected function getDataFromTranslate(Model $parent, string $locale): array
    {
        $data = $parent->attributesToArray();

        $data['name'] = $parent->name.' '.Locales::get($locale)->native;
        $data['slug'] = Str::slug($data['name']);

        return $data;
    }
}
