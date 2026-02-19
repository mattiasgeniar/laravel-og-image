<?php

namespace Spatie\OgImage\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Spatie\OgImage\Actions\InjectOgImageFallbackAction;
use Spatie\OgImage\Actions\RenderOgImageScreenshotAction;
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

        if (! $this->hasTemplateTag($content)) {
            $fallbackAction = OgImageGenerator::getActionClass('inject_og_image_fallback', InjectOgImageFallbackAction::class);

            $content = $fallbackAction->execute($request, $content);

            if ($content === null) {
                return $response;
            }

            $response->setContent($content);
        }

        if (! $request->has('ogimage')) {
            return $response;
        }

        $screenshotAction = OgImageGenerator::getActionClass('render_og_image_screenshot', RenderOgImageScreenshotAction::class);

        $html = $screenshotAction->execute($response->getContent());

        if ($html === null) {
            return $response;
        }

        $response->setContent($html);

        return $response;
    }

    protected function hasTemplateTag(string $html): bool
    {
        return str_contains($html, '<template data-og-image>');
    }
}
