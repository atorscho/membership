<?php

namespace Atorscho\Uservel\Traits;

use Atorscho\Uservel\Groups\Group;
use Atorscho\Uservel\Groups\GroupAttachments;
use Atorscho\Uservel\Permissions\Permission;
use Atorscho\Uservel\Permissions\PermissionAttachments;
use Atorscho\Uservel\Permissions\PermissionsAttribute;
use BadMethodCallException;
use Exception;

trait UservelRelations
{
    use GroupAttachments, PermissionAttachments, PermissionsAttribute;

    /**
     * User's groups.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function groups()
    {
        return $this->belongsToMany(Group::class, 'user_groups');
    }

    /**
     * User's permissions.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function permissions()
    {
        return $this->belongsToMany(Permission::class, 'user_permissions');
    }

    /**
     * Check if user is in specified group(s).
     *
     * @param array|string $is Group handle or an array of handles.
     *
     * @return bool
     */
    public function is($is)
    {
        // Comma separated list of groups' names
        $groups = $this->groups->lists('handle')->all();

        // If $is is a string, check for its presence in $groups
        if (!is_array($is)) {
            return in_array(str_plural($is), $groups);
        }

        // Convert to plural all array's values
        $is = array_map('str_plural', $is);

        // Compare the count of the intersection between $is and $groups
        // If true, it means user is in all specified groups
        return count(array_intersect($is, $groups)) == count($is);
    }

    /**
     * Check for user's or user group's permission.
     *
     * @param array|string $can Permission handle or an array of handles.
     *
     * @return bool
     */
    public function can($can)
    {
        // Ensure $can is always permission's ID
        if (!is_numeric($can) && is_string($can)) {
            $can = Permission::whereHandle($can)->first();

            if (!$can) {
                return false;
            }

            $can = $can->id;
        } elseif (is_array($can)) {
            $permissions = [];

            foreach ($can as $permission) {
                if ($this->can($permission)) {
                    $permissions[] = $permission;
                }
            }

            return count(array_intersect($can, $permissions)) == count($can);
        }

        // Get user's group permissions
        $groups = $this->groups;

        // If found, return true
        foreach ($groups as $group) {
            $permissions = $group->permissions->lists('id')->all();

            if (in_array($can, $permissions)) {
                return true;
            }
        }

        // Otherwise get user's own permissions
        $permissions = $this->permissions->lists('id')->all();

        // If found, return true
        // Otherwise return false
        return in_array($can, $permissions);
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
        if (strpos($name, 'is') !== true) {
            // Remove 'is'
            $group = str_replace('is', '', $name);
            // Lowercase
            $group = strtolower($group);

            if (!Group::whereHandle($group)->first()) {
                throw new Exception('Group does not exist.');
            }

            return $this->is($group);
        } elseif (strpos($name, 'can') !== true) {
            // Remove 'can'
            $permission = str_replace('can', '', $name);
            // Lowercase
            $permission = strtolower($permission);

            if (!Permission::whereHandle($permission)->first()) {
                throw new Exception('Permission does not exist.');
            }

            return $this->is($permission);
        }

        throw new BadMethodCallException("Method {$name} does not exist.");


    }
}
