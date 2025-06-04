<?php

namespace Novius\LaravelFilamentNews\Filament\Resources\CategoryResource\Pages;

use Filament\Actions\DeleteAction;
use Filament\Actions\EditAction;
use Filament\Actions\ForceDeleteAction;
use Filament\Actions\RestoreAction;
use Filament\Resources\Pages\ViewRecord;
use Novius\LaravelFilamentActionPreview\Filament\Actions\PreviewAction;
use Novius\LaravelFilamentNews\Facades\News;

class ViewCategory extends ViewRecord
{
    public static function getResource(): string
    {
        return News::getCategoryResource();
    }

    protected function getHeaderActions(): array
    {
        return [
            PreviewAction::make(),
            EditAction::make(),
            DeleteAction::make(),
            ForceDeleteAction::make(),
            RestoreAction::make(),
        ];
    }
}
