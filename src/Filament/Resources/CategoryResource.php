<?php

namespace Novius\LaravelFilamentNews\Filament\Resources;

use Filament\Schemas\Schema;
use Filament\Schemas\Components\Tabs;
use Filament\Schemas\Components\Tabs\Tab;
use Filament\Schemas\Components\Utilities\Set;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Actions\EditAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\ActionGroup;
use Filament\Actions\ViewAction;
use Filament\Tables\Enums\RecordActionsPosition;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Novius\LaravelFilamentNews\Filament\Resources\CategoryResource\Pages\ListCategory;
use Novius\LaravelFilamentNews\Filament\Resources\CategoryResource\Pages\CreateCategory;
use Novius\LaravelFilamentNews\Filament\Resources\CategoryResource\Pages\ViewCategory;
use Novius\LaravelFilamentNews\Filament\Resources\CategoryResource\Pages\EditCategory;
use Exception;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Resource;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Support\Str;
use Illuminate\Validation\Rules\Unique;
use Novius\LaravelFilamentActionPreview\Filament\Tables\Actions\PreviewAction;
use Novius\LaravelFilamentNews\Facades\News;
use Novius\LaravelFilamentNews\Filament\Resources\CategoryResource\Pages;
use Novius\LaravelFilamentNews\Filament\Resources\CategoryResource\RelationManagers\PostsRelationManager;
use Novius\LaravelFilamentNews\Models\NewsCategory;
use Novius\LaravelFilamentPublishable\Filament\Tables\Actions\PublicationBulkAction;
use Novius\LaravelFilamentPublishable\Filament\Tables\Filters\PublicationStatusFilter;
use Novius\LaravelFilamentSlug\Filament\Forms\Components\Slug;
use Novius\LaravelFilamentTranslatable\Filament\Forms\Components\Locale;
use Novius\LaravelFilamentTranslatable\Filament\Tables\Columns\LocaleColumn;
use Novius\LaravelFilamentTranslatable\Filament\Tables\Columns\TranslationsColumn;
use Novius\LaravelFilamentTranslatable\Filament\Tables\Filters\LocaleFilter;
use Novius\LaravelMeta\Traits\FilamentResourceHasMeta;

class CategoryResource extends Resource
{
    use FilamentResourceHasMeta;

    protected static ?string $model = NewsCategory::class;

    protected static ?string $slug = 'categories';

    protected static ?string $recordTitleAttribute = 'name';

    protected static string | \BackedEnum | null $navigationIcon = 'heroicon-o-folder';

    protected static ?string $recordRouteKeyName = 'id';

    public static function getModelLabel(): string
    {
        return trans('laravel-filament-news::crud-category.resource_label_singular');
    }

    public static function getPluralModelLabel(): string
    {
        return trans('laravel-filament-news::crud-category.resource_label');
    }

    public static function getNavigationGroup(): ?string
    {
        return trans('laravel-filament-news::news.navigation_group');
    }

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Tabs::make()
                    ->columnSpanFull()
                    ->tabs([
                        Tab::make(trans('laravel-filament-news::crud-category.panel_post_informations'))
                            ->schema(static::tabMain()),
                        Tab::make(trans('laravel-filament-news::crud-category.panel_seo_fields'))
                            ->schema(static::getFormSEOFields()),
                    ])
                    ->columns()
                    ->persistTabInQueryString(),
            ]);
    }

    protected static function tabMain(): array
    {
        return [
            TextInput::make('name')
                ->label(trans('laravel-filament-news::crud-category.name'))
                ->required()
                ->live(onBlur: true)
                ->afterStateUpdated(function ($state, Set $set) {
                    $set('slug', Str::slug($state));
                }),

            Slug::make('slug')
                ->label(trans('laravel-filament-news::crud-category.slug'))
                ->required()
                ->string()
                ->regex('/^[a-zA-Z0-9-_]+$/')
                ->unique(
                    News::getCategoryModel(),
                    'slug',
                    ignoreRecord: true,
                    modifyRuleUsing: function (Unique $rule, Get $get) {
                        return $rule->where('locale', $get('locale'));
                    }
                ),

            Locale::make('locale')
                ->required(),

            Hidden::make('locale_parent_id'),
        ];
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
                    ->label(trans('laravel-filament-news::crud-category.id'))
                    ->sortable()
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),

                TextColumn::make('name')
                    ->label(trans('laravel-filament-news::crud-category.name'))
                    ->searchable()
                    ->sortable(),

                TextColumn::make('slug')
                    ->label(trans('laravel-filament-news::crud-category.slug'))
                    ->searchable()
                    ->sortable(),

                LocaleColumn::make('locale'),
                TranslationsColumn::make('translations'),

                static::getTableSEOBadgeColumn(),

                TextColumn::make('created_at')
                    ->label(trans('laravel-filament-news::crud-category.created_at'))
                    ->dateTime()
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->sortable(),

                TextColumn::make('updated_at')
                    ->label(trans('laravel-filament-news::crud-category.updated_at'))
                    ->dateTime()
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->sortable(),
            ])
            ->filters([
                LocaleFilter::make('locale'),
                PublicationStatusFilter::make('publication_status'),
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
                    PublicationBulkAction::make(),
                    DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => ListCategory::route('/'),
            'create' => CreateCategory::route('/create'),
            'view' => ViewCategory::route('/{record:id}'),
            'edit' => EditCategory::route('/{record:id}/edit'),
        ];
    }

    public static function getGloballySearchableAttributes(): array
    {
        return ['name', 'slug'];
    }

    public static function getRelations(): array
    {
        return [
            PostsRelationManager::class,
        ];
    }
}
