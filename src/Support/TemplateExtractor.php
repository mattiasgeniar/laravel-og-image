<?php

namespace Spatie\OgImage\Support;

class TemplateExtractor
{
    public static function extract(string $html): ?array
    {
        if (! preg_match('/<template\s+data-og-image(?P<attrs>[^>]*)>(?P<content>.*?)<\/template>/s', $html, $matches)) {
            return null;
        }

        return [
            'content' => $matches['content'],
            'width' => self::extractAttribute('data-og-width', $matches['attrs']),
            'height' => self::extractAttribute('data-og-height', $matches['attrs']),
        ];
    }

    protected static function extractAttribute(string $name, string $attributes): ?int
    {
        if (! preg_match("/{$name}=\"(\d+)\"/", $attributes, $match)) {
            return null;
        }

        return (int) $match[1];
    }
}
