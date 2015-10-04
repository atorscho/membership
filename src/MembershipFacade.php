<?php

namespace Atorscho\Membership;

use Illuminate\Support\Facades\Facade;

class MembershipFacade extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return 'membership';
    }
}
