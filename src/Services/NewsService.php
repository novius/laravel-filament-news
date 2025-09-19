<?php

namespace Novius\LaravelFilamentNews\Services;

use Illuminate\Support\Arr;
use Illuminate\Support\Collection;
use LaravelLang\Locales\Data\LocaleData;
use LaravelLang\Locales\Facades\Locales;
use Novius\LaravelFilamentNews\Filament\Resources\Categories\CategoryResource;
use Novius\LaravelFilamentNews\Filament\Resources\Posts\PostResource;
use Novius\LaravelFilamentNews\Filament\Resources\Tags\TagResource;
use Novius\LaravelFilamentNews\Models\NewsCategory;
use Novius\LaravelFilamentNews\Models\NewsPost;
use Novius\LaravelFilamentNews\Models\NewsTag;

class NewsService
{
    public function __construct(protected array $config = []) {}

    /**
     * @return Collection<string, LocaleData>
     */
    public function locales(): Collection
    {
        $locales = Arr::get($this->config, 'locales', []);

        return Locales::installed()
            ->when(! empty($locales), fn (Collection $collection) => $collection->filter(fn (LocaleData $localeData) => in_array($localeData->code, $locales, true)));
    }

    /** @return class-string<NewsPost> */
    public function getPostModel(): string
    {
        return Arr::get($this->config, 'models.post', NewsPost::class);
    }

    /** @return class-string<PostResource> */
    public function getPostResource(): string
    {
        return Arr::get($this->config, 'resources.post', PostResource::class);
    }

    /** @return class-string<NewsCategory> */
    public function getCategoryModel(): string
    {
        return Arr::get($this->config, 'models.category', NewsCategory::class);
    }

    /** @return class-string<CategoryResource> */
    public function getCategoryResource(): string
    {
        return Arr::get($this->config, 'resources.category', CategoryResource::class);
    }

    /** @return class-string<NewsTag> */
    public function getTagModel(): string
    {
        return Arr::get($this->config, 'models.tag', NewsTag::class);
    }

    /** @return class-string<TagResource> */
    public function getTagResource(): string
    {
        return Arr::get($this->config, 'resources.tag', TagResource::class);
    }
}
