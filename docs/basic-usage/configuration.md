---
title: Configuration
weight: 2
---

You can configure the package using the `OgImage` facade in your `AppServiceProvider`. All methods are chainable:

```php
use Spatie\OgImage\Facades\OgImage;

public function boot(): void
{
    OgImage::useCloudflare(
            apiToken: env('CLOUDFLARE_API_TOKEN'),
            accountId: env('CLOUDFLARE_ACCOUNT_ID'),
        )
        ->size(1200, 630)
        ->format('webp')
        ->disk('s3');
}
```

You only need to call the methods for settings you want to change. The defaults work well for most applications.

### size

Set the dimensions of the generated OG images. Defaults to 1200x630, which is the recommended size across most social platforms.

```php
OgImage::size(1200, 630);
```

### format

Set the default image format. Supported formats: `jpeg`, `png`, `webp`. Defaults to `jpeg`.

```php
OgImage::format('webp');
```

### disk

Set the filesystem disk and path for storing generated images. Defaults to the `public` disk at `og-images`.

```php
OgImage::disk('s3', 'og-images');
```

### configureScreenshot

Customize the underlying screenshot builder. See [customizing screenshots](/docs/laravel-og-image/v1/advanced-usage/customizing-screenshots) for details.

```php
OgImage::configureScreenshot(function (ScreenshotBuilder $screenshot) {
    $screenshot->deviceScaleFactor(2);
});
```
