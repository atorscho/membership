<?php

namespace Atorscho\Uservel\Permissions;

trait PermissionAttachments
{
    /**
     * Add permission(s) to the user or group.
     *
     * @param int|string|Permission $permissions May be permission ID, handle or model object.
     */
    public function addPermission($permissions)
    {
        if ($permissions instanceof Permission) {
            $permissions = [$permissions];
        } else {
            $permissions = (array) $permissions;
        }

        foreach ($permissions as $permission) {
            if ($permission instanceof Permission) {
                $permission = $permission->id;
            } elseif (!is_numeric($permission)) {
                $permission = Permission::whereHandle($permission)->first()->id;
            }

            // Attach the permission
            $this->permissions()->attach($permission);
        }
    }

    /**
     * Remove permission(s) from the user or group.
     *
     * @param int|string|Permission $permissions May be permission ID, handle or model object.
     */
    public function removePermission($permissions)
    {
        if ($permissions instanceof Permission) {
            $permissions = [$permissions];
        } else {
            $permissions = (array) $permissions;
        }

        foreach ($permissions as $permission) {
            if ($permission instanceof Permission) {
                $permission = $permission->id;
            } elseif (!is_numeric($permission)) {
                $permission = Permission::whereHandle($permission)->first()->id;
            }

            // Detach the permission
            $this->permissions()->detach($permission);
        }
    }

    /**
     * Remove all permissions from a user or group.
     */
    public function removeAllPermissions()
    {
        $this->permissions()->sync([]);
    }
}
