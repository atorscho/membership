<?php

namespace Atorscho\Uservel\Permissions;

trait PermissionsAttribute
{
    use GrantPermissions;

    /**
     * Attach permissions.
     *
     * @param string $permissions Separate permissions with a pipe "|".
     */
    public function setPermissionsAttribute($permissions)
    {
        if ($permissions == '*') {
            $this->grantPermissions(Permission::lists('id')->all());

            return;
        }

        $permissions = explode('|', $permissions);

        $this->grantPermissions($permissions);
    }
}
