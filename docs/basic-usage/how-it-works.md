---
title: How it works
weight: 1
---

When you share a link on social media, platforms like Twitter, Facebook, and LinkedIn display a preview image. These are called Open Graph (OG) images. This package lets you define that image as HTML in your Blade views, and automatically screenshots it to generate the actual image file.

There are two phases: rendering the page, and generating the image.

## When a visitor loads your page

1. The `<x-og-image>` Blade component hashes the template HTML to produce a unique key (e.g. `a1b2c3d4e5f6`)
2. It stores the current page URL in the cache, keyed by that hash
3. It outputs a hidden `<template data-og-image>` tag containing your HTML, along with `og:image` meta tags. If the image has already been generated, the meta tags point directly to the storage URL (e.g. `https://yourapp.com/storage/og-images/a1b2c3d4e5f6.jpeg`), so crawlers bypass Laravel entirely. On first render, the meta tags point to `/og-image/a1b2c3d4e5f6.jpeg`, which triggers generation

At this point, the visitor sees nothing — the `<template>` tag is natively invisible in browsers.

## When a crawler requests the image URL

4. The request to `/og-image/a1b2c3d4e5f6.jpeg` hits `OgImageController`, which checks if the image has already been generated. If it has, it redirects to the static file on disk (e.g. `/storage/og-images/a1b2c3d4e5f6.jpeg`) and the process stops here.
5. If the image doesn't exist yet, the controller looks up the page URL from cache using the hash, and visits that page with `?ogimage` appended.
6. `RenderOgImageMiddleware` detects the `?ogimage` parameter and replaces the response with a minimal HTML page: just the page's `<head>` (preserving all CSS, fonts, and Vite assets) and your template content, displayed at 1200×630 pixels.
7. A screenshot is taken of that page and saved to the configured disk (default: `public`).
8. The controller redirects to the static file on disk.

On subsequent requests for the same image, only step 4 runs — a fast cache lookup followed by a redirect.

Because the screenshot uses your actual page's `<head>`, your OG image inherits all of your CSS, fonts, and Vite assets. No separate stylesheet configuration needed.

## Performance

The og-image route runs without any middleware (no sessions, CSRF, cookies), keeping the redirect as fast as possible.

After the first page render, subsequent renders output the direct storage URL in the meta tags. This means crawlers go straight to the static file (or S3/CDN) without hitting Laravel at all. Only the very first render uses the `/og-image/{hash}.jpeg` route URL, which triggers generation.
