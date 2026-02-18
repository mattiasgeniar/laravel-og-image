---
title: Customizing screenshots
weight: 1
---

Under the hood, this package uses [spatie/laravel-screenshot](https://github.com/spatie/laravel-screenshot) to generate images. You can pass extra options to the screenshot builder via the `screenshot` config key.

## Available options

```php
// config/og-image.php
'screenshot' => [
    'device_scale_factor' => 2, // retina-quality images
    'wait_until' => 'networkidle0', // wait for network to be idle
    'wait_for_timeout' => 1000, // wait 1 second before taking the screenshot
],
```

### device_scale_factor

Controls the device pixel ratio. Set to `2` for retina-quality images (resulting in 2400x1260 pixel images at the default 1200x630 viewport).

### wait_until

Determines when the page is considered loaded. Options:

- `load` — wait for the `load` event
- `domcontentloaded` — wait for the `DOMContentLoaded` event
- `networkidle0` — wait until there are no more than 0 network connections for at least 500ms
- `networkidle2` — wait until there are no more than 2 network connections for at least 500ms

### wait_for_timeout

Additional time to wait (in milliseconds) after the page has loaded. Useful if you need to wait for fonts or animations to complete.

## Browsershot configuration

Since laravel-screenshot uses Browsershot by default, you can configure Browsershot's settings through its own config file. See the [laravel-screenshot documentation](https://github.com/spatie/laravel-screenshot) for details.
