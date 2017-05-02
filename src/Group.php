<?php

namespace Atorscho\Membership;

use Illuminate\Database\Eloquent\Model;

class Group extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['name', 'handle', 'open_tag', 'close_tag'];
}
