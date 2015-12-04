<?php

namespace App\Models;

use Cartalyst\Sentinel\Roles\EloquentRole as SentinelRole;

class Role extends SentinelRole
{

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'slug',
        'rank',
        'permissions',
    ];
}
