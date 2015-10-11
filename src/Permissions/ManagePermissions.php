<?php

namespace Atorscho\Membership\Permissions;

trait ManagePermissions
{
    /**
     * Grant permissions to the model object.
     *
     * @param Permission|string|null $permissions Permission instance, string (separated by a "|"),
     *                                            or an array of permissions.
     */
    public function givePermissionTo($permissions = null)
    {
        // No permissions if parameter is an empty string
        if (is_string($permissions) && empty($permissions)) {
            return null;
        }

        // No parameter provided or '*', give all permissions
        if (is_null($permissions) || $permissions == '*') {
            $permissions = Permission::lists('id')->all();

            return $this->permissions()->attach($permissions);
        }

        // If the parameter is an instance of Permission, attach it
        if ($permissions instanceof Permission) {
            return $this->permissions()->attach($permissions);
        }

        // If the parameter is a string, explode it to an array by '|'
        if (is_string($permissions)) {
            $permissions = explode('|', $permissions);
        }

        // Convert all array items to an instance of Permission
        foreach ($permissions as $permission) {
            if (is_string($permission)) {
                $permission = Permission::whereHandle($permission)->first();
            }

            // Attach the permission
            $this->permissions()->attach($permission);
        }
    }

    /**
     * Remove permissions from the model object.
     *
     * @param Permission|string|null $permissions Permission instance, string (separated by a "|"),
     *                                            or an array of permissions.
     *
     * @return int
     */
    public function removePermissionTo($permissions = null)
    {
        // No parameter provided or '*', give all permissions
        if (is_null($permissions) || $permissions == '*') {
            $permissions = Permission::lists('id')->all();

            return $this->permissions()->detach($permissions);
        }

        // If the parameter is an instance of Permission, detach it
        if ($permissions instanceof Permission) {
            return $this->permissions()->detach($permissions);
        }

        // If the parameter is a string, explode it to an array by '|'
        if (is_string($permissions)) {
            $permissions = explode('|', $permissions);
        }

        // Convert all array items to an instance of Permission
        foreach ($permissions as $permission) {
            if (is_string($permission)) {
                $permission = Permission::whereHandle($permission)->firstOrFail();
            }

            // Attach the permission
            $this->permissions()->detach($permission);
        }

        return true;
    }

    /**
     * Synchronize permissions by attaching and detaching them.
     *
     * @param array $ids
     * @param bool  $detaching
     *
     * @return mixed
     */
    public function syncPermissions($ids, $detaching = true)
    {
        return $this->permissions()->sync($ids, $detaching);
    }
}
