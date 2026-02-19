<?php

namespace Spatie\OgImage\Actions;

class RenderOgImageScreenshotAction
{
    public function execute(string $content): ?string
    {
        $templateContent = $this->extractTemplateContent($content);

        if ($templateContent === null) {
            return null;
        }

        $head = $this->extractHead($content);

        return $this->renderScreenshot($head, $templateContent);
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

    protected function renderScreenshot(string $head, string $templateContent): string
    {
        return view('og-image::screenshot', [
            'head' => $head,
            'templateContent' => $templateContent,
            'width' => config('og-image.width', 1200),
            'height' => config('og-image.height', 630),
        ])->render();
    }
}
