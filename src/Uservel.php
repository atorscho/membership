<?php

namespace Atorscho\Uservel;

use Atorscho\Uservel\Groups\Group;
use Atorscho\Uservel\Permissions\Permission;
use Auth;
use BadMethodCallException;
use Exception;
use File;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Uservel
 *
 * @package Atorscho\Uservel
 */
class Uservel
{
    /**
     * Create a user and add him to a specified group.
     *
     * @param array      $attributes
     * @param int|string $group
     *
     * @return Model
     * @throws Exception
     */
    public function createUser(array $attributes, $group)
    {
        if (!$attributes) {
            throw new Exception('Parameter $attributes must be specified.');
        }

        $user = config('membership.users.model');

        $user = $user::create($attributes);

        if (!is_numeric($group)) {
            $group = Group::whereHandle($group)->first();

            if (!$group) {
                throw new Exception('Group does not exist.');
            }
        }

        $user->addGroup($group);

        return $user;
    }

    /**
     * Return user instance or its model attribute.
     *
     * @param string|null $attribute
     *
     * @return Model|string|bool
     */
    public function currentUser($attribute = null)
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
    public function avatar($avatar = '')
    {
        // If $avatar not specified, use authenticated user's one
        if (!$avatar && !is_null($avatar)) {
            $avatar = Auth::user()->{config('membership.users.avatar.column')};

            // Check if $avatar field value exists
            if (!Auth::user()->{config('membership.users.avatar.column')}) {
                return self::defaultAvatar();
            }
        }

        // Check for avatar file existance
        if ($this->avatarExists($avatar)) {
            return asset(config('membership.users.avatar.path') . $avatar);
        }

        // If does not exist, return the default one
        return self::defaultAvatar();
    }

    /**
     * Check if user's avatar exists.
     *
     * @param Model|string|null $avatar Avatar file name or user's object.
     *                                  If empty, checks for current user.
     *
     * @return bool
     */
    public function avatarExists($avatar = null)
    {
        if (Auth::guest()) {
            return false;
        }

        if (is_null($avatar)) {
            $avatar = Auth::user()->{config('membership.users.avatar.column')};
        } elseif ($avatar instanceof Model) {
            $avatar = $avatar->{config('membership.users.avatar.column')};
        }

        if (!$avatar || !File::exists(public_path(config('membership.users.avatar.path') . $avatar))) {
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
    protected function defaultAvatar($path = false)
    {
        if ($path) {
            return public_path(config('membership.users.avatar.default'));
        }

        return asset(config('membership.users.avatar.default'));
    }

    /**
     * Get group model by its ID or handle.
     *
     * @param int|string|Group $group
     *
     * @return Group
     * @throws Exception
     */
    protected function getGroupModel($group)
    {
        if (is_numeric($group)) {
            $group = Group::find($group);
        } elseif (is_string($group)) {
            $group = Group::whereHandle($group)->first();
        }

        if (!$group) {
            throw new Exception('Group does not exist.');
        }

        return $group;
    }

    /**
     * Get an array of permission IDs.
     *
     * '*' for all permission IDs.
     *
     * @param array|int|string|Permission $permissions
     *
     * @return array
     * @throws Exception
     */
    protected function getPermissionIds($permissions)
    {
        // Wildcard support
        if ($permissions == '*') {
            return Permission::lists('id')->all();
        }

        if (is_numeric($permissions)) {
            $permission = Permission::find($permissions);

            if (!$permission) {
                throw new Exception('Permission does not exist.');
            }

            return [(int) $permission->id];
        } elseif (is_string($permissions)) {
            $permission = Permission::whereHandle($permissions)->first();

            if (!$permission) {
                throw new Exception('Permission does not exist.');
            }

            return [(int) $permission->id];
        } elseif ($permissions instanceof Permission) {
            return [(int) $permissions->id];
        }

        $ids = [];

        foreach ($permissions as $permission) {
            $ids[] = $this->getPermissionIds($permission);
        }

        return array_flatten($ids);
    }

    /**
     * Is triggered when invoking inaccessible methods in an object context.
     *
     * @param $name      string
     * @param $arguments array
     *
     * @return mixed
     * @throws Exception
     */
    public function __call($name, $arguments)
    {
        if (!str_contains($name, 'create')) {
            throw new BadMethodCallException("Method {$name} does not exist.");
        }

        // Remove 'create'
        $group = str_replace('create', '', $name);
        // Pluralize
        $group = str_plural($group);
        // Lowercase
        $group = strtolower($group);

        if (!Group::whereHandle($group)->first()) {
            throw new Exception('Group does not exist.');
        }

        return $this->createUser($arguments[0], $group);
    }
}
