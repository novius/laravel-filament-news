<?php

namespace Novius\LaravelFilamentNews\Filament\Resources\Tags;

use Exception;
use Filament\Actions\ActionGroup;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Resource;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Components\Utilities\Set;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Enums\RecordActionsPosition;
use Filament\Tables\Table;
use Illuminate\Support\Str;
use Illuminate\Validation\Rules\Unique;
use Novius\LaravelFilamentActionPreview\Filament\Tables\Actions\PreviewAction;
use Novius\LaravelFilamentNews\Facades\News;
use Novius\LaravelFilamentNews\Filament\Resources\Tags\Pages\CreateTag;
use Novius\LaravelFilamentNews\Filament\Resources\Tags\Pages\EditTag;
use Novius\LaravelFilamentNews\Filament\Resources\Tags\Pages\ListTag;
use Novius\LaravelFilamentNews\Filament\Resources\Tags\Pages\ViewTag;
use Novius\LaravelFilamentNews\Filament\Resources\Tags\RelationManagers\PostsRelationManager;
use Novius\LaravelFilamentNews\Models\NewsTag;
use Novius\LaravelFilamentSlug\Filament\Forms\Components\Slug;
use Novius\LaravelFilamentTranslatable\Filament\Forms\Components\Locale;
use Novius\LaravelFilamentTranslatable\Filament\Tables\Columns\LocaleColumn;
use Novius\LaravelFilamentTranslatable\Filament\Tables\Columns\TranslationsColumn;
use Novius\LaravelFilamentTranslatable\Filament\Tables\Filters\LocaleFilter;
use Novius\LaravelMeta\Traits\FilamentResourceHasMeta;

class TagResource extends Resource
{
    use FilamentResourceHasMeta;

    protected static ?string $model = NewsTag::class;

    protected static ?string $slug = 'tags';

    protected static ?string $recordTitleAttribute = 'name';

    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-tag';

    protected static ?string $recordRouteKeyName = 'id';

    public static function getModelLabel(): string
    {
        return trans('laravel-filament-news::crud-tag.resource_label_singular');
    }

    public static function getPluralModelLabel(): string
    {
        return trans('laravel-filament-news::crud-tag.resource_label');
    }

    public static function getNavigationGroup(): ?string
    {
        return trans('laravel-filament-news::news.navigation_group');
    }

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('name')
                    ->label(trans('laravel-filament-news::crud-tag.name'))
                    ->required()
                    ->live(onBlur: true)
                    ->columnSpanFull()
                    ->afterStateUpdated(function ($state, Set $set) {
                        $set('slug', Str::slug($state));
                    }),

                Slug::make('slug')
                    ->label(trans('laravel-filament-news::crud-tag.slug'))
                    ->required()
                    ->string()
                    ->regex('/[a-zA-Z0-9-_]+$/')
                    ->unique(
                        News::getTagModel(),
                        'slug',
                        ignoreRecord: true,
                        modifyRuleUsing: function (Unique $rule, Get $get) {
                            return $rule->where('locale', $get('locale'));
                        }
                    ),

                Locale::make('locale')
                    ->required(),

                Hidden::make('locale_parent_id'),
            ]);
    }

    /**
     * @throws Exception
     */
    public static function table(Table $table): Table
    {
        return $table
            ->defaultSort('name')
            ->columns([
                TextColumn::make('id')
                    ->label(trans('laravel-filament-news::crud-tag.id'))
                    ->sortable()
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),

                TextColumn::make('name')
                    ->label(trans('laravel-filament-news::crud-tag.name'))
                    ->searchable()
                    ->sortable(),

                TextColumn::make('slug')
                    ->label(trans('laravel-filament-news::crud-tag.slug'))
                    ->searchable()
                    ->sortable(),

                LocaleColumn::make('locale'),

                TranslationsColumn::make('translations'),

                TextColumn::make('created_at')
                    ->label(trans('laravel-filament-news::crud-tag.created_at'))
                    ->dateTime()
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->sortable(),

                TextColumn::make('updated_at')
                    ->label(trans('laravel-filament-news::crud-tag.updated_at'))
                    ->dateTime()
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->sortable(),
            ])
            ->filters([
                LocaleFilter::make('locale'),
            ])
            ->recordActions([
                EditAction::make(),
                DeleteAction::make(),
                ActionGroup::make([
                    PreviewAction::make(),
                    ViewAction::make(),
                ]),
            ], RecordActionsPosition::BeforeColumns)
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => ListTag::route('/'),
            'create' => CreateTag::route('/create'),
            'view' => ViewTag::route('/{record:id}'),
            'edit' => EditTag::route('/{record:id}/edit'),
        ];
    }

    public static function getGloballySearchableAttributes(): array
    {
        return ['name'];
    }

    public static function getRelations(): array
    {
        return [
            PostsRelationManager::class,
        ];
    }
}
