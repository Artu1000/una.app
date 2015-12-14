<?php

namespace App\Models;

use Cartalyst\Sentinel\Roles\EloquentRole as SentinelRole;
use \Dimsav\Translatable\Translatable;

class Role extends SentinelRole
{

    use Translatable;

    public $translatedAttributes = ['name'];

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

    public function sanitizeRanks()
    {
        $roles_data = \Sentinel::getRoleRepository()
            ->selectRaw('MAX(rank) as max')
            ->selectRaw('COUNT(*) as count')
            ->first();

        // if we detect a rank gap
        if ($roles_data->max > $roles_data->count) {
            // we correct the rank of all roles
            $roles = \Sentinel::getRoleRepository()->orderBy('rank', 'asc')->get();

            $verification_rank = 0;
            foreach ($roles as $r) {
                // we update the incorrect ranks
                if ($r->rank !== $verification_rank + 1) {
                    $r->rank = $verification_rank + 1;
                    $r->save();
                }
                // we increment the verification rank
                $verification_rank++;
            }
        }
    }

    /**
     * @param int $parent_role_id
     * @return int
     */
    public function updateHierarchy($parent_role_id)
    {
        // we get the roles concerned by the rank incrementation regarding the given parent role
        if ($parent_role = \Sentinel::findRoleById($parent_role_id)) {
            // if a parent is defined
            // we get the roles hierarchically inferiors to the parent
            $roles = \Sentinel::getRoleRepository()->where('rank', '>', $parent_role->rank)
                ->orderBy('rank', 'desc')
                ->get();
        } else {
            // if the role has to be the master role
            // we get all roles
            $roles = \Sentinel::getRoleRepository()->orderBy('rank', 'desc')->get();
        }

        // we increment the rank of the selected roles
        foreach ($roles as $r) {
            $r->rank += 1;
            $r->save();
        }

        // we get the new rank to apply to the current role
        $new_rank = $parent_role ? ($parent_role->rank + 1) : 1;

        return $new_rank;
    }
}
