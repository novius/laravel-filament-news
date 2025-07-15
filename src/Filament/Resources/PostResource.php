<?php

namespace Novius\LaravelFilamentNews\Filament\Resources;

use Exception;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Tabs;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
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
use Filament\Tables\Columns\ToggleColumn;
use Filament\Tables\Enums\ActionsPosition;
use Filament\Tables\Filters\TernaryFilter;
use Filament\Tables\Filters\TrashedFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Str;
use Illuminate\Validation\Rules\Unique;
use Novius\LaravelFilamentActionPreview\Filament\Tables\Actions\PreviewAction;
use Novius\LaravelFilamentNews\Facades\News;
use Novius\LaravelFilamentNews\Filament\Resources\PostResource\Pages;
use Novius\LaravelFilamentNews\Models\NewsCategory;
use Novius\LaravelFilamentNews\Models\NewsPost;
use Novius\LaravelFilamentPublishable\Filament\Forms\Components\ExpiredAt;
use Novius\LaravelFilamentPublishable\Filament\Forms\Components\PublicationStatus;
use Novius\LaravelFilamentPublishable\Filament\Forms\Components\PublishedAt;
use Novius\LaravelFilamentPublishable\Filament\Forms\Components\PublishedFirstAt;
use Novius\LaravelFilamentPublishable\Filament\Tables\Actions\PublicationBulkAction;
use Novius\LaravelFilamentPublishable\Filament\Tables\Columns\PublicationColumn;
use Novius\LaravelFilamentPublishable\Filament\Tables\Filters\PublicationStatusFilter;
use Novius\LaravelFilamentSlug\Filament\Forms\Components\Slug;
use Novius\LaravelFilamentTranslatable\Filament\Forms\Components\Locale;
use Novius\LaravelFilamentTranslatable\Filament\Tables\Columns\LocaleColumn;
use Novius\LaravelFilamentTranslatable\Filament\Tables\Columns\TranslationsColumn;
use Novius\LaravelFilamentTranslatable\Filament\Tables\Filters\LocaleFilter;
use Novius\LaravelMeta\Traits\FilamentResourceHasMeta;

class PostResource extends Resource
{
    use FilamentResourceHasMeta;

    protected static ?string $model = NewsPost::class;

    protected static ?string $slug = 'posts';

    protected static ?string $recordTitleAttribute = 'title';

    protected static ?string $navigationIcon = 'heroicon-o-newspaper';

    protected static ?string $recordRouteKeyName = 'id';

    public static function getModelLabel(): string
    {
        return trans('laravel-filament-news::crud-post.resource_label_singular');
    }

    public static function getPluralModelLabel(): string
    {
        return trans('laravel-filament-news::crud-post.resource_label');
    }

