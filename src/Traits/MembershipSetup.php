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

    /**
     * Check the user belonging to a group.
     *
     * @param array|string $groups Comma or pipe separated list of group handles,
     *                             or an array of handles.
     * @param bool         $strict
     *
     * @return bool
     */
    public function is($groups, $strict = true)
    {
        // Check if $group is a "|" separated list
        if (is_string($groups) && str_contains($groups, '|')) {
            // Strict search is off, check AT LEAST ONE group
            $strict = false;
            $groups = explode('|', $groups);
        } // Or if $group is a "," separated list
        elseif (is_string($groups) && str_contains($groups, ',')) {
            // Strict search is on, check ALL groups
            $strict = true;
            $groups = explode(',', $groups);
        }

        // Ensure $group is always an array
        $groups = (array) $groups;

        // Ensure group handles are in plural
        $groups = array_map(function ($item) {
            return str_plural($item);
        }, $groups);

        // Check ALL groups
        if ($strict) {
            return count(array_intersect($this->groups->lists('handle')->all(), $groups)) == count($groups);
        }

        return (bool) array_intersect($this->groups->lists('handle')->all(), $groups);
    }
}
