<?php

namespace Spatie\OgImage\Actions;

use Spatie\OgImage\Support\TemplateExtractor;

class RenderOgImageScreenshotAction
{
    public function execute(string $content): ?string
    {
        $extracted = TemplateExtractor::extract($content);

        if ($extracted === null) {
            return null;
        }

        return view('og-image::screenshot', [
            'head' => $this->extractHead($content),
            'templateContent' => $extracted['content'],
            'width' => $extracted['width'] ?? config('og-image.width', 1200),
            'height' => $extracted['height'] ?? config('og-image.height', 630),
        ])->render();
    }

    protected function extractHead(string $html): string
    {
        if (! preg_match('/<head\b[^>]*>(.*?)<\/head>/si', $html, $matches)) {
            return '';
        }

        return $matches[1];
    }
}
