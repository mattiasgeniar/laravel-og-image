---
title: Using the Blade component
weight: 1
---

The easiest way to add OG images is with the `<x-og-image>` Blade component. Place it in the `<head>` of your layout:

```blade
<head>
    <x-og-image>
        <div class="w-full h-full bg-blue-900 text-white flex items-center justify-center">
            <h1 class="text-6xl font-bold">{{ $post->title }}</h1>
        </div>
    </x-og-image>
</head>
```

The component captures your HTML slot, generates a content hash, stores the HTML in cache, and outputs the meta tags. The slot content itself is **not** rendered visually — only the meta tags are output.

The rendered output looks like:

```html
<meta property="og:image" content="https://yourapp.com/og-image/a1b2c3d4e5f6.png">
<meta name="twitter:image" content="https://yourapp.com/og-image/a1b2c3d4e5f6.png">
<meta name="twitter:card" content="summary_large_image">
```

## Specifying the image format

By default, images are generated as PNG. You can specify a different format:

```blade
<x-og-image format="webp">
    <div class="w-full h-full bg-gradient-to-r from-purple-500 to-pink-500 text-white flex items-center justify-center">
        <h1 class="text-6xl font-bold">{{ $title }}</h1>
    </div>
</x-og-image>
```

## Using Tailwind CSS

By default, the [Tailwind CSS CDN](https://cdn.tailwindcss.com) is included in the screenshot document. This means you can use any Tailwind utility class in your OG image HTML without any extra setup.

## Tips for designing OG images

- Design for **1200x630 pixels** (the default size)
- Use `w-full h-full` on your root element to fill the entire viewport
- Use `flex` or `grid` for layout
- Keep text large — it will be viewed as a thumbnail
- Test your designs by visiting the OG image URL directly in your browser
