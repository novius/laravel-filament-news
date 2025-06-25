<?php

namespace Novius\LaravelFilamentNews\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Carbon;
use Novius\LaravelFilamentNews\Database\Factories\NewsTagFactory;
use Novius\LaravelFilamentNews\Facades\News;
use Novius\LaravelLinkable\Configs\LinkableConfig;
use Novius\LaravelLinkable\Traits\Linkable;
use Novius\LaravelTranslatable\Support\TranslatableModelConfig;
use Novius\LaravelTranslatable\Traits\Translatable;
use Spatie\Sluggable\HasSlug;
use Spatie\Sluggable\SlugOptions;

/**
 * @property int $id
 * @property string $name
 * @property string $slug
 * @property string $locale
 * @property int $locale_parent_id
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property Carbon|null $deleted_at
 * @property-read Collection<int, NewsPost> $posts
 * @property-read Collection<int, NewsTag> $translations
 * @property-read Collection<int, NewsTag> $translationsWithDeleted
 *
 * @method static NewsTagFactory factory($count = null, $state = [])
 * @method static Builder<static>|NewsTag newModelQuery()
 * @method static Builder<static>|NewsTag newQuery()
 * @method static Builder<static>|NewsTag onlyTrashed()
 * @method static Builder<static>|NewsTag query()
 * @method static Builder<static>|NewsTag withLocale(?string $locale)
 * @method static Builder<static>|NewsTag withTrashed()
 * @method static Builder<static>|NewsTag withoutTrashed()
 *
 * @mixin Model
 */
class NewsTag extends Model
{
    use HasFactory;
    use HasSlug;
    use Linkable;
    use SoftDeletes;
    use Translatable;

    protected $table = 'news_tags';

    protected $guarded = ['id'];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    protected static function booted(): void
    {
        static::saving(static function ($tag) {
            $locales = config('laravel-filament-news.locales', []);

            if (empty($tag->locale) && count($locales) === 1) {
                $tag->locale = array_key_first($locales);
            }
        });
    }

    public function getRouteKeyName(): string
    {
        return 'slug';
    }

    protected ?LinkableConfig $_linkableConfig;

    public function linkableConfig(): ?LinkableConfig
    {
        $route = config('laravel-filament-news.front_routes_name.tag');
        $routeParameterName = config('laravel-filament-news.front_routes_parameters.tag');
        if (empty($routeParameterName) && empty($route)) {
            return null;
        }

        if (! isset($this->_linkableConfig)) {
            $this->_linkableConfig = new LinkableConfig(
                routeName: $route,
                routeParameterName: $routeParameterName,
                optionLabel: 'name',
                optionGroup: trans('laravel-filament-news::crud-tag.resource_label'),
                resolveQuery: function (Builder|NewsCategory $query) {
                    $query->where('locale', app()->currentLocale());
                },
            );
        }

        return $this->_linkableConfig;
    }

    public function translatableConfig(): TranslatableModelConfig
    {
        return new TranslatableModelConfig(config('laravel-filament-news.locales'));
    }

    public function getSlugOptions(): SlugOptions
    {
        return SlugOptions::create()
            ->extraScope(fn (Builder|NewsTag $query) => $query->where('locale', $this->locale))
            ->generateSlugsFrom('name')
            ->saveSlugsTo('slug')
            ->doNotGenerateSlugsOnUpdate();
    }

    public function posts(): BelongsToMany
    {
        return $this->belongsToMany(News::getPostModel(), 'news_post_tag', 'news_tag_id', 'news_post_id');
    }

    /**
     * {@inheritdoc}
     */
    protected static function newFactory(): Factory
    {
        return NewsTagFactory::new();
    }
}
