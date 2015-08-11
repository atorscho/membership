<?php

namespace Atorscho\Uservel\Groups;

use Atorscho\Uservel\Permissions\Permission;
use Atorscho\Uservel\Traits\HandleAttribute;
use Illuminate\Database\Eloquent\Model;

/**
 * Atorscho\Uservel\Groups\Group
 *
 * @property-read \Illuminate\Database\Eloquent\Collection|Permission[] $permissions 
 * @property-read \Illuminate\Database\Eloquent\Collection|\config('uservel.users.model[] $users 
 * @property-write mixed $handle 
 */
class Group extends Model
{
    use HandleAttribute;

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
}