    public static function getNavigationGroup(): ?string
    {
        return trans('laravel-filament-news::news.navigation_group');
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Tabs::make()
                    ->columnSpanFull()
                    ->tabs([
                        Tabs\Tab::make(trans('laravel-filament-news::crud-post.panel_post_informations'))
                            ->schema(static::tabMain()),
                        Tabs\Tab::make(trans('laravel-filament-news::crud-post.panel_post_content'))
                            ->schema(static::tabContent()),
                        Tabs\Tab::make(trans('laravel-filament-news::crud-post.panel_seo_fields'))
                            ->schema(static::getFormSEOFields()),
                    ])
                    ->columns()
                    ->persistTabInQueryString(),
            ]);
    }

    protected static function tabMain(): array
    {
        return [
            $title = TextInput::make('title')
                ->label(trans('laravel-filament-news::crud-post.title'))
                ->required()
                ->live(onBlur: true)
                ->afterStateUpdated(function ($state, Set $set) {
                    $set('slug', Str::slug($state));
                }),

            Slug::make('slug')
                ->label(trans('laravel-filament-news::crud-post.slug'))
                ->fromField($title)
                ->required()
                ->string()
                ->regex('/^[a-zA-Z0-9-_]+$/')
                ->unique(
                    News::getPostModel(),
                    'slug',
                    ignoreRecord: true,
                    modifyRuleUsing: function (Unique $rule, Get $get) {
                        return $rule->where('locale', $get('locale'));
                    }
                ),

            Locale::make('locale')
                ->required(),

            Select::make('categories')
                ->label(trans('laravel-filament-news::crud-post.categories'))
                ->multiple()
                ->relationship(
                    'categories',
                    'name',
                    function (Builder|NewsCategory $query, Get $get) {
                        $locale = $get('locale');
                        if ($locale) {
                            $query->where('locale', $locale);
                        }
                    }
                ),

            Select::make('tags')
                ->label(trans('laravel-filament-news::crud-post.tags'))
                ->multiple()
                ->relationship(
                    'tags',
                    'name',
                    function (Builder|NewsCategory $query, Get $get) {
                        $locale = $get('locale');
                        if ($locale) {
                            $query->where('locale', $locale);
                        }
                    }
                ),

            Section::make(trans('laravel-filament-news::crud-post.panel_publication'))
                ->columns()
                ->schema([
                    PublicationStatus::make('publication_status'),
                    PublishedAt::make('published_at'),
                    ExpiredAt::make('expired_at'),
                    PublishedFirstAt::make('published_first_at'),
                ]),

            Hidden::make('locale_parent_id'),
        ];
    }

    protected static function tabContent(): array
    {
        return [
            Toggle::make('featured')
                ->label(trans('laravel-filament-news::crud-post.featured'))
                ->inline(false),

            Textarea::make('intro')
                ->label(trans('laravel-filament-news::crud-post.intro'))
                ->helperText(trans('laravel-filament-news::crud-post.intro_help')),

            RichEditor::make('content')
                ->columnSpanFull()
                ->label(trans('laravel-filament-news::crud-post.content')),

            FileUpload::make('featured_image')
                ->label(trans('laravel-filament-news::crud-post.featured_image'))
                ->image(),

            FileUpload::make('card_image')
                ->label(trans('laravel-filament-news::crud-post.card_image'))
                ->helperText(trans('laravel-filament-news::crud-post.card_image_help'))
                ->image(),
        ];
    }

    /**
     * @throws Exception
     */
    public static function table(Table $table): Table
    {
        return $table
            ->defaultSort('updated_at', 'desc')
            ->columns([
                TextColumn::make('id')
                    ->label(trans('laravel-filament-news::crud-post.id'))
                    ->sortable()
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),

                TextColumn::make('title')
                    ->label(trans('laravel-filament-news::crud-post.title'))
                    ->searchable()
                    ->sortable(),

                TextColumn::make('slug')
                    ->label(trans('laravel-filament-news::crud-post.slug'))
                    ->searchable()
                    ->sortable(),

                LocaleColumn::make('locale')
                    ->sortable(),
                TranslationsColumn::make('translations'),

                PublicationColumn::make('publication_status')
                    ->sortable(),

                ToggleColumn::make('featured')
                    ->sortable(),

                static::getTableSEOBadgeColumn(),

                TextColumn::make('created_at')
                    ->label(trans('laravel-filament-news::crud-post.created_at'))
                    ->dateTime()
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->sortable(),

                TextColumn::make('updated_at')
                    ->label(trans('laravel-filament-news::crud-post.updated_at'))
                    ->dateTime()
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->sortable(),
            ])
            ->filters([
                LocaleFilter::make('locale'),
                PublicationStatusFilter::make('publication_status'),
                TernaryFilter::make('featured')
                    ->label(trans('laravel-filament-news::crud-post.featured')),
                TrashedFilter::make(),
            ])
            ->actions([
                EditAction::make(),
                DeleteAction::make(),
                ActionGroup::make([
                    PreviewAction::make(),
                    ViewAction::make(),
                ]),
            ], ActionsPosition::BeforeColumns)
            ->bulkActions([
                BulkActionGroup::make([
                    PublicationBulkAction::make(),
                    DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListPost::route('/'),
            'create' => Pages\CreatePost::route('/create'),
            'view' => Pages\ViewPost::route('/{record:id}'),
            'edit' => Pages\EditPost::route('/{record:id}/edit'),
        ];
    }

    public static function getGloballySearchableAttributes(): array
    {
        return ['title', 'slug'];
    }
}
