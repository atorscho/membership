<?php

namespace Atorscho\Membership\Permissions;

use Atorscho\Membership\Groups\Group;
use Illuminate\Database\Eloquent\Model;

class PermissionSet extends Model
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
        'id' => 'int'
    ];

    /**
     * Disable model timestamps.
     *
     * @var bool
     */
    public $timestamps = false;

    /**
     * Get all set's permissions.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function permissions()
    {
        return $this->hasMany(Permission::class, 'set_id');
    }
}
