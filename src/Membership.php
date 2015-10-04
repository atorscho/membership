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
     * @param string|null $attribute User column name.
     *
     * @return \Illuminate\Contracts\Auth\Authenticatable|null
     */
    public function currentUser($attribute = null)
    {
        if (auth()->guest()) {
            return null;
        }

        $user = $this->getCurrentUserInstance();

        if (!$attribute) {
            return $user;
        }

        return $user->{$attribute} ?: null;
    }

    /**
     * Determine if the authenticated user's attribute is not empty.
     *
     * @param string $attribute
     *
     * @return bool
     */
    public function currentUserHas($attribute)
    {
        return auth()->guest() && $this->getCurrentUserInstance()->{$attribute};
    }

    public function avatar($user = null)
    {
        return asset($this->getAvatarPath() . $user->avatar);
    }

    /**
     * @return string
     */
    protected function getAvatarPath()
    {
        return trim(config('membership.users.avatar.path', " \t\n\r\0\x0B\/")) . '/';
    }

    /**
     *
     *
     * @return object
     */
    protected function getCurrentUserInstance()
    {
        $class = config('membership.users.model');

        $user = $class::with('groups', 'permissions')->findOrFail($this->auth->id());

        return $user;
    }
}
