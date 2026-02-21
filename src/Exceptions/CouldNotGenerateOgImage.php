<?php

namespace Spatie\OgImage\Exceptions;

use Exception;
use Throwable;

class CouldNotGenerateOgImage extends Exception
{
    public static function screenshotFailed(string $url, Throwable $previous): self
    {
        return new self(
            "Could not generate OG image for URL `{$url}`: {$previous->getMessage()}",
            previous: $previous,
        );
    }
}
