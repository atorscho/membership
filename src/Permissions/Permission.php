<?php

namespace Atorscho\Membership\Permissions;

use Atorscho\Membership\Groups\Group;
use Illuminate\Database\Eloquent\Model;

class Permission extends Model
{
    /**
     * The attributes that are protected from mass assignment.
     *
     * @var array
     */
    protected $guarded = ['id'];

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
     * Groups with current permission.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function groups()
    {
        return $this->belongsToMany(Group::class, 'group_permissions');
    }

    /**
     * Users that have current permission.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function users()
    {
        return $this->belongsToMany(config('auth.model'), 'user_permissions');
    }

    /**
     * Ensure the handle attribute is always in a correct format.
     *
     * @param string $handle
     */
    public function setHandleAttribute($handle)
    {
        $this->attributes['handle'] = str_slug($handle ?: $this->name, config('membership.permissions.handle_separator'));
    }
}
