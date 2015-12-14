<?php

use Illuminate\Database\Seeder;

class RolesTableSeeder extends Seeder
{
    public function run()
    {
        // we create the admin role
        $admin = \Sentinel::getRoleRepository()->createModel()->create([
            'slug' => 'admin',
            'rank' => 1
        ]);
        // we translate the translatable fields
        $admin->translateOrNew('fr')->name = 'Administrateur';
        $admin->translateOrNew('en')->name = 'Administrator';
        // we give all permissions to the admin role
        $permissions = [];
        foreach (array_dot(config('permissions')) as $permission => $value) {
            $permissions[$permission] = true;
        }
        $admin->permissions = $permissions;
        // we save the changes
        $admin->save();


        // we create the member role
        $mod = \Sentinel::getRoleRepository()->createModel()->create([
            'slug' => 'moderator',
            'rank' => 2
        ]);
        // we translate the translatable fields
        $mod->translateOrNew('fr')->name = 'Modérateur';
        $mod->translateOrNew('en')->name = 'Moderator';
        $mod->save();


        // we create the user role
        $user = \Sentinel::getRoleRepository()->createModel()->create([
            'slug' => 'user',
            'rank' => 3
        ]);
        // we translate the translatable fields
        $user->translateOrNew('fr')->name = 'Utilisateur';
        $user->translateOrNew('en')->name = 'User';
        $user->save();
    }
}
