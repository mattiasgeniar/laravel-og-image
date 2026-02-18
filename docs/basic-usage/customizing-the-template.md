---
title: Customizing the template
weight: 3
---

## Head tags

By default, the screenshot document includes the Tailwind CSS CDN. You can customize what's injected into the `<head>` via the `head` config option:

```php
// config/og-image.php
'head' => [
    '<script src="https://cdn.tailwindcss.com"></script>',
    '<link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;700&display=swap" rel="stylesheet">',
    '<style>body { font-family: "Inter", sans-serif; }</style>',
],
```

You can also use your own CSS file instead of Tailwind:

```php
'head' => [
    '<link rel="stylesheet" href="https://yourapp.com/css/og-image.css">',
],
```

## Customizing the document template

The HTML you provide is wrapped in a full HTML document before being screenshotted. You can publish and customize this template:

```bash
php artisan vendor:publish --tag=og-image-views
```

This publishes the `document.blade.php` file to `resources/views/vendor/og-image/document.blade.php`. The template receives these variables:

- `$html` — your OG image HTML content
- `$headTags` — array of head tag strings from config
- `$width` — the configured width in pixels
- `$height` — the configured height in pixels

## Changing the image dimensions

```php
// config/og-image.php
'width' => 1200,
'height' => 630,
```

> Note: 1200x630 is the recommended size for OG images across most social platforms.
