<?php

use Illuminate\Support\Facades\Cache;
use Spatie\OgImage\OgImage;

beforeEach(function () {
    $this->ogImage = app(OgImage::class);
});

it('can hash html content', function () {
    $hash = $this->ogImage->hash('<div>Hello</div>');

    expect($hash)->toBe(md5('<div>Hello</div>'));
});

it('can generate template and meta tags from html', function () {
    $result = $this->ogImage->html('<div>Hello</div>');

    $hash = md5('<div>Hello</div>');

    expect($result->toHtml())
        ->toContain('<template data-og-image><div>Hello</div></template>')
        ->toContain('<meta property="og:image"')
        ->toContain('<meta name="twitter:image"')
        ->toContain('<meta name="twitter:card" content="summary_large_image">')
        ->toContain($hash.'.jpeg');
});

it('stores the current url in cache', function () {
    $this->ogImage->html('<div>Hello</div>');

    $hash = md5('<div>Hello</div>');

    expect(Cache::get("og-image:{$hash}"))->toBe('http://localhost');
});

it('can store and retrieve a url from cache', function () {
    $this->ogImage->storeUrlInCache('test-hash', 'https://example.com/my-page');

    expect($this->ogImage->getUrlFromCache('test-hash'))->toBe('https://example.com/my-page');
});

it('returns null when url is not in cache', function () {
    expect($this->ogImage->getUrlFromCache('nonexistent'))->toBeNull();
});

it('can generate a url for a hash', function () {
    $url = $this->ogImage->url('abc123', 'png');

    expect($url)->toBe('https://example.com/og-image/abc123.png');
});

it('uses the configured format by default', function () {
    config()->set('og-image.format', 'webp');

    $url = $this->ogImage->url('abc123');

    expect($url)->toContain('abc123.webp');
});

it('can build the image path', function () {
    $path = $this->ogImage->imagePath('abc123', 'png');

    expect($path)->toBe('og-images/abc123.png');
});

it('uses the configured path', function () {
    config()->set('og-image.path', 'custom-og-images');

    $path = $this->ogImage->imagePath('abc123', 'png');

    expect($path)->toBe('custom-og-images/abc123.png');
});
