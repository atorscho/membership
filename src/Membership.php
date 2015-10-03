<?php

namespace Atorscho\Membership;

use Illuminate\Auth\Guard;

class Membership
{
    /**
     * @var Guard
     */
    private $auth;

    /**
     * Membership constructor.
     *
     * @param Guard $auth
     */
    public function __construct(Guard $auth)
    {
        $this->auth = $auth;
    }

    /**
     * Get currently logged in user instance or its attribute value.
     *
     * @param string|null $attribute
     *
     * @return \Illuminate\Contracts\Auth\Authenticatable|null
     */
    public function currentUser($attribute = null)
    {
        $class = config('membership.users.model');

        $user = $class::with('groups', 'permissions')->findOrFail($this->auth->id());

        if (!$attribute) {
            return $user;
        }

        return $user->{$attribute} ?: null;
    }


}
