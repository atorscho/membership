<?php

namespace Atorscho\Membership;

use Illuminate\Support\Facades\Facade;

/**
 * Class MembershipFacade
 *
 * @package Atorscho\Membership
 * @author  Alex Torscho <contact@alextorscho.com>
 * @version 2.0.0
 */
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
