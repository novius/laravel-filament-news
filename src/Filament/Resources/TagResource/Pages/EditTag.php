<?php

namespace Novius\LaravelFilamentNews\Filament\Resources\TagResource\Pages;

use Filament\Actions\DeleteAction;
use Filament\Actions\ForceDeleteAction;
use Filament\Actions\RestoreAction;
use Filament\Actions\ViewAction;
use Filament\Resources\Pages\EditRecord;
use Novius\LaravelFilamentActionPreview\Filament\Actions\PreviewAction;
use Novius\LaravelFilamentNews\Facades\News;

class EditTag extends EditRecord
{
    public static function getResource(): string
    {
        return News::getTagResource();
    }

    protected function getHeaderActions(): array
    {
        return [
            PreviewAction::make(),
            ViewAction::make(),
            DeleteAction::make(),
            ForceDeleteAction::make(),
            RestoreAction::make(),
        ];
    }
}
