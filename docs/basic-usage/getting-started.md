---
title: Getting started
weight: 1
---

When you share a link on social media, platforms like Twitter, Facebook, and LinkedIn display a preview image. These are called Open Graph (OG) images. This package lets you define that image as HTML in your Blade views, and automatically screenshots it to generate the actual image file.

## How it works

1. You wrap your OG image HTML in the `<x-og-image>` Blade component
2. The package renders it inside a hidden `<template>` tag on your page and outputs `og:image` meta tags pointing to `/og-image/{hash}.jpeg`
3. When a social platform (or any crawler) requests that image URL, the package looks up which page the image belongs to
4. It visits that page with `?ogimage` appended, which triggers a middleware that strips everything except the `<head>` and your template content, displayed at 1200×630 pixels
5. A screenshot is taken and saved to disk
6. Future requests for the same image are served directly from disk

Because the screenshot uses your actual page's `<head>`, your OG image inherits all of your CSS, fonts, and Vite assets. No separate stylesheet configuration needed.

## The Blade component

Place the `<x-og-image>` component anywhere in your Blade view:

```blade
<x-og-image>
    <div class="w-full h-full bg-blue-900 text-white flex items-center justify-center">
        <h1 class="text-6xl font-bold">{{ $post->title }}</h1>
    </div>
</x-og-image>
```

This outputs a hidden `<template>` tag (natively invisible in browsers) and the meta tags:

```html
<template data-og-image>
    <div class="w-full h-full bg-blue-900 text-white flex items-center justify-center">
        <h1 class="text-6xl font-bold">My Post Title</h1>
    </div>
</template>
<meta property="og:image" content="https://yourapp.com/og-image/a1b2c3d4e5f6.jpeg">
<meta name="twitter:image" content="https://yourapp.com/og-image/a1b2c3d4e5f6.jpeg">
<meta name="twitter:card" content="summary_large_image">
```

The image URL contains a hash of your HTML content. When the content changes, the hash changes, so crawlers pick up the new image automatically.

## Specifying the image format

By default, images are generated as JPEG. You can specify a different format:

```blade
<x-og-image format="webp">
    <div class="w-full h-full bg-gradient-to-r from-purple-500 to-pink-500 text-white flex items-center justify-center">
        <h1 class="text-6xl font-bold">{{ $title }}</h1>
    </div>
</x-og-image>
```

## Previewing your OG image

Append `?ogimage` to any page URL to see exactly what will be screenshotted. This renders just the template content at the configured dimensions (1200×630 by default), using the page's full `<head>` with all CSS and fonts.

## Design tips

- Design for 1200×630 pixels (the default size)
- Use `w-full h-full` on your root element to fill the entire viewport
- Use `flex` or `grid` for layout
- Keep text large, since it will be viewed as a thumbnail
- Preview your designs by visiting the page URL with `?ogimage` appended
