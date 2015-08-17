<?php

namespace Atorscho\Uservel\Permissions;

use Atorscho\Uservel\Traits\CreateModel;
use Exception;

trait PermissionAttachments
{
    use CreateModel;

    /**
     * Add permission(s) to the user or group.
     *
     * @param int|string|Permission $permissions May be permission ID, handle or model object.
     *
     * @throws Exception
     */
    public function addPermission($permissions)
    {
        // If $permissions is '*', attach to all permissions
        if ($permissions == ['*']) {
            $this->addPermission(Permission::lists('id')->all());

            return;
        }

        if ($permissions instanceof Permission) {
            $permissions = [$permissions];
        } else {
            $permissions = (array) $permissions;
        }

        foreach ($permissions as $permission) {
            if ($permission instanceof Permission) {
                $permission = $permission->id;
            } elseif (!is_numeric($permission)) {
                $name       = $permission;
                $permission = Permission::whereHandle($permission)->first();

                if (!$permission) {
                    throw new Exception("Permission [{$name}] does not exist.");
                }

                $permission = $permission->id;
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
