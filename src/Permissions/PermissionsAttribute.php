<?php

namespace Atorscho\Uservel\Permissions;

trait PermissionsAttribute
{
    /**
     * Attach permissions.
     *
     * @param string $permissions Separate permissions with a pipe "|".
     */
    public function setPermissionsAttribute($permissions)
    {
        if ($permissions == '*') {
            $this->addPermission(Permission::lists('id')->all());

            return;
        }

        $permissions = explode('|', $permissions);

        $this->addPermission($permissions);
    }
}
