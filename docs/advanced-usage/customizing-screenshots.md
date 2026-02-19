---
title: Customizing screenshots
weight: 1
---

You can customize the screenshot builder using `OgImage::configureScreenshot()` in your `AppServiceProvider`:

```php
use Spatie\OgImage\Enums\WaitUntil;
use Spatie\OgImage\Facades\OgImage;
use Spatie\LaravelScreenshot\ScreenshotBuilder;

public function boot(): void
{
    OgImage::configureScreenshot(function (ScreenshotBuilder $screenshot) {
        $screenshot
            ->deviceScaleFactor(2)
            ->waitUntil(WaitUntil::NetworkIdle0);
    });
}
```

The closure receives the `ScreenshotBuilder` instance after the URL, size, and disk have been set. You can call any method on the builder.

This can be chained with other configuration methods:

```php
OgImage::format('webp')
    ->size(1200, 630)
    ->configureScreenshot(function (ScreenshotBuilder $screenshot) {
        $screenshot->deviceScaleFactor(2);
    });
```

For choosing between Browsershot and Cloudflare, see [screenshot driver](/docs/laravel-og-image/v1/basic-usage/screenshot-driver).

## Common options

### deviceScaleFactor

Controls the device pixel ratio. Set to `2` for retina-quality images (resulting in 2400x1260 pixel images at the default 1200x630 viewport).

### waitUntil

Determines when the page is considered loaded. Use the `WaitUntil` enum:

- `WaitUntil::Load`: wait for the `load` event
- `WaitUntil::DomContentLoaded`: wait for the `DOMContentLoaded` event
- `WaitUntil::NetworkIdle0`: wait until there are no more than 0 network connections for at least 500ms
- `WaitUntil::NetworkIdle2`: wait until there are no more than 2 network connections for at least 500ms

### waitForTimeout

Additional time to wait (in milliseconds) after the page has loaded. Useful if you need to wait for fonts or animations to complete.
