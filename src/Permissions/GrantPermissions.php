<?php

namespace Atorscho\Uservel\Permissions;

trait GrantPermissions
{
    /**
     * Add permissions.
     *
     * @param int|string|Permission $permissions May be permission ID, handle, object or an array.
     */
    public function grantPermissions($permissions)
    {
        $permissions = (array) $permissions;

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
}
