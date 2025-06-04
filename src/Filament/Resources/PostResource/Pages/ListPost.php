<?php

namespace Novius\LaravelFilamentNews\Filament\Resources\PostResource\Pages;

use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;
use Novius\LaravelFilamentNews\Facades\News;

class ListPost extends ListRecords
{
    public static function getResource(): string
    {
        return News::getPostResource();
    }

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
