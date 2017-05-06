<?php

namespace Atorscho\Membership;

use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use InvalidArgumentException;

class Permission extends Model
{
    use Assignable, Handlable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['name', 'handle', 'type'];

    /**
     * Disable timestamps population.
     *
     * @var bool
     */
    public $timestamps = false;

    /**
     * Find a permissions by their type.
     */
    public static function searchType(string $type, array $attributes = ['*'])
    {
        return static::where('type', $type)->get($attributes);
    }

    /**
     * Find a permission using its type and handle.
     */
    public static function search(string $handle, array $attributes = ['*'])
    {
        $handle = explode('.', $handle);
        $type   = array_shift($handle);

        if (!$handle) {
            throw new InvalidArgumentException('The search argument is not properly formatted.');
        }

        $handle = $handle[0];

        return static::where(compact('type', 'handle'))->firstOrFail($attributes);
    }

    /**
     * Get all groups the permission is attached to.
     */
    public function groups(): BelongsToMany
    {
        return $this->belongsToMany(Group::class, 'group_permissions');
    }

    /**
     * Get all users the permission is attached to.
     */
    public function users(): BelongsToMany
    {
        return $this->belongsToMany(config('auth.providers.users.model'), 'user_permissions');
    }

    /**
     * Grant the permission to a user.
     *
     * @param int|Authenticatable $group
     */
    public function grantTo($user): void
    {
        $userModel = config('auth.providers.users.model');

        if (is_int($user) && !$userModel::find($user)) {
            return;
        }

        $this->users()->attach($user);
    }

    /**
     * Grant the permission to a user.
     *
     * @param int|Authenticatable $group
     */
    public function ungrantFrom($user): void
    {
        $userModel = config('auth.providers.users.model');

        if (is_int($user) && !$userModel::find($user)) {
            return;
        }

        $this->users()->detach($user);
    }

    /**
     * Check whether the permission is granted to a user.
     *
     * @param int|Authenticatable $group
     *
     * @return bool
     */
    public function isGrantedTo($user): bool
    {
        $userModel = config('auth.providers.users.model');

        if (is_int($user) && !$userModel::find($user)) {
            return false;
        }

        return $this->users()->where('user_id', is_int($user) ? $user : $user->id)->exists();
    }
}
