<?php

namespace Novius\LaravelFilamentNews\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Carbon;
use Novius\LaravelFilamentNews\Database\Factories\NewsCategoryFactory;
use Novius\LaravelFilamentNews\Facades\News;
use Novius\LaravelLinkable\Configs\LinkableConfig;
use Novius\LaravelLinkable\Traits\Linkable;
use Novius\LaravelMeta\Enums\IndexFollow;
use Novius\LaravelMeta\MetaModelConfig;
use Novius\LaravelMeta\Traits\HasMeta;
use Novius\LaravelTranslatable\Traits\Translatable;
use Spatie\Sluggable\HasSlug;
use Spatie\Sluggable\SlugOptions;

/**
 * @property int $id
 * @property string $name
 * @property string $slug
 * @property string $locale
 * @property int $locale_parent_id
 * @property array $extras
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property Carbon|null $deleted_at
 * @property array<array-key, mixed>|null $meta
 * @property-read string|null $seo_robots
 * @property-read string|null $seo_title
 * @property-read string|null $seo_description
 * @property-read string|null $seo_keywords
 * @property-read string|null $og_type
 * @property-read string|null $og_title
 * @property-read string|null $og_description
 * @property-read string|null $og_image
 * @property-read string|null $og_image_url
 * @property-read NewsCategory|null $localParent
 * @property-read Collection<int, NewsPost> $posts
 * @property-read Collection<int, NewsCategory> $translations
 * @property-read Collection<int, NewsCategory> $translationsWithDeleted
 *
 * @method static NewsCategoryFactory factory($count = null, $state = [])
 * @method static Builder<static>|NewsCategory indexableByRobots()
 * @method static Builder<static>|NewsCategory newModelQuery()
 * @method static Builder<static>|NewsCategory newQuery()
 * @method static Builder<static>|NewsCategory notIndexableByRobots()
 * @method static Builder<static>|NewsCategory onlyTrashed()
 * @method static Builder<static>|NewsCategory query()
 * @method static Builder<static>|NewsCategory withLocale(?string $locale)
 * @method static Builder<static>|NewsCategory withTrashed()
 * @method static Builder<static>|NewsCategory withoutTrashed()
 *
 * @mixin Model
 */
class NewsCategory extends Model
{
    use HasFactory;
    use HasMeta;
    use HasSlug;
    use Linkable;
    use SoftDeletes;
    use Translatable;

    protected $table = 'filament_news_categories';

    protected $guarded = ['id'];

    protected $casts = [
        'extras' => 'json',
    ];

    protected static function booted(): void
    {
        static::saving(static function (NewsCategory $category) {
            if (empty($category->locale) && News::locales()->count() === 1) {
                $category->locale = News::locales()->first()->code;
            }
        });
    }

    public function getRouteKeyName(): string
    {
        return 'slug';
    }

    public function getSlugOptions(): SlugOptions
    {
        return SlugOptions::create()
            ->generateSlugsFrom('name')
            ->saveSlugsTo('slug')
            ->doNotGenerateSlugsOnUpdate();
    }

    public function getMetaConfig(): MetaModelConfig
    {
        if (! isset($this->metaConfig)) {
            $this->metaConfig = MetaModelConfig::make()
                ->setDefaultSeoRobots(IndexFollow::index_follow)
                ->setFallbackTitle('name');
        }

        return $this->metaConfig;
    }

    protected ?LinkableConfig $_linkableConfig;

    public function linkableConfig(): ?LinkableConfig
    {
        $route = config('laravel-filament-news.front_routes_name.post');
        $routeParameterName = config('laravel-filament-news.front_routes_parameters.post');
        if (empty($routeParameterName) && empty($route)) {
            return null;
        }

        if (! isset($this->_linkableConfig)) {
            $this->_linkableConfig = new LinkableConfig(
                routeName: $route,
                routeParameterName: $routeParameterName,
                optionLabel: 'name',
                optionGroup: trans('laravel-filament-news::crud-category.resource_label'),
                resolveQuery: function (Builder|NewsCategory $query) {
                    $query->where('locale', app()->currentLocale());
                },
            );
        }

        return $this->_linkableConfig;
    }

    public function localParent(): HasOne
    {
        return $this->hasOne(static::class, 'id', 'locale_parent_id');
    }

    public function posts(): BelongsToMany
    {
        return $this->belongsToMany(News::getPostModel(), 'filament_news_post_category', 'news_category_id', 'news_post_id');
    }

    /**
     * {@inheritdoc}
     */
    protected static function newFactory(): Factory
    {
        return NewsCategoryFactory::new();
    }
}
