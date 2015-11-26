<?php
namespace Libtek\Jmes\Facades;

use Illuminate\Support\Facades\Facade;

class Jmes Extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return 'jmes';
    }
}