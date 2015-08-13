<?php

use Illuminate\Database\Eloquent\Model;

if (!function_exists('create_user')) {
    /**
     * Create a user and add him to a specified group.
     *
     * @param array      $attributes
     * @param int|string $group
     *
     * @return object
     */
    function create_user(array $attributes, $group = 'members')
    {
        return Uservel::createUser($attributes, $group);
    }
}

if (!function_exists('create_member')) {
    /**
     * Create a member.
     *
     * @param array $attributes
     *
     * @return object
     */
    function create_member(array $attributes)
    {
        return Uservel::createMember($attributes);
    }
}

if (!function_exists('create_moderator')) {
    /**
     * Create a moderator.
     *
     * @param array $attributes
     *
     * @return object
     */
    function create_moderator(array $attributes)
    {
        return Uservel::createModerator($attributes);
    }
}

if (!function_exists('create_admin')) {
    /**
     * Create an administrator.
     *
     * @param array $attributes
     *
     * @return object
     */
    function create_admin(array $attributes)
    {
        return Uservel::createAdmin($attributes);
    }
}

if (!function_exists('create_superadmin')) {
    /**
     * Create an superadministrator.
     *
     * @param array $attributes
     *
     * @return object
     */
    function create_superadmin(array $attributes)
    {
        return Uservel::createSuperadmin($attributes);
    }
}

if (!function_exists('current_user')) {
    /**
     * Return user instance or its model attribute.
     *
     * @param string|null $attribute
     *
     * @return object|string|bool
     */
    function current_user($attribute = null)
    {
        return Uservel::currentUser($attribute);
    }
}

if (!function_exists('current_user_is')) {
    /**
     * Check if user is in specified group(s).
     *
     * @param array|string $is
     *
     * @return bool
     */
    function current_user_is($is)
    {
        if (Auth::guest()) {
            return false;
        }

        return Auth::user()->is($is);
    }
}

if (!function_exists('current_user_can')) {
    /**
     * Check for user's or user group's permission.
     *
     * @param array|string $can        Permission handle or an array of handles.
     * @param Model|null   $model      Check if model's user_id relation is current user's ID.
     * @param bool         $checkOwner Set to false if you do not want to check model's ownership.
     *
     * @return bool
     */
    function current_user_can($can, $model = null, $checkOwner = true)
    {
        if (Auth::guest()) {
            return false;
        }

        return Auth::user()->can($can, $model, $checkOwner);
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
        return Auth::check();
    }
}

if (!function_exists('user_avatar')) {
    /**
     * Return user's avatar if it exists, otherwise return the default one.
     *
     * @param string $avatar Avatar file name.
     *
     * @return string
     */
    function user_avatar($avatar = '')
    {
        return Uservel::avatar($avatar);
    }
}

if (!function_exists('avatar_exists')) {
    /**
     * Check if user's avatar exists.
     *
     * @param Model|string|null $avatar Avatar file name or user's object.
     *                                  If empty, checks for current user.
     *
     * @return bool
     */
    function avatar_exists($avatar = null)
    {
        return Uservel::avatarExists($avatar);
    }
}
