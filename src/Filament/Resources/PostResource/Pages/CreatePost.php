<?php

namespace Novius\LaravelFilamentNews\Filament\Resources\PostResource\Pages;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use LaravelLang\Locales\Facades\Locales;
use Novius\LaravelFilamentNews\Facades\News;
use Novius\LaravelFilamentNews\Models\NewsPost;
use Novius\LaravelFilamentTranslatable\Filament\Resources\Pages\CreateRecord;

class CreatePost extends CreateRecord
{
    public static function getResource(): string
    {
        return News::getPostResource();
    }

    protected function getHeaderActions(): array
    {
        return [

        ];
    }

    /**
     * @param  NewsPost  $parent
     */
    protected function getDataFromTranslate(Model $parent, string $locale): array
    {
        $data = $parent->attributesToArray();

        $data['title'] = $parent->title.' '.Locales::get($locale)->native;
        $data['slug'] = Str::slug($data['title']);

        return $data;
    }
}
