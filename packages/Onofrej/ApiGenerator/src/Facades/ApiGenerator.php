<?php

namespace Onofrej\ApiGenerator\Facades;

use Illuminate\Support\Facades\Facade;

class ApiGenerator extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return 'apigenerator';
    }
}
