<?php

namespace Antennaio\Clyde\Facades;

use Illuminate\Support\Facades\Facade;

class ClydeUpload extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return 'laravel-clyde-upload';
    }
}
