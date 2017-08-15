<?php

use Atorscho\Membership\Group;

if (!function_exists('is_logged_in')) {
    /**
     * Determine if the current user is authenticated.
     */
    function is_logged_in(): bool
    {
        return Membership::isLoggedIn();
	}
}

if (!function_exists('is_guest')) {
    /**
     * Determine if the current user is a guest.
     */
    function is_guest(): bool
    {
        return Membership::isGuest();
    }
}

if (!function_exists('user')) {
    /**
     * Retrieve currently authenticated user or his attribute value.
     *
     * @return \Illuminate\Contracts\Auth\Authenticatable|string|null
     */
    function user(?string $attribute = null)
	{
        return Membership::user($attribute);
	}
}

if (!function_exists('user_can')) {
    /**
     * Check whether the user has a given permission.
     */
    function user_can(string $code, ?Model $model = null, string $userForeignKey = 'user_id'): bool
    {
        if (is_guest()) {
            return false;
        }

        return user()->hasPermission($code, $model, $userForeignKey);
	}
}

if (!function_exists('user_is')) {
    /**
     * Check whether the user is assigned to a group.
     *
     * @param int|string|Group $group
     *
     * @return bool
     */
    function user_is($group): bool
    {
        if (is_guest()) {
            return false;
        }

        return user()->isAssignedTo($group);
	}
}
