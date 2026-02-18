---
title: Image formats
weight: 2
---

The package supports three image formats: PNG, JPEG, and WebP.

## Setting the default format

```php
// config/og-image.php
'format' => 'png', // or 'jpeg', 'webp'
```

## Per-image format

You can override the format on a per-image basis:

```blade
<x-og-image format="webp">
    <div>...</div>
</x-og-image>
```

Or with the facade:

```blade
{{ OgImage::view('og.post', $data, 'jpeg') }}
{{ OgImage::html('<div>...</div>', 'webp') }}
```

## Format comparison

| Format | File size | Quality | Transparency |
|--------|-----------|---------|--------------|
| PNG    | Largest   | Lossless | Yes         |
| JPEG   | Smallest  | Lossy   | No           |
| WebP   | Small     | Both    | Yes          |

For most OG images, PNG is the safest choice as it's universally supported. WebP offers smaller file sizes with good quality but has slightly less universal support among social platforms.

## Same HTML, multiple formats

The same HTML can produce different format images since the format is part of the URL. For example, `/og-image/abc123.png` and `/og-image/abc123.webp` are different URLs serving different formats of the same content.
