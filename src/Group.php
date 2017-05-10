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
    protected $fillable = ['name', 'handle', 'open_tag', 'close_tag', 'limit', 'public'];

    /**
     * Disable timestamps population.
     *
     * @var bool
     */
    public $timestamps = false;

    /**
     * Cast attributes to relevant types.
     *
     * @var array
     */
    protected $casts = [
        'limit' => 'int',
        'public'    => 'bool'
    ];

    /**
     * Get group's users.
     */
    public function users(): BelongsToMany
    {
        return $this->belongsToMany(config('auth.providers.users.model'), 'user_groups');
    }

    /**
     * Get group's leaders.
     */
    public function leaders(): BelongsToMany
    {
        return $this->belongsToMany(config('auth.providers.users.model'), 'group_leaders');
    }

    /**
     * Get group's permissions.
     */
    public function permissions(): BelongsToMany
    {
        return $this->belongsToMany(Permission::class, 'group_permissions');
    }

    /**
     * Grant given permissions to the group.
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

            $permission->assignTo($this);
        }
    }

    /**
     * Lose given permissions from the group.
     *
     * @param array|Permission|Collection $permissions
     */
    public function losePermissions($permissions): void
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
     *
     * @return bool Whether the user has been assigned.
     */
    public function assign($user): bool
    {
        if ($this->limitExceeded()) {
            return false;
        }

        $this->users()->attach($user);

        return true;
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
     * Add a leader to the group.
     *
     * @param int|Authenticatable $user
     */
    public function addLeader($user): void
    {
        $this->leaders()->attach($user);
    }

    /**
     * Determine whether a given user is leader of the group.
     *
     * @param int|Authenticatable $user
     *
     * @return bool
     */
    public function hasLeader($user): bool
    {
        return $this->leaders()->where('user_id', $user->id)->exists();
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

    /**
     * Check whether the limit has been exceeded.
     *
     * 0 disables any limit.
     */
    protected function limitExceeded(): bool
    {
        return $this->limit !== 0 && $this->users()->count() >= $this->limit;
    }
}
