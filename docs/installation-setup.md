---
title: Installation & setup
weight: 3
---

You can install the package via composer:

```bash
composer require spatie/laravel-og-image
```

Make sure you also have the requirements for [spatie/laravel-screenshot](https://github.com/spatie/laravel-screenshot) installed (Node.js and Chrome/Chromium by default).

## Publishing the config file

Optionally, you can publish the config file with:

```bash
php artisan vendor:publish --tag=og-image-config
```

This is the content of the published config file:

```php
return [
    /*
     * The filesystem disk used to store generated OG images.
     */
    'disk' => 'public',

    /*
     * The path within the disk where OG images will be stored.
     */
    'path' => 'og-images',

    /*
     * The dimensions of the generated OG images in pixels.
     */
    'width' => 1200,
    'height' => 630,

    /*
     * The default image format. Supported: "png", "jpeg", "webp".
     */
    'format' => 'png',

    /*
     * HTML tags injected into the <head> of the screenshot document.
     */
    'head' => [
        '<script src="https://cdn.tailwindcss.com"></script>',
    ],

    /*
     * The cache store used to temporarily store OG image HTML.
     */
    'cache_store' => null,

    /*
     * How long to keep the HTML in cache.
     */
    'cache_ttl' => null,

    /*
     * The base URL used to generate OG image URLs.
     */
    'base_url' => null,

    /*
     * The route prefix for the OG image serving endpoint.
     */
    'route_prefix' => 'og-image',

    /*
     * Middleware applied to the OG image serving route.
     */
    'route_middleware' => ['web'],

    /*
     * Extra configuration passed to spatie/laravel-screenshot.
     */
    'screenshot' => [],
];
```

## Publishing the view

You can publish the document template used to wrap your OG image HTML:

```bash
php artisan vendor:publish --tag=og-image-views
```

This lets you customize the HTML document that wraps your OG image content before it's screenshotted.
