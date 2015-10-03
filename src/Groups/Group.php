<?php

namespace Atorscho\Membership\Groups;

use Atorscho\Membership\Permissions\ManagePermissions;
use Atorscho\Membership\Permissions\Permission;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

// Todo - Get formatted name.

class Group extends Model
{
    use ManagePermissions;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['name', 'handle', 'description', 'prefix', 'suffix'];

    /**
     * Cast attributes to relevant types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'int'
    ];

    /**
     * Disable model timestamps.
     *
     * @var bool
     */
    public $timestamps = false;

    /**
     * Group's permissions.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function permissions()
    {
        return $this->belongsToMany(Permission::class, 'group_permissions');
    }

    /**
     * Group's users.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function users()
    {
        return $this->belongsToMany(config('membership.users.model'), 'user_groups');
    }

    /**
     * Return only specific group members.
     *
     * @param Builder $query
     * @param string  $group
     *
     * @return mixed
     */
    public function scopeOnly(Builder $query, $group)
    {
        return $query->whereHandle($group)->first()->users;
    }

    /**
     * Ensure the handle attribute is always in a correct format.
     *
     * @param string $handle
     */
    public function setHandleAttribute($handle)
    {
        $this->attributes['handle'] = str_slug($handle ?: $this->name, config('membership.groups.handle_separator'));
    }

    // TODO - Move to a trait (?)

}
