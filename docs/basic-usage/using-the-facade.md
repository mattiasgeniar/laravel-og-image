---
title: Using the facade
weight: 2
---

You can also generate OG image meta tags using the `OgImage` facade. This is useful when you want to keep your OG image templates in separate Blade files.

## Rendering a view

```php
use Spatie\OgImage\Facades\OgImage;
```

```blade
{{-- In your layout's <head> --}}
{{ OgImage::view('og.blog-post', ['title' => $post->title, 'author' => $post->author->name]) }}
```

Create the view file at `resources/views/og/blog-post.blade.php`:

```blade
<div class="w-full h-full bg-white flex flex-col justify-center p-16">
    <h1 class="text-6xl font-bold text-gray-900">{{ $title }}</h1>
    <p class="text-3xl text-gray-600 mt-4">by {{ $author }}</p>
</div>
```

The `view()` method renders the Blade view, hashes the resulting HTML, stores it in cache, and returns the meta tags as an `HtmlString`. Because it implements `Htmlable`, you can use double-brace syntax `{{ }}` in your Blade templates.

## Rendering raw HTML

If you prefer to pass HTML directly:

```blade
{{ OgImage::html('<div class="w-full h-full bg-blue-500 text-white flex items-center justify-center"><h1 class="text-6xl">Hello</h1></div>') }}
```

## Specifying the format

Both methods accept an optional format parameter:

```blade
{{ OgImage::view('og.blog-post', ['title' => $post->title], 'webp') }}
{{ OgImage::html('<div>...</div>', 'jpeg') }}
```
