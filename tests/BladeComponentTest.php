<?php

use Illuminate\Support\Facades\Cache;

it('renders template tag and meta tags from blade component', function () {
    $view = $this->blade('<x-og-image><div>Hello World</div></x-og-image>');

    $view->assertSee('<template data-og-image>', false);
    $view->assertSee('</template>', false);
    $view->assertSee('<meta property="og:image"', false);
    $view->assertSee('<meta name="twitter:image"', false);
    $view->assertSee('<meta name="twitter:card" content="summary_large_image">', false);
});

it('includes the slot content inside the template tag', function () {
    $view = $this->blade('<x-og-image><div class="my-og-content">Hello</div></x-og-image>');

    $view->assertSee('<template data-og-image><div class="my-og-content">Hello</div></template>', false);
});

it('stores the page url in cache', function () {
    $slotHtml = '<div>Hello World</div>';

    $this->blade('<x-og-image>'.$slotHtml.'</x-og-image>');

    $hash = md5($slotHtml);

    expect(Cache::get("og-image:{$hash}"))->toBe('http://localhost');
});

it('accepts a format attribute', function () {
    $view = $this->blade('<x-og-image format="webp"><div>Hello</div></x-og-image>');

    $view->assertSee('.webp', false);
});
