<?php

use Illuminate\Database\Seeder;

class RolesTableSeeder extends Seeder
{
    public function run()
    {
        // we create the admin role
        $admin = \Sentinel::getRoleRepository()->createModel()->create([
            'name' => 'Admin',
            'slug' => 'admin',
            'rank' => 1
        ]);

        // we give all permissions to the admin role
        $permissions = [];
        foreach (array_dot(config('permissions')) as $permission => $value) {
            $permissions[$permission] = true;
        }
        $admin->permissions = $permissions;
        $admin->save();

        // we create the member role
        \Sentinel::getRoleRepository()->createModel()->create([
            'name' => 'Modérateur',
            'slug' => 'moderator',
            'rank' => 2
        ]);

        // we create the user role
        \Sentinel::getRoleRepository()->createModel()->create([
            'name' => 'User',
            'slug' => 'user',
            'rank' => 3
        ]);
    }
}
