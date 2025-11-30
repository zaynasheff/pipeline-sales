<?php

namespace Zaynasheff\PipelineSales\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @see \Zaynasheff\PipelineSales\PipelineSales
 */
class PipelineSales extends Facade
{
    protected static function getFacadeAccessor()
    {
        return \Zaynasheff\PipelineSales\PipelineSales::class;
    }
}
