<?php

namespace Spatie\OgImage\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RenderOgImageMiddleware
{
    public function handle(Request $request, Closure $next): Response
    {
        $response = $next($request);

        if (! $request->has('ogimage')) {
            return $response;
        }

        $content = $response->getContent();

        if (! is_string($content)) {
            return $response;
        }

        $templateContent = $this->extractTemplateContent($content);

        if ($templateContent === null) {
            return $response;
        }

        $head = $this->extractHead($content);
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
}
