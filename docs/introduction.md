---
title: Introduction
weight: 1
---

This package makes it easy to generate Open Graph images for your Laravel application. Define your OG image HTML inline in your Blade views or via separate view files, and the package automatically generates screenshot images, serves them via a dedicated route, and caches them on disk.

No external API needed — everything runs on your own server using [spatie/laravel-screenshot](https://github.com/spatie/laravel-screenshot).

Here's a quick example using the Blade component:

```blade
<x-og-image>
    <div class="w-full h-full bg-blue-900 text-white flex items-center justify-center">
        <h1 class="text-6xl font-bold">{{ $post->title }}</h1>
    </div>
</x-og-image>
```

This will render the appropriate `<meta>` tags pointing to a generated screenshot of your HTML. The screenshot is taken at 1200x630 pixels — the standard OG image size.

You can also use the facade:

```php
use Spatie\OgImage\Facades\OgImage;

// In your Blade view
{{ OgImage::view('og.blog-post', ['title' => $post->title]) }}
```

Both approaches output `<meta property="og:image">`, `<meta name="twitter:image">`, and `<meta name="twitter:card">` tags.

## How it works

1. Your HTML is hashed (md5) and stored in Laravel's cache
2. Meta tags point to `/og-image/{hash}.png`
3. When that URL is first requested, the HTML is retrieved from cache, wrapped in a full HTML document (with Tailwind CSS by default), and screenshotted
4. The generated image is saved to your public disk
5. Subsequent requests serve the image directly from disk with immutable cache headers

## We got badges

[![Latest Version on Packagist](https://img.shields.io/packagist/v/spatie/laravel-og-image.svg?style=flat-square)](https://packagist.org/packages/spatie/laravel-og-image)
[![GitHub Tests Action Status](https://img.shields.io/github/actions/workflow/status/spatie/laravel-og-image/run-tests.yml?branch=main&label=tests&style=flat-square)](https://github.com/spatie/laravel-og-image/actions?query=workflow%3Arun-tests+branch%3Amain)
[![Total Downloads](https://img.shields.io/packagist/dt/spatie/laravel-og-image.svg?style=flat-square)](https://packagist.org/packages/spatie/laravel-og-image)
