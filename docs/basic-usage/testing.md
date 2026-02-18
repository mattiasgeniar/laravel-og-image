---
title: Testing
weight: 5
---

The package provides a fake implementation for testing, so you don't need Chrome or Node.js in your test environment.

## Setting up the fake

```php
use Spatie\OgImage\Facades\OgImage;

it('generates an og image for a blog post', function () {
    $fake = OgImage::fake();

    $post = Post::factory()->create(['title' => 'My Post']);

    $this->get(route('blog.show', $post))
        ->assertOk();

    $fake->assertViewRendered('og.blog-post');
});
```

## Available assertions

### assertViewRendered

Assert that a view was rendered as an OG image:

```php
// Assert any view was rendered
$fake->assertViewRendered();

// Assert a specific view was rendered
$fake->assertViewRendered('og.blog-post');

// Assert with a callback
$fake->assertViewRendered(function (string $view, array $data) {
    return $view === 'og.blog-post' && $data['title'] === 'My Post';
});
```

### assertHtmlRendered

Assert that raw HTML was rendered as an OG image:

```php
// Assert any HTML was rendered
$fake->assertHtmlRendered();

// Assert specific HTML was rendered
$fake->assertHtmlRendered('<div>Hello</div>');

// Assert with a callback
$fake->assertHtmlRendered(fn (string $html) => str_contains($html, 'Hello'));
```

### assertNothingRendered

Assert that no OG images were rendered:

```php
$fake->assertNothingRendered();
```

## Meta tags in tests

The fake still returns valid meta tag HTML, so you can assert on the rendered output:

```php
OgImage::fake();

$response = $this->get('/blog/my-post');

$response->assertSee('<meta property="og:image"', false);
$response->assertSee('<meta name="twitter:card" content="summary_large_image">', false);
```
