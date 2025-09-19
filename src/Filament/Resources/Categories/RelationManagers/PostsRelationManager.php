<?php

namespace Novius\LaravelFilamentNews\Filament\Resources\Categories\RelationManagers;

use Filament\Actions\ViewAction;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Enums\RecordActionsPosition;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Model;
use Novius\LaravelFilamentNews\Filament\Resources\Posts\PostResource;
use Novius\LaravelFilamentNews\Models\NewsPost;
use Novius\LaravelFilamentPublishable\Filament\Tables\Columns\PublicationColumn;
use Novius\LaravelFilamentTranslatable\Filament\Tables\Columns\LocaleColumn;

class PostsRelationManager extends RelationManager
{
    protected static string $relationship = 'posts';

    public function isReadOnly(): bool
    {
        return false;
    }

    public static function getTitle(Model $ownerRecord, string $pageClass): string
    {
        return trans('laravel-filament-news::crud-post.resource_label');
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('title')
            ->columns([
                TextColumn::make('title')
                    ->label(trans('laravel-filament-news::crud-post.title'))
                    ->searchable()
                    ->sortable(),

                LocaleColumn::make('locale')
                    ->sortable(),

                PublicationColumn::make('publication_status')
                    ->sortable(),
            ])
            ->recordActions([
                ViewAction::make()
                    ->url(fn (NewsPost $record) => PostResource::getUrl('view', ['record' => $record])),
            ], RecordActionsPosition::BeforeColumns);
    }
}
