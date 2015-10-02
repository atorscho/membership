<?php

namespace Atorscho\Membership\Groups;

use Atorscho\Membership\Permissions\Permission;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class Group extends Model
{
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
     * Role's permissions.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function permissions()
    {
        return $this->belongsToMany(Permission::class, 'group_permissions');
    }

    /**
     * Role's users.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function users()
    {
        return $this->belongsToMany(config('uservel.users.model'), 'user_groups');
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
        $this->attributes['handle'] = str_slug($handle ?: $this->name, config('membership.handle_separator'));
    }
}
