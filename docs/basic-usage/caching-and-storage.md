---
title: Caching and storage
weight: 4
---

## How caching works

This package uses two layers of caching:

1. **Laravel cache**: Stores the HTML content temporarily, keyed by its md5 hash. This is needed so the controller can retrieve the HTML when the image URL is first requested.
2. **Filesystem disk**: Stores the generated image permanently. Once an image is generated, it's served directly from disk on subsequent requests.

## Content-hash URLs

Because image URLs are based on the md5 hash of the HTML content, changing the content automatically produces a new URL. Old images remain on disk until you clear them.

## Configuring the disk

By default, images are stored on the `public` disk:

```php
// config/og-image.php
'disk' => 'public',
'path' => 'og-images',
```

## Configuring the cache

You can specify which cache store to use and how long HTML should be kept:

```php
// config/og-image.php
'cache_store' => 'redis',
'cache_ttl' => 3600, // seconds, or null for forever
```

The HTML only needs to stay in cache until the image is generated. After that, the image is served directly from disk. Setting a TTL of a few hours is usually sufficient.

## Clearing generated images

To delete all generated OG images from disk:

```bash
php artisan og-image:clear
```

This removes all files from the configured disk path. The images will be regenerated on the next request (as long as the HTML is still in cache or the page is visited again).
