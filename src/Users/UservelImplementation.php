<?php

namespace Atorscho\Uservel\Users;

use Atorscho\Uservel\Groups\Group;
use Atorscho\Uservel\Permissions\Permission;
use Exception;

interface UservelImplementation
{
    /**
     * User's groups.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function groups();

    /**
     * User's permissions.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function permissions();

    /**
     * Check if user is in specified group(s).
     *
     * @param array|string $is Group handle or an array of handles.
     *
     * @return bool
     */
    public function is($is);

    /**
     * Check for user's or user group's permission.
     *
     * @param array|string $can Permission handle or an array of handles.
     *
     * @return bool
     */
    public function can($can);

    /**
     * Handle dynamic method calls into the model.
     *
     * @param  string $method
     * @param  array  $parameters
     *
     * @return mixed
     * @throws Exception
     */
    public function __call($method, $parameters);

    /**
     * Add user or permission to the group.
     *
     * @param int|string|Group|null $groups May be group ID, handle or model object.
     */
    public function addGroup($groups = null);

    /**
     * Remove user or permission from the group.
     *
     * @param int|string|Group $groups May be group ID, handle or model object.
     */
    public function removeGroup($groups);

    /**
     * Remove all groups from a user or permission.
     */
    public function removeAllGroups();

    /**
     * Add permission(s) to the user or group.
     *
     * @param int|string|Permission $permissions May be permission ID, handle or model object.
     */
    public function addPermission($permissions);

    /**
     * Remove permission(s) from the user or group.
     *
     * @param int|string|Permission $permissions May be permission ID, handle or model object.
     */
    public function removePermission($permissions);

    /**
     * Remove all permissions from a user or group.
     */
    public function removeAllPermissions();

    /**
     * Attach permissions.
     *
     * @param string $permissions Separate permissions with a pipe "|".
     */
    public function setPermissionsAttribute($permissions);
}
