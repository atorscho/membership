<?php

namespace Atorscho\Membership\Permissions;

use Atorscho\Membership\Groups\Group;
use Illuminate\Database\Eloquent\Model;

class Permission extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['name', 'handle'];

    /**
     * Cast attributes to relevant types.
     *
     * @var array
     */
    protected $casts = [
        'id'     => 'int',
        'set_id' => 'int'
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
        return $this->belongsToMany(config('uservel.users.model'), 'user_permissions');
    }

    /**
     * Permission's set.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function set()
    {
        return $this->belongsTo(PermissionSet::class, 'set_id');
    }
}
