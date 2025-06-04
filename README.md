<div align="center">

# Laravel Filament News

[![Novius CI](https://github.com/novius/laravel-filament-news/actions/workflows/main.yml/badge.svg?branch=main)](https://github.com/novius/laravel-filament-news/actions/workflows/main.yml)

</div>

## Introduction 

This [Laravel Filament](https://filamentphp.com/) package allows you to manage Post news in your Laravel Filament admin panel.  
You will be able to create posts, categories and tags.  
You can attach multiple categories and tags to a post. Categories can be viewed as a listing page.

## Requirements

* PHP >= 8.2
* Laravel Filament >= 3.3
* Laravel >= 11.0

## Installation

You can install the package via composer:

```bash
composer require novius/laravel-filament-news
```

Register the tool in the `tools` method of the `NovaServiceProvider`:

```php
// app/Providers/NovaServiceProvider.php

public function tools()
{
    return [
        // ...
        new \Novius\LaravelFilamentNews\LaravelFilamentNews(),
    ];
}
```

Run migrations with:

```bash
php artisan migrate
```

## Configuration

You can optionally publish the config file with:

```bash
php artisan vendor:publish --provider="Novius\LaravelFilamentNews\LaravelFilamentNewsServiceProvider" --tag="config"
```

This will allow you defined routes names and  

This will allow you to:  
* define the name of the routes and their parameter
* override resource or model class
* define locales used

```php
// config/laravel-filament-news.php

return [
    // ...
    'resources' => [
        'post' => \App\Nova\Post::class,
    ],
];
```

```php
// app/Nova/Post.php

namespace App\Nova;

use Laravel\Nova\Fields\Text;

class Post extends \Novius\LaravelFilamentNews\Nova\NewsPost
{
    public function mainFields(): array
    {
        return [
            ...parent::mainFields(),

            Text::make('Subtitle'),
        ];
    }
}
```

## Front Stuff

If you want a pre-generated front controller and routes, you can run following command :

```shell
php artisan news-manager:publish-front {--without-categories} {--without-tags} 
``` 

This command appends routes to `routes/web.php` and creates a new `App\Http\Controllers\FrontNewsController`.

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
