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
use Illuminate\Support\Str;
use Novius\LaravelFilamentNews\Database\Factories\NewsPostFactory;
use Novius\LaravelFilamentNews\Facades\News;
use Novius\LaravelLinkable\Configs\LinkableConfig;
use Novius\LaravelLinkable\Traits\Linkable;
use Novius\LaravelMeta\Enums\IndexFollow;
use Novius\LaravelMeta\MetaModelConfig;
use Novius\LaravelMeta\Traits\HasMeta;
use Novius\LaravelPublishable\Enums\PublicationStatus;
use Novius\LaravelPublishable\Traits\Publishable;
use Novius\LaravelTranslatable\Support\TranslatableModelConfig;
use Novius\LaravelTranslatable\Traits\Translatable;
use Spatie\Sluggable\HasSlug;
use Spatie\Sluggable\SlugOptions;

/**
 * @property int $id
 * @property string $title
 * @property string $slug
 * @property string $locale
 * @property int $locale_parent_id
 * @property bool $featured
 * @property string $intro
 * @property string $content
 * @property string $featured_image
 * @property string $card_image
 * @property string $post_status
 * @property NewsCategory $categories
 * @property NewsTag $tags
 * @property string $preview_token
 * @property array $extras
 * @property PublicationStatus $publication_status
 * @property Carbon|null $published_first_at
 * @property Carbon|null $published_at
 * @property Carbon|null $expired_at
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
 * @property-read NewsPost|null $localParent
 * @property-read Collection<int, NewsPost> $translations
 * @property-read Collection<int, NewsPost> $translationsWithDeleted
 *
 * @method static NewsPostFactory factory($count = null, $state = [])
 * @method static Builder<static>|NewsPost indexableByRobots()
 * @method static Builder<static>|NewsPost newModelQuery()
 * @method static Builder<static>|NewsPost newQuery()
 * @method static Builder<static>|NewsPost notIndexableByRobots()
 * @method static Builder<static>|NewsPost notPublished()
 * @method static Builder<static>|NewsPost onlyDrafted()
 * @method static Builder<static>|NewsPost onlyExpired()
 * @method static Builder<static>|NewsPost onlyTrashed()
 * @method static Builder<static>|NewsPost onlyWillBePublished()
 * @method static Builder<static>|NewsPost published()
 * @method static Builder<static>|NewsPost query()
 * @method static Builder<static>|NewsPost withLocale(?string $locale)
 * @method static Builder<static>|NewsPost withTrashed()
 * @method static Builder<static>|NewsPost withoutTrashed()
 *
 * @mixin Model
 */
class NewsPost extends Model
{
    use HasFactory;
    use HasMeta;
    use HasSlug;
    use Linkable;
    use Publishable;
    use SoftDeletes;
    use Translatable;

    protected $table = 'news_posts';

    protected $guarded = ['id'];

    protected $casts = [
        'extras' => 'json',
    ];

    protected static function booted(): void
    {
        static::saving(static function (NewsPost $post) {
            if (empty($post->preview_token)) {
                $post->preview_token = Str::random();
            }

            if (empty($post->locale) && News::locales()->count() === 1) {
                $post->locale = News::locales()->first()->code;
            }
        });
    }

    public function getRouteKeyName(): string
    {
        return 'slug';
    }

    public function isFeatured(): bool
    {
        return $this->featured;
    }

    public function getSlugOptions(): SlugOptions
    {
        return SlugOptions::create()
            ->generateSlugsFrom('title')
            ->saveSlugsTo('slug')
            ->doNotGenerateSlugsOnUpdate();
    }

    public function getMetaConfig(): MetaModelConfig
    {
        if (! isset($this->metaConfig)) {
            $this->metaConfig = MetaModelConfig::make()
                ->setDefaultSeoRobots(IndexFollow::index_follow)
                ->setFallbackTitle('title')
                ->setFallbackDescription('intro')
                ->setFallbackImage('featured_image');
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
                optionLabel: 'title',
                optionGroup: trans('laravel-filament-news::crud-post.resource_label'),
                resolveQuery: function (Builder|NewsPost $query) {
                    $query->where('locale', app()->currentLocale());
                },
                resolveNotPreviewQuery: function (Builder|NewsPost $query) {
                    $query->published();
                },
                previewTokenField: 'preview_token'
            );
        }

        return $this->_linkableConfig;
    }

    public function translatableConfig(): TranslatableModelConfig
    {
        return new TranslatableModelConfig(config('laravel-filament-news.locales'));
    }

    public function localParent(): HasOne
    {
        return $this->hasOne(static::class, 'id', 'locale_parent_id');
    }

    public function categories(): BelongsToMany
    {
        return $this->belongsToMany(News::getCategoryModel(), 'news_post_category', 'news_post_id', 'news_category_id');
    }

    public function tags(): BelongsToMany
    {
        return $this->belongsToMany(News::getTagModel(), 'news_post_tag', 'news_post_id', 'news_tag_id');
    }

    /**
     * {@inheritdoc}
     */
    protected static function newFactory(): Factory
    {
        return NewsPostFactory::new();
    }
}
