---
title: Programmatic usage
weight: 3
---

The `OgImage` class provides several utility methods you can use directly.

## Getting the image URL

```php
use Spatie\OgImage\Facades\OgImage;

$hash = OgImage::hash('<div>My content</div>');
$url = OgImage::url($hash, 'png');
// https://yourapp.com/og-image/a1b2c3d4e5f6.png
```

## Checking if an image exists

```php
$exists = OgImage::exists($hash, 'png');
```

This checks whether the image file already exists on the configured disk.

## Computing the hash

```php
$hash = OgImage::hash('<div>My content</div>');
```

Returns the md5 hash of the given HTML string. This is the same hash used in the image URL.

## Storing and retrieving HTML from cache

```php
OgImage::storeHtmlInCache($hash, '<div>My content</div>');

$html = OgImage::getHtmlFromCache($hash);
```

These methods interact with the configured cache store directly. You generally don't need to call them yourself — the `html()` and `view()` methods handle this automatically.

## Custom base URL

If your app is behind a CDN or you want images served from a different domain:

```php
// config/og-image.php
'base_url' => 'https://cdn.example.com',
```

This affects all generated meta tag URLs.
