<?php

namespace Spatie\OgImage\Exceptions;

use Exception;

class InvalidOgImage extends Exception
{
    public static function widthAndHeightMustBothBeProvided(): self
    {
        return new self('Both `width` and `height` must be provided, or neither.');
    }
}
