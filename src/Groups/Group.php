<?php

namespace Atorscho\Uservel\Groups;

use Atorscho\Uservel\Permissions\Permission;
use Atorscho\Uservel\Permissions\PermissionAttachments;
use Atorscho\Uservel\Permissions\PermissionsAttribute;
use Atorscho\Uservel\Traits\HandleAttribute;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

// todo - formatted name

/**
 * Atorscho\Uservel\Groups\Group
 *
 * @property-read \Illuminate\Database\Eloquent\Collection|Permission[] $permissions
 * @property-read \Illuminate\Database\Eloquent\Collection|\config('uservel.users.model[] $users
 * @property-write mixed                                                $handle
 * @property int                                                        $id
 * @property string                                                     $name
 * @property string                                                     $description
 * @property string                                                     $prefix
 * @property string                                                     $suffix
 * @method static \Illuminate\Database\Query\Builder|\Atorscho\Uservel\Groups\Group whereId($value)
 * @method static \Illuminate\Database\Query\Builder|\Atorscho\Uservel\Groups\Group whereName($value)
 * @method static \Illuminate\Database\Query\Builder|\Atorscho\Uservel\Groups\Group whereHandle($value)
 * @method static \Illuminate\Database\Query\Builder|\Atorscho\Uservel\Groups\Group whereDescription($value)
 * @method static \Illuminate\Database\Query\Builder|\Atorscho\Uservel\Groups\Group wherePrefix($value)
 * @method static \Illuminate\Database\Query\Builder|\Atorscho\Uservel\Groups\Group whereSuffix($value)
 */
class Group extends Model
{
    use HandleAttribute, PermissionAttachments, PermissionsAttribute;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['name', 'handle', 'permissions'];

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
        return $this->belongsToMany(config('uservel.users.model'), 'user_groups');
    }

    /**
     * Return only specific group members.
     *
     * @param Builder $query
     * @param string        $group
     *
     * @return mixed
     */
    public function scopeOnly(Builder $query, $group)
    {
        return $query->whereHandle($group)->first()->users;
    }
}
