<?php

namespace Atorscho\Uservel;

use Illuminate\Support\Facades\Facade;

class UservelFacade extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return 'uservel';
    }
}
