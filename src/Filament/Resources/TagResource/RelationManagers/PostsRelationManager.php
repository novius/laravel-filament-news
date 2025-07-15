<?php

namespace Novius\LaravelFilamentNews\Filament\Resources\TagResource\RelationManagers;

use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Enums\ActionsPosition;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Model;
use Novius\LaravelFilamentNews\Filament\Resources\PostResource;
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
            ->actions([
                Tables\Actions\ViewAction::make()
                    ->url(fn (NewsPost $record) => PostResource::getUrl('view', ['record' => $record])),
            ], ActionsPosition::BeforeColumns);
    }
}
