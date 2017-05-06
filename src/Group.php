<?php

namespace Atorscho\Membership;

use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Group extends Model
{
    use Handlable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['name', 'handle', 'open_tag', 'close_tag', 'public'];

    /**
     * Disable timestamps population.
     *
     * @var bool
     */
    public $timestamps = false;

    /**
     * Group's users.
     */
    public function users(): BelongsToMany
    {
        return $this->belongsToMany(config('auth.providers.users.model'), 'user_groups');
    }

    /**
     * Group's permissions.
     */
    public function permissions(): BelongsToMany
    {
        return $this->belongsToMany(Permission::class, 'group_permissions');
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

            $permission->assignTo($this);
        }
    }

    /**
     * Detach given permissions from the group.
     *
     * @param array|Permission|Collection $permissions
     */
    public function detach($permissions): void
    {
        if (!is_array($permissions) && !$permissions instanceof Collection) {
            $permissions = func_get_args();
        }

        foreach ($permissions as $permission) {
            $permission = $this->resolvePermission($permission);

            $permission->unassignFrom($this);
        }
    }

    /**
     * Assign a user to the group.
     *
     * @param int|Authenticatable $user
     */
    public function assign($user): void
    {
        $this->users()->attach($user);
    }

    /**
     * Unassign a user from the group.
     *
     * @param int|Authenticatable $user
     */
    public function unassign($user): void
    {
        $this->users()->detach($user);
    }

    /**
     * Determine whether a given user is assigned to the group.
     *
     * @param int|Authenticatable $user
     *
     * @return bool
     */
    public function hasAssigned($user): bool
    {
        return $this->users()->where('user_id', $user->id)->exists();
    }

    /**
     * Get group's formatted name using tags.
     */
    public function getFormattedNameAttribute(): string
    {
        return $this->open_tag . $this->name . $this->close_tag;
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
