<?php

namespace Atorscho\Uservel;

use Atorscho\Uservel\Groups\Group;

class Uservel
{
    /**
     * Create a user and add him to a specified group.
     *
     * @param array      $attributes
     * @param int|string $group
     *
     * @return object
     * @throws \Exception
     */
    public static function createUser(array $attributes, $group)
    {
        if (!$attributes) {
            throw new \Exception('Parameter $attributes must be specified.');
        }

        $user = config('uservel.users.model');

        $user = $user::create($attributes);

        if (!is_numeric($group)) {
            $group = Group::whereHandle(str_plural($group))->first();

            if (!$group) {
                throw new \Exception('Group does not exist.');
            }

            $group = $group->id;
        }

        $user->groups()->attach($group);

        return $user;
    }

    /**
     * Create a member.
     *
     * @param array $attributes
     *
     * @return User
     */
    public static function createMember(array $attributes)
    {
        return static::createUser($attributes, 'members');
    }

    /**
     * Create a moderator.
     *
     * @param array $attributes
     *
     * @return User
     */
    public static function createModerator(array $attributes)
    {
        return static::createUser($attributes, 'moderators');
    }

    /**
     * Create an administrator.
     *
     * @param array $attributes
     *
     * @return User
     */
    public static function createAdmin(array $attributes)
    {
        return static::createUser($attributes, 'admins');
    }

    /**
     * Attach permission to the group.
     *
     * @param int|string|Group $group
     * @param array|int|string $permissions
     */
    public static function addGroupPermission($group, $permissions)
    {
        /** @var Group $group */
        $group = static::getGroupModel($group);

        $permissions = static::getPermissionIds($permissions);

        $group->permissions()->attach($permissions);
    }

    /**
     * Return user instance or its model attribute.
     *
     * @param string|null $attribute
     *
     * @return User|string|bool
     */
    public static function currentUser($attribute)
    {
        if (Auth::guest()) {
            return false;
        }

        $user = Auth::user();

        if (!$attribute) {
            return $user;
        }

        return $user->$attribute ?: false;
    }

    /**
     * Return user's avatar if it exists, otherwise return the default one.
     *
     * @param string $avatar Avatar file name.
     *
     * @return string
     */
    public static function avatar($avatar = '')
    {
        // If $avatar not specified, use authenticated user's one
        if (!$avatar && !is_null($avatar)) {
            $avatar = Auth::user()->avatar;

            // Check if $avatar field value exists
            if (!Auth::user()->avatar) {
                return self::defaultAvatar();
            }
        }

        // Check for avatar file existance
        if (static::avatarExists($avatar)) {
            return asset(config('filesystems.uploads.avatars') . $avatar);
        }

        // If does not exist, return the default one
        return self::defaultAvatar();
    }

    /**
     * Check if user's avatar exists.
     *
     * @param string $avatar Avatar file name.
     *
     * @return bool
     */
    public static function avatarExists($avatar)
    {
        if (!$avatar || !File::exists(public_path(config('filesystems.uploads.avatars') . $avatar))) {
            return false;
        }

        return true;
    }

    /**
     * Get default avatar's link.
     *
     * @param bool $path If true, return default avatar's absolute path.
     *
     * @return string
     */
    protected static function defaultAvatar($path = false)
    {
        if ($path) {
            return public_path(config('filesystems.avatars.default'));
        }

        return asset(config('filesystems.avatars.default'));
    }

    /**
     * Get group model by its ID or handle.
     *
     * @param int|string|Group $group
     *
     * @return mixed|static
     * @throws \Exception
     */
    protected static function getGroupModel($group)
    {
        if (is_numeric($group)) {
            $group = Group::find($group);
        } elseif (is_string($group)) {
            $group = Group::whereHandle($group)->first();
        } elseif (!$group instanceof Group) {
            throw new \Exception('Group does not exist.');
        }

        return $group;
    }

    /**
     * Get an array of permission IDs.
     *
     * @param array|int|string|Permission $permissions
     *
     * @return array
     * @throws \Exception
     */
    protected static function getPermissionIds($permissions)
    {
        // Wildcard support
        if ($permissions == '*') {
            return Permission::lists('id')->all();
        }

        if (is_numeric($permissions)) {
            $permission = Permission::find($permissions);

            if (!$permission) {
                throw new \Exception('Permission does not exist.');
            }

            return [(int) $permission->id];
        } elseif (is_string($permissions)) {
            $permission = Permission::whereHandle($permissions)->first();

            if (!$permission) {
                throw new \Exception('Permission does not exist.');
            }

            return [(int) $permission->id];
        } elseif ($permissions instanceof Permission) {
            return [(int) $permissions->id];
        }

        $ids = [];

        foreach ($permissions as $permission) {
            $ids[] = static::getPermissionIds($permission);
        }

        return array_flatten($ids);
    }
}
