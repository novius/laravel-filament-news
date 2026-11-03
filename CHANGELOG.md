# Changelog

All notable changes to `novius/laravel-filament-news` will be documented in this file.

The format is based on [Keep a Changelog](https://keepachangelog.com/en/1.1.0/),
and this project adheres to [Semantic Versioning](https://semver.org/spec/v2.0.0.html).

## [Unreleased](https://github.com/novius/laravel-filament-news/compare/1.1.0...HEAD)

## [1.1.0](https://github.com/novius/laravel-filament-news/compare/1.0.2...1.1.0) - 2026-11-03

### Added

- `navigation_group` config option to display the news resources in a Filament navigation group. It accepts a
  string (a translation key or a label) or an enum.

### Changed

- The news resources are no longer displayed in a navigation group by default. The posts resource is now the
  "News" navigation item, and the categories and tags resources are its child items.
- The categories and tags resources follow the navigation group and label of the configured posts resource.

### Upgrade

- The translation key `laravel-filament-news::news.navigation_group` is renamed to
  `laravel-filament-news::news.navigation_label`. Update it if you override the package translations.
- To keep a navigation group, set the `navigation_group` option in `config/laravel-filament-news.php`.

### Maintenance

- Add Dependabot, release notes categories, changelog update on release, issue template, security policy and
  contribution guide.
- Update the CI workflow (pinned actions) and rename the Composer scripts: `lint`, `lint:check` and `analyse`.

## [1.0.2](https://github.com/novius/laravel-filament-news/compare/1.0.1...1.0.2) - 2026-06-26

### Fixed

- The posts factory uses the post model defined in the config.

## [1.0.1](https://github.com/novius/laravel-filament-news/compare/1.0.0...1.0.1) - 2026-05-12

### Added

- Laravel 13 compatibility (`spatie/laravel-sluggable` 4 and `orchestra/testbench` 11 allowed).

### Fixed

- Route model binding of posts, categories and tags uses `Linkable` instead of `HasSlug`.

## [1.0.0](https://github.com/novius/laravel-filament-news/releases/tag/1.0.0) - 2026-02-18

First release of the Filament package. Requires PHP 8.2+, Laravel 11 or 12 and Filament 4 or 5.

### Added

- Filament resources to manage posts, categories and tags, registered with the `NewsPlugin`.
- Publication workflow, slugs, translations, SEO fields, preview action and internal links, using the Novius
  `laravel-filament-publishable`, `laravel-filament-slug`, `laravel-filament-translatable`, `laravel-meta`,
  `laravel-filament-action-preview` and `laravel-linkable` packages.
- Configurable resource and model classes, locales and front routes.
