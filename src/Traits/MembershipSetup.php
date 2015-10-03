<?php

namespace Atorscho\Membership\Traits;

use Atorscho\Membership\Groups\Group;
use Atorscho\Membership\Groups\ManageGroups;
use Atorscho\Membership\Permissions\ManagePermissions;
use Atorscho\Membership\Permissions\Permission;

trait MembershipSetup
{
    use ManageGroups, ManagePermissions;

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
     * User's own permissions.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function permissions()
    {
        return $this->belongsToMany(Permission::class, 'user_permissions');
    }

    public function is($group, $and = true)
    {
        // If $group contains ',', check EVERY group
        // If $group contains '|', check AT LEAST ONE group


    }
}
