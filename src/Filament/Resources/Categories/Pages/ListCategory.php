<?php

namespace Novius\LaravelFilamentNews\Filament\Resources\Categories\Pages;

use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;
use Novius\LaravelFilamentNews\Facades\News;

class ListCategory extends ListRecords
{
    public static function getResource(): string
    {
        return News::getCategoryResource();
    }

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
