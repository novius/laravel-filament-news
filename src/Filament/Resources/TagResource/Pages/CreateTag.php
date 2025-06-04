<?php

namespace Novius\LaravelFilamentNews\Filament\Resources\TagResource\Pages;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use LaravelLang\Locales\Facades\Locales;
use Novius\LaravelFilamentNews\Facades\News;
use Novius\LaravelFilamentNews\Models\NewsTag;
use Novius\LaravelFilamentTranslatable\Filament\Resources\Pages\CreateRecord;

class CreateTag extends CreateRecord
{
    public static function getResource(): string
    {
        return News::getTagResource();
    }

    protected function getHeaderActions(): array
    {
        return [

        ];
    }

    /**
     * @param  NewsTag  $parent
     */
    protected function getDataFromTranslate(Model $parent, string $locale): array
    {
        $data = $parent->attributesToArray();

        $data['name'] = $parent->name.' '.Locales::get($locale)->native;
        $data['slug'] = Str::slug($data['name']);

        return $data;
    }
}
