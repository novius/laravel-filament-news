<?php

use Novius\LaravelFilamentNews\Filament\Resources\Categories\CategoryResource;
use Novius\LaravelFilamentNews\Filament\Resources\Posts\PostResource;
use Novius\LaravelFilamentNews\Filament\Resources\Tags\TagResource;
use Novius\LaravelFilamentNews\Models\NewsCategory;
use Novius\LaravelFilamentNews\Models\NewsPost;
use Novius\LaravelFilamentNews\Models\NewsTag;

return [
    /*
     * Resources used to manage your posts.
     */
    'resources' => [
        'post' => PostResource::class,
        'category' => CategoryResource::class,
        'tag' => TagResource::class,
    ],

    /*
     * Models used to manage your posts.
     */
    'models' => [
        'post' => NewsPost::class,
        'category' => NewsCategory::class,
        'tag' => NewsTag::class,
    ],

    // If you want to restrict the list of possible locals. By default, uses all the locals installed
    'locales' => [
        // 'en',
    ],

    /*
     * The route name used to display news posts and categories.
     */
    'front_routes_name' => [
        'posts' => null,
        'post' => null,
        'categories' => null,
        'category' => null,
        'tag' => null,
    ],

    /*
     * The route name used to display news posts and categories.
     */
    'front_routes_parameters' => [
        'post' => null,
        'category' => null,
        'tag' => null,
    ],
];
