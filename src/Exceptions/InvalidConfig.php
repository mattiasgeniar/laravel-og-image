<?php

namespace Spatie\OgImage\Exceptions;

use Exception;

class InvalidConfig extends Exception
{
    public static function invalidAction(string $actionName, string $configuredClass, string $expectedClass): self
    {
        return new self("The configured action `{$configuredClass}` for `{$actionName}` must extend `{$expectedClass}`.");
    }
}
