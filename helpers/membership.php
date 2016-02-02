<?php

use Atorscho\Membership\Exceptions\IncorrectParameterType;

if (!function_exists('current_user')) {
    /**
     * Return user instance or its model attribute.
     *
     * @param string|null $attribute
     *
     * @return object|string|bool
     */
    function current_user($attribute = null, $default = '')
    {
        return Membership::currentUser($attribute, $default);
    }
}

if (!function_exists('current_user_is')) {
    /**
     * Check the user belonging to a group.
     *
     * @param array|string $groups Comma or pipe separated list of group handles,
     *                             or an array of handles.
     * @param bool         $strict
     *
     * @return bool
     */
    function current_user_is($groups, $strict = true)
    {
        if (!is_logged_in()) {
            return false;
        }

        return current_user()->is($groups, $strict);
    }
}

if (!function_exists('current_user_can')) {
    /**
     * Determine if a user has permission to perform some action.
     *
     * @param array|string $permissions Comma or pipe separated list of permission handles,
     *                                  or an array of handles.
     * @param object       $model       [Optional]
     * @param string       $column      [Optional]
     *
     * @return bool
     * @throws IncorrectParameterType
     */
    function current_user_can($permissions, $model = null, $column = null)
    {
        if (!is_logged_in()) {
            return false;
        }

        return current_user()->can($permissions, $model, $column);
    }
}

if (!function_exists('is_logged_in')) {
    /**
     * Return true if user is logged in.
     *
     * @return bool
     */
    function is_logged_in()
    {
        return auth()->check();
    }
}

if (! function_exists('is_logged_out')) {
    /**
     * Return true if user is not logged in.
     *
     * @return bool
     */
    function is_logged_out()
    {
        return auth()->guest();
	}
}

if (!function_exists('avatar')) {
    /**
     * Get user's avatar. If none found, return the default one.
     *
     * @param object $user User model instance. [Optional]
     *
     * @return string
     */
    function avatar($user = null)
    {
        return Membership::avatar($user);
    }
}

if (!function_exists('avatar_exists')) {
    /**
     * Check wheter user avatar exists.
     *
     * @param object|null $user User model instance. [Optional]
     *
     * @return bool
     */
    function avatar_exists($user = null)
    {
        return Membership::avatarExists($user);
    }
}

if (!function_exists('str_obfuscate')) {
    /**
     * Obfuscate a string.
     *
     * (c) Laravel (Collective)
     *
     * @param string $value
     *
     * @return string
     */
    function str_obfuscate($value)
    {
        return Membership::obfuscate($value);
    }
}
