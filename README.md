# Generate OG images for your Laravel app

[![Latest Version on Packagist](https://img.shields.io/packagist/v/spatie/laravel-og-image.svg?style=flat-square)](https://packagist.org/packages/spatie/laravel-og-image)
[![GitHub Tests Action Status](https://img.shields.io/github/actions/workflow/status/spatie/laravel-og-image/run-tests.yml?branch=main&label=tests&style=flat-square)](https://github.com/spatie/laravel-og-image/actions?query=workflow%3Arun-tests+branch%3Amain)
[![GitHub Code Style Action Status](https://img.shields.io/github/actions/workflow/status/spatie/laravel-og-image/fix-php-code-style-issues.yml?branch=main&label=code%20style&style=flat-square)](https://github.com/spatie/laravel-og-image/actions?query=workflow%3A"Fix+PHP+code+style+issues"+branch%3Amain)
[![Total Downloads](https://img.shields.io/packagist/dt/spatie/laravel-og-image.svg?style=flat-square)](https://packagist.org/packages/spatie/laravel-og-image)

This package makes it easy to generate Open Graph images for your Laravel application. Define your OG image HTML inline in your Blade views or via separate view files, and the package automatically generates screenshot images using [spatie/laravel-screenshot](https://github.com/spatie/laravel-screenshot), serves them via a dedicated route, and caches them on disk.

No external API needed — everything runs on your own server.

## Support us

[<img src="https://github-ads.s3.eu-central-1.amazonaws.com/laravel-og-image.jpg?t=1" width="419px" />](https://spatie.be/github-ad-click/laravel-og-image)

We invest a lot of resources into creating [best in class open source packages](https://spatie.be/open-source). You can support us by [buying one of our paid products](https://spatie.be/open-source/support-us).

We highly appreciate you sending us a postcard from your hometown, mentioning which of our package(s) you are using. You'll find our address on [our contact page](https://spatie.be/about-us). We publish all received postcards on [our virtual postcard wall](https://spatie.be/open-source/postcards).

## Documentation

You'll find full documentation on [our documentation site](https://spatie.be/docs/laravel-og-image).

## Basic usage

Use the Blade component to define your OG image inline:

```blade
<x-og-image>
    <div class="w-full h-full bg-blue-900 text-white flex items-center justify-center">
        <h1 class="text-6xl font-bold">{{ $post->title }}</h1>
    </div>
</x-og-image>
```

Or use the facade with a dedicated view file:

```blade
{{ OgImage::view('og.blog-post', ['title' => $post->title]) }}
```

Both output the appropriate `<meta>` tags pointing to a generated screenshot of your HTML at 1200x630 pixels.

## How it works

1. Your HTML is hashed (md5) and stored in Laravel's cache
2. Meta tags point to `/og-image/{hash}.png`
3. When that URL is first requested, the HTML is screenshotted at 1200x630
4. The generated image is saved to your public disk
5. Subsequent requests serve the image directly from disk

Tailwind CSS is included by default, so you can use utility classes out of the box.

## Installation

You can install the package via composer:

```bash
composer require spatie/laravel-og-image
```

This package requires [spatie/laravel-screenshot](https://github.com/spatie/laravel-screenshot), which uses Browsershot under the hood. Make sure you have Node.js and a Chrome/Chromium binary installed.

You can optionally publish the config file:

```bash
php artisan vendor:publish --tag="og-image-config"
```

## Testing

The package provides a fake for testing:

```php
use Spatie\OgImage\Facades\OgImage;

OgImage::fake();

// ... your code ...

OgImage::assertViewRendered('og.blog-post');
```

```bash
composer test
```

## Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information on what has changed recently.

## Contributing

Please see [CONTRIBUTING](CONTRIBUTING.md) for details.

## Security Vulnerabilities

Please review [our security policy](../../security/policy) on how to report security vulnerabilities.

## Credits

- [Freek Van der Herten](https://github.com/freekmurze)
- [All Contributors](../../contributors)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
