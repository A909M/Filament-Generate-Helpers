<?php

namespace A909M\FilamentGenerateHelpers\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @see \A909M\FilamentGenerateHelpers\FilamentGenerateHelpers
 */
class FilamentGenerateHelpers extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return \A909M\FilamentGenerateHelpers\FilamentGenerateHelpers::class;
    }
}
