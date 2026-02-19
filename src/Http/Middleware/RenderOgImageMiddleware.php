<?php

namespace Spatie\OgImage\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Spatie\OgImage\OgImage;
use Spatie\OgImage\OgImageGenerator;
use Symfony\Component\HttpFoundation\Response;

class RenderOgImageMiddleware
{
    public function handle(Request $request, Closure $next): Response
    {
        $response = $next($request);

        $content = $response->getContent();

        if (! is_string($content)) {
            return $response;
        }

        $hasTemplate = $this->hasTemplateTag($content);

        if (! $hasTemplate) {
            $content = $this->injectFallback($request, $content);

            if ($content === null) {
                return $response;
            }

            $response->setContent($content);
        }

        if (! $request->has('ogimage')) {
            return $response;
        }

        $templateContent = $this->extractTemplateContent($response->getContent());

        if ($templateContent === null) {
            return $response;
        }

        $head = $this->extractHead($response->getContent());
        $width = config('og-image.width', 1200);
        $height = config('og-image.height', 630);

        $html = <<<HTML
        <!DOCTYPE html>
        <html>
        <head>
            {$head}
            <style>
                *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }
                html, body { width: {$width}px; height: {$height}px; overflow: hidden; }
            </style>
        </head>
        <body>{$templateContent}</body>
        </html>
        HTML;

        $response->setContent($html);

        return $response;
    }

    protected function injectFallback(Request $request, string $content): ?string
    {
        $generator = app(OgImageGenerator::class);
        $fallback = $generator->getFallbackUsing();

        if ($fallback === null) {
            return null;
        }

        $view = $fallback($request);

        if ($view === null) {
            return null;
        }

        $html = $view instanceof View ? $view->render() : (string) $view;

        $ogImage = app(OgImage::class);
        $format = config('og-image.format', 'jpeg');
        $hash = $ogImage->hash($html);

        $ogImage->storeUrlInCache($hash, $generator->resolveScreenshotUrl());

        $template = '<template data-og-image>'.$html.'</template>';
        $metaTags = $ogImage->metaTags($hash, $format)->toHtml();

        $content = $this->injectBeforeClosingHead($content, $metaTags);
        $content = $this->injectBeforeClosingBody($content, $template);

        return $content;
    }

    protected function hasTemplateTag(string $html): bool
    {
        return str_contains($html, '<template data-og-image>');
    }

    protected function extractTemplateContent(string $html): ?string
    {
        if (preg_match('/<template\s+data-og-image>(.*?)<\/template>/s', $html, $matches)) {
            return $matches[1];
        }

        return null;
    }

    protected function extractHead(string $html): string
    {
        if (preg_match('/<head\b[^>]*>(.*?)<\/head>/si', $html, $matches)) {
            return $matches[1];
        }

        return '';
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
