<?php

namespace Atorscho\Membership;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Support\Collection;

/**
 * Trait Membershipable
 * This trait must be included in the User model
 * in order to get package's RBAC features.
 *
 * @package Atorscho\Membership
 * @author  Alex Torscho <contact@alextorscho.com>
 * @version 2.0.0
 */
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
     * Get user's primary group.
     */
    public function primaryGroup(): BelongsTo
    {
        return $this->belongsTo(Group::class);
    }

    /**
     * Groups the user is leading.
     */
    public function leadingGroups(): BelongsToMany
    {
        return $this->belongsToMany(Group::class, 'group_leaders');
    }

    /**
     * User's own permissions.
     */
    public function permissions(): BelongsToMany
    {
        return $this->belongsToMany(Permission::class, 'user_permissions');
    }

    /**
     * Grant given permissions to the user.
     *
     * @param array|Permission|Collection|string $permissions
     */
    public function grantPermissions($permissions): void
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
     * Remove given permissions from the user.
     *
     * @param array|Permission|Collection|string $permissions
     */
    public function losePermissions($permissions): void
    {
        if (!is_array($permissions) && !$permissions instanceof Collection) {
            $permissions = func_get_args();
        }

        foreach ($permissions as $permission) {
            $permission = $this->resolvePermission($permission);

            $this->permissions()->detach($permission);
        }
    }

    /**
     * Check whether the user has given permission.
     */
    public function hasPermission(string $permission, ?Model $model = null, string $userForeignKey = 'user_id'): bool
    {
        // Check model's ownership
        if ($model && $model->{$userForeignKey} == $this->id) {
            return true;
        }

        // Check user's own permissions
        if ($this->permissions->pluck('code')->contains($permission)) {
            return true;
        }

        // Check user's groups permissions
        return $this->groups()->with('permissions')->get()->map->permissions->flatten()
                                                                            ->pluck('code')
                                                                            ->contains($permission);
    }

    /**
     * Make the user leader of a group.
     *
     * @param int|string|Group $group
     */
    public function makeLeaderOf($group): void
    {
        $group = $this->resolveGroup($group);

        $this->leadingGroups()->attach($group);
    }

    /**
     * Determine whether a given user is leader of the group.
     *
     * @param int|string|Group $group
     *
     * @return bool
     */
    public function isLeaderOf($group): bool
    {
        $group = $this->resolveGroup($group);

        return $this->leadingGroups()->where('group_id', is_int($group) ? $group : $group->id)->exists();
    }

    /**
     * Get user's formatted name according to his primary group tags.
     */
    public function getFormattedNameAttribute(): string
    {
        if (!$this->primary_group_id) {
            return '';
        }

        $nameField = \Config::get('membership.users.name_column');

        return $this->primaryGroup->open_tag . $this->{$nameField} . $this->primaryGroup->close_tag;
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
