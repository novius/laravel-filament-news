<?php

namespace Novius\LaravelFilamentNews\Filament\Resources;

use Exception;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Forms\Get;
use Filament\Forms\Set;
use Filament\Resources\Resource;
use Filament\Tables\Actions\ActionGroup;
use Filament\Tables\Actions\BulkActionGroup;
use Filament\Tables\Actions\DeleteAction;
use Filament\Tables\Actions\DeleteBulkAction;
use Filament\Tables\Actions\EditAction;
use Filament\Tables\Actions\ViewAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Support\Str;
use Illuminate\Validation\Rules\Unique;
use Novius\LaravelFilamentActionPreview\Filament\Tables\Actions\PreviewAction;
use Novius\LaravelFilamentNews\Facades\News;
use Novius\LaravelFilamentNews\Filament\Resources\TagResource\Pages;
use Novius\LaravelFilamentNews\Filament\Resources\TagResource\RelationManagers\PostsRelationManager;
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

    protected static ?string $navigationIcon = 'heroicon-o-tag';

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

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
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
            ->columns([
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
            ])
            ->filters([
                LocaleFilter::make('locale'),
            ])
            ->actions([
                EditAction::make(),
                DeleteAction::make(),
                ActionGroup::make([
                    PreviewAction::make(),
                    ViewAction::make(),
                ]),
            ])
            ->bulkActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListTag::route('/'),
            'create' => Pages\CreateTag::route('/create'),
            'view' => Pages\ViewTag::route('/{record:id}'),
            'edit' => Pages\EditTag::route('/{record:id}/edit'),
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
