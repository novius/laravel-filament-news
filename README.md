<div align="center">

# Laravel Filament News

[![Novius CI](https://github.com/novius/laravel-filament-news/actions/workflows/main.yml/badge.svg?branch=main)](https://github.com/novius/laravel-filament-news/actions/workflows/main.yml)
[![Packagist Release](https://img.shields.io/packagist/v/novius/laravel-filament-news.svg?maxAge=1800&style=flat-square)](https://packagist.org/packages/novius/laravel-filament-news)
[![License: AGPL v3](https://img.shields.io/badge/License-AGPL%20v3-blue.svg)](http://www.gnu.org/licenses/agpl-3.0)

</div>

## Introduction 

This [Laravel Filament](https://filamentphp.com/) package allows you to manage Post news in your Laravel Filament admin panel.  
You will be able to create posts, categories and tags.  
You can attach multiple categories and tags to a post. Categories can be viewed as a listing page.

## Requirements

* PHP >= 8.2
* Laravel Filament >= 4
* Laravel >= 11.0

## Installation

You can install the package via composer:

```bash
composer require novius/laravel-filament-news
```

Run migrations with:

```bash
php artisan migrate
```

In your `AdminFilamentPanelProvider` add the `PageManagerPlugin` :

```php
use Novius\LaravelFilamentNews\Filament\NewsPlugin;

class AdminFilamentPanelProvider extends PanelProvider
{
    public function panel(Panel $panel): Panel
    {
        return $panel
            // ...
            ->plugins([
                NewsPlugin::make(),
            ])
            // ...
            ;
    }
}
```

## Configuration

You can optionally publish the config file with:

```bash
php artisan vendor:publish --provider="Novius\LaravelFilamentNews\LaravelFilamentNewsServiceProvider" --tag="config"
```

This will allow you to:  
* define the name of the routes and their parameter
* override resource or model classes
* define locales used

```php
// config/laravel-filament-news.php

return [
    /*
     * Resources used to manage your posts. 
     */
    'resources' => [
        'post' => \Novius\LaravelFilamentNews\Filament\Resources\PostResource::class,
        'category' => \Novius\LaravelFilamentNews\Filament\Resources\CategoryResource::class,
        'tag' => \Novius\LaravelFilamentNews\Filament\Resources\TagResource::class,
    ],

    /*
     * Models used to manage your posts.
     */
    'models' => [
        'post' => \Novius\LaravelFilamentNews\Models\NewsPost::class,
        'category' => \Novius\LaravelFilamentNews\Models\NewsCategory::class,
        'tag' => \Novius\LaravelFilamentNews\Models\NewsTag::class,
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
```

## Front Stuff

If you want a pre-generated front controller and routes, you can run following command :

```shell
php artisan news-manager:publish-front {--without-categories} {--without-tags} 
``` 

This command appends routes to `routes/web.php` and creates a new `App\Http\Controllers\NewsController`.

You can then customize your routes and your controller.

In views called by the controller use the documentation of [laravel-meta](https://github.com/novius/laravel-meta?tab=readme-ov-file#front) to implement meta tags

## Assets

Next we need to publish the Laravel Nova Translatable package's assets. We do this by running the following command:

```sh
php artisan vendor:publish --provider="Novius\LaravelNovaTranslatable\LaravelNovaTranslatableServiceProvider" --tag="public"
```

## Migrations and lang files

If you want to customize the migrations or lang files, you can publish them with:

```bash
php artisan vendor:publish --provider="Novius\LaravelFilamentNews\LaravelFilamentNewsServiceProvider" --tag="migrations"
```

```bash
php artisan vendor:publish --provider="Novius\LaravelFilamentNews\LaravelFilamentNewsServiceProvider" --tag="lang"
```

## Testing

Run the tests with:

```bash
composer test
```

## Lint

Lint your code with Laravel Pint using:

```bash
composer lint
```

## Licence

This package is under [GNU Affero General Public License v3](http://www.gnu.org/licenses/agpl-3.0.html) or (at your option) any later version.
