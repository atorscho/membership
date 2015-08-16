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
        // Get the ID
        $this->attributes['id'] = $this->orderBy('id', 'desc')->first()->id + 1;

        if ($permissions == '*') {
            $this->addPermission(Permission::lists('id')->all());

            return;
        }

        $permissions = explode('|', $permissions);

        $this->addPermission($permissions);
    }
}
