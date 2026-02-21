---
title: How it works
weight: 1
---

When you share a link on social media, platforms like Twitter, Facebook, and LinkedIn display a preview image. These are called Open Graph (OG) images. This package lets you define that image as HTML in your Blade views, and automatically screenshots it to generate the actual image file.

## The big picture

There are three stages to understand:

1. Your page renders: the Blade component outputs meta tags and a hidden HTML template
2. A crawler fetches the image: the package generates a screenshot on the fly
3. Next time your page renders: the meta tags now point straight to the generated image

Let's walk through each stage.

## Stage 1: Your page renders for the first time

When a visitor (or a crawler) loads your page, the `<x-og-image>` Blade component does three things:

1. It hashes the template HTML to produce a unique key (e.g. `a1b2c3d4e5f6`)
2. It stores the current page URL in the cache, keyed by that hash
3. It outputs a hidden `<template>` tag and `og:image` meta tags

The HTML in the response looks like this:

```html
<template data-og-image>
    <div class="...">My Post Title</div>
</template>
<meta property="og:image" content="https://yourapp.com/og-image/a1b2c3d4e5f6.jpeg">
<meta name="twitter:image" content="https://yourapp.com/og-image/a1b2c3d4e5f6.jpeg">
<meta name="twitter:card" content="summary_large_image">
```

The `<template>` tag is natively invisible in browsers, so visitors don't see it. The meta tags point to a route in your app (`/og-image/a1b2c3d4e5f6.jpeg`). The image doesn't exist yet, but that's fine. Crawlers will request it next.

## Stage 2: A crawler requests the image

When Twitter, Facebook, or LinkedIn sees the `og:image` meta tag, it makes a request to `https://yourapp.com/og-image/a1b2c3d4e5f6.jpeg`. Here's what happens:

1. The request hits `OgImageController`, which looks up the hash in the cache
2. The controller finds the original page URL and visits it with `?ogimage` appended
3. `RenderOgImageMiddleware` detects the `?ogimage` parameter and replaces the response with a minimal HTML page: just your page's `<head>` (preserving all CSS, fonts, and Vite assets) and the template content, displayed at 1200x630 pixels
4. A screenshot is taken and saved to the configured disk (default: `public`)
5. The controller redirects the crawler to the static file (e.g. `/storage/og-images/a1b2c3d4e5f6.jpeg`)

Because the screenshot uses your actual page's `<head>`, your OG image inherits all of your CSS, fonts, and Vite assets. No separate stylesheet configuration needed.

## Stage 3: Subsequent page renders

Now that the image exists, the next time a visitor loads your page, the `<x-og-image>` component detects that the image has already been generated. Instead of pointing to the `/og-image/` route, the meta tags now point directly to the storage URL:

```html
<meta property="og:image" content="https://yourapp.com/storage/og-images/a1b2c3d4e5f6.jpeg">
<meta name="twitter:image" content="https://yourapp.com/storage/og-images/a1b2c3d4e5f6.jpeg">
```

This means crawlers fetch the image directly from disk (or S3/CDN) without hitting Laravel at all.

## Performance

After the image has been generated, there are two optimizations that keep things fast:

- Subsequent page renders point crawlers straight to the static file via direct URLs in the meta tags, bypassing Laravel entirely
- The `/og-image/` route runs without any middleware (no sessions, CSRF, or cookies), so if a crawler does hit it before a second page render has occurred, it's just a cache lookup and a redirect
