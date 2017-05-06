<?php

namespace Atorscho\Membership;

use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Support\Collection;

trait Membershipable
{
    use Assignable;

    /**
     * Get user's groups.
     */
    public function groups(): BelongsToMany
    {
        return $this->belongsToMany(Group::class, 'user_groups');
    }

    /**
     * User's own permissions.
     */
    public function permissions(): BelongsToMany
    {
        return $this->belongsToMany(Permission::class, 'user_permissions');
    }

    /**
     * Attach given permissions to the group.
     *
     * @param array|Permission|Collection|string $permissions
     */
    public function attach($permissions): void
    {
        if (!is_array($permissions) && !$permissions instanceof Collection) {
            $permissions = func_get_args();
        }

        foreach ($permissions as $permission) {
            $permission = $this->resolvePermission($permission);

            $this->permissions()->attach($permission);
        }
    }

    /**
     * Resolve the $permission parameter.
     *
     * @param int|string|Permission $permission
     *
     * @return Permission
     */
    protected function resolvePermission($permission): Permission
    {
        if (is_string($permission)) {
            $permission = Permission::search($permission);
        } elseif (is_int($permission)) {
            $permission = Permission::find($permission);
        }

        return $permission;
    }
}
