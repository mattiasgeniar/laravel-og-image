<?php

namespace Spatie\OgImage;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\HtmlString;

class OgImage
{
    public function html(string $html, ?string $format = null, ?int $width = null, ?int $height = null): HtmlString
    {
        $format ??= $this->defaultFormat();
        $hash = $this->hash($html, $width, $height);

        $this->storeInCache($hash, app(OgImageGenerator::class)->resolveScreenshotUrl(), $width, $height);

        $attributes = collect([
            'data-og-image' => true,
            'data-og-hash' => $hash,
            'data-og-format' => $format,
            'data-og-width' => $width,
            'data-og-height' => $height,
        ])
            ->filter()
            ->map(fn ($value, $key) => $value === true ? $key : "{$key}=\"{$value}\"")
            ->implode(' ');

        return new HtmlString("<template {$attributes}>{$html}</template>");
    }

    public function url(string $hash, ?string $format = null): string
    {
        $format ??= $this->defaultFormat();
        $baseUrl = rtrim(config('app.url'), '/');

        return "{$baseUrl}/og-image/{$hash}.{$format}";
    }

    public function defaultFormat(): string
    {
        return config('og-image.format', 'jpeg');
    }

    public function hash(string $html, ?int $width = null, ?int $height = null): string
    {
        $subject = $width !== null && $height !== null
            ? "{$html}-{$width}x{$height}"
            : $html;

        return md5($subject);
    }

    public function storeInCache(string $hash, string $url, ?int $width = null, ?int $height = null): void
    {
        if (Cache::has("og-image:{$hash}")) {
            return;
        }

        $data = ['url' => $url];

        if ($width !== null && $height !== null) {
            $data['width'] = $width;
            $data['height'] = $height;
        }

        Cache::forever("og-image:{$hash}", $data);
    }

    public function getFromCache(string $hash): ?array
    {
        return Cache::get("og-image:{$hash}");
    }

    public function imagePath(string $hash, string $format): string
    {
        $path = config('og-image.path', 'og-images');

        return "{$path}/{$hash}.{$format}";
    }

    public function metaTags(string $hash, string $format): HtmlString
    {
        $url = e($this->url($hash, $format));

        $tags = implode(PHP_EOL, [
            "<meta property=\"og:image\" content=\"{$url}\">",
            "<meta name=\"twitter:image\" content=\"{$url}\">",
            '<meta name="twitter:card" content="summary_large_image">',
        ]);

        return new HtmlString($tags);
    }
}
