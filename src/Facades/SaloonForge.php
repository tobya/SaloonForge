<?php

namespace Tobya\SaloonForge\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @see \Tobya\SaloonForge\SaloonForge
 */
class SaloonForge extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return \Tobya\SaloonForge\SaloonForge::class;
    }
}
