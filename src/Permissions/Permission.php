<?php

namespace Atorscho\Uservel\Permissions;

use Atorscho\Uservel\Groups\Group;
use Atorscho\Uservel\Groups\GroupAttachments;
use Atorscho\Uservel\Traits\HandleAttribute;
use Illuminate\Database\Eloquent\Model;

/**
 * Atorscho\Uservel\Permissions\Permission
 *
 * @property-read \Illuminate\Database\Eloquent\Collection|Group[] $groups
 * @property-read \Illuminate\Database\Eloquent\Collection|\config('membership.users.model[] $users
 * @property-write mixed                                           $handle
 * @property int                                                   $id
 * @property string                                                $name
 * @method static \Illuminate\Database\Query\Builder|\Atorscho\Uservel\Permissions\Permission whereId($value)
 * @method static \Illuminate\Database\Query\Builder|\Atorscho\Uservel\Permissions\Permission whereName($value)
 * @method static \Illuminate\Database\Query\Builder|\Atorscho\Uservel\Permissions\Permission whereHandle($value)
 */
class Permission extends Model
{
    use GroupAttachments, HandleAttribute;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['name', 'handle', 'groups'];

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
        return $this->belongsToMany(config('membership.users.model'), 'user_permissions');
    }
}
