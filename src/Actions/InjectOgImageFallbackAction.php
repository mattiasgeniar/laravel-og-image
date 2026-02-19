<?php

namespace Spatie\OgImage\Actions;

use Illuminate\Http\Request;
use Illuminate\View\View;
use Spatie\OgImage\OgImage;
use Spatie\OgImage\OgImageGenerator;

class InjectOgImageFallbackAction
{
    public function execute(Request $request, string $content): ?string
    {
        $fallbackHtml = $this->renderFallback($request);

        if ($fallbackHtml === null) {
            return null;
        }

        $hash = $this->hashContent($fallbackHtml);

        $this->cachePageUrl($hash, $this->resolveScreenshotUrl());

        $content = $this->injectMetaTags($content, $hash);
        $content = $this->injectTemplate($content, $fallbackHtml);

        return $content;
    }

    protected function renderFallback(Request $request): ?string
    {
        $fallback = app(OgImageGenerator::class)->getFallbackUsing();

        if ($fallback === null) {
            return null;
        }

        $view = $fallback($request);

        if ($view === null) {
            return null;
        }

        return $view instanceof View ? $view->render() : (string) $view;
    }

    protected function hashContent(string $html): string
    {
        return app(OgImage::class)->hash($html);
    }

    protected function resolveScreenshotUrl(): string
    {
        return app(OgImageGenerator::class)->resolveScreenshotUrl();
    }

    protected function cachePageUrl(string $hash, string $url): void
    {
        app(OgImage::class)->storeUrlInCache($hash, $url);
    }

    protected function injectMetaTags(string $content, string $hash): string
    {
        $format = config('og-image.format', 'jpeg');
        $metaTags = app(OgImage::class)->metaTags($hash, $format)->toHtml();

        return $this->injectBeforeClosingHead($content, $metaTags);
    }

    protected function injectTemplate(string $content, string $html): string
    {
        $template = '<template data-og-image>'.$html.'</template>';

        return $this->injectBeforeClosingBody($content, $template);
    }

    protected function injectBeforeClosingHead(string $html, string $inject): string
    {
        if (stripos($html, '</head>') !== false) {
            return str_ireplace('</head>', $inject.PHP_EOL.'</head>', $html);
        }

        return $html;
    }

    protected function injectBeforeClosingBody(string $html, string $inject): string
    {
        if (stripos($html, '</body>') !== false) {
            return str_ireplace('</body>', $inject.PHP_EOL.'</body>', $html);
        }

        return $html;
    }
}
