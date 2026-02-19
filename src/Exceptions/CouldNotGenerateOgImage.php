<?php

namespace Spatie\OgImage\Exceptions;

use Exception;

class CouldNotGenerateOgImage extends Exception
{
    public static function urlNotFound(string $hash): self
    {
        return new self("Could not find the URL for OG image with hash [{$hash}]. The cache entry may have expired.");
    }
}
