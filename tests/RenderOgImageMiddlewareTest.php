<?php

use Illuminate\Support\Facades\Route;
use Spatie\OgImage\Http\Middleware\RenderOgImageMiddleware;

beforeEach(function () {
    Route::middleware(['web', RenderOgImageMiddleware::class])->get('/test-page', function () {
        return response(<<<'HTML'
        <!DOCTYPE html>
        <html>
        <head>
            <meta charset="utf-8">
            <link rel="stylesheet" href="/css/app.css">
        </head>
        <body>
            <h1>My Page</h1>
            <template data-og-image><div class="og-content">Hello OG</div></template>
            <meta property="og:image" content="https://example.com/og-image/abc123.png">
        </body>
        </html>
        HTML);
    });
});

it('does not modify the response without ogimage parameter', function () {
    $response = $this->get('/test-page');

    $response->assertOk();
    expect($response->getContent())
        ->toContain('<h1>My Page</h1>')
        ->toContain('<template data-og-image>');
});

it('renders only the template content with ogimage parameter', function () {
    $response = $this->get('/test-page?ogimage');

    $response->assertOk();

    $content = $response->getContent();

    expect($content)
        ->toContain('<div class="og-content">Hello OG</div>')
        ->toContain('width: 1200px')
        ->toContain('height: 630px')
        ->not->toContain('<h1>My Page</h1>')
        ->not->toContain('<template data-og-image>');
});

it('preserves the head content from the original page', function () {
    $response = $this->get('/test-page?ogimage');

    $content = $response->getContent();

    expect($content)
        ->toContain('<link rel="stylesheet" href="/css/app.css">')
        ->toContain('<meta charset="utf-8">');
});

it('adds css reset styles', function () {
    $response = $this->get('/test-page?ogimage');

    $content = $response->getContent();

    expect($content)
        ->toContain('box-sizing: border-box')
        ->toContain('margin: 0')
        ->toContain('overflow: hidden');
});

it('uses configured dimensions', function () {
    config()->set('og-image.width', 800);
    config()->set('og-image.height', 400);

    $response = $this->get('/test-page?ogimage');

    $content = $response->getContent();

    expect($content)
        ->toContain('width: 800px')
        ->toContain('height: 400px');
});

it('returns the original response when no template tag is found', function () {
    Route::middleware(['web', RenderOgImageMiddleware::class])->get('/no-template', function () {
        return response('<html><body><h1>No OG image here</h1></body></html>');
    });

    $response = $this->get('/no-template?ogimage');

    expect($response->getContent())->toContain('<h1>No OG image here</h1>');
});
