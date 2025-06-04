<?php

namespace Novius\LaravelFilamentNews\Filament\Resources\TagResource\Pages;

use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;
use Novius\LaravelFilamentNews\Facades\News;

class ListTag extends ListRecords
{
    public static function getResource(): string
    {
        return News::getTagResource();
    }

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
