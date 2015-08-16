<?php

namespace Atorscho\Uservel\Users;

use Atorscho\Uservel\Groups\Group;
use Atorscho\Uservel\Groups\GroupAttachments;
use Atorscho\Uservel\Permissions\Permission;
use Atorscho\Uservel\Permissions\PermissionAttachments;
use Atorscho\Uservel\Permissions\PermissionsAttribute;
use Auth;
use Exception;
use Illuminate\Database\Eloquent\Model;

trait UservelRelations
{
    /**
     * Handle dynamic method calls into the model.
     *
     * @param  string $method
     * @param  array  $parameters
     *
     * @return mixed
     * @throws Exception
     */
    public function __call($method, $parameters)
    {
        if (str_contains($method, 'is')) {
            // Remove 'is'
            $group = str_replace('is', '', $method);
            // Lowercase
            $group = strtolower($group);

            if (!Group::whereHandle($group)->first()) {
                throw new Exception('Group does not exist.');
            }

            return $this->is($group);
        } elseif (str_contains($method, 'can')) {
            // Remove 'can'
            $permission = str_replace('can', '', $method);
            // Lowercase
            $permission = strtolower($permission);

            if (!Permission::whereHandle($permission)->first()) {
                throw new Exception('Permission does not exist.');
            }

            return $this->can($permission, $parameters[0]);
        }

        if (in_array($method, ['increment', 'decrement'])) {
            return call_user_func_array([$this, $method], $parameters);
        }

        $query = $this->newQuery();

        return call_user_func_array([$query, $method], $parameters);
    }

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
     * @param array|string $can        Permission handle or an array of handles.
     * @param Model|null   $model      Check if model's user_id relation is current user's ID.
     * @param bool         $checkOwner Set to false if you do not want to check model's ownership.
     *
     * @return bool
     */
    public function can($can, $model = null, $checkOwner = true)
    {
        // If model is provided, check if current user can edit it
        if (isset($model) && is_object($model)) {
            // Check model's owner
            if ($checkOwner && isset($model->user_id)) {
                return $model->user_id == current_user('id');
            }

            return $this->can($can);
        }

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
}
