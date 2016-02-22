<?php

use Illuminate\Database\Seeder;

class RolesTableSeeder extends Seeder
{
    public function run()
    {
        // we create the admin role
        $admin = \Sentinel::getRoleRepository()->createModel()->create([
            'slug'     => 'admin',
            'position' => 1,
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


        // we create the moderator role
        $mod = \Sentinel::getRoleRepository()->createModel()->create([
            'slug'     => 'moderator',
            'position' => 2,
        ]);
        // we translate the translatable fields
        $mod->translateOrNew('fr')->name = 'Modérateur';
        $mod->translateOrNew('en')->name = 'Moderator';
        $mod->save();


        // we create the user role
        $member = \Sentinel::getRoleRepository()->createModel()->create([
            'slug'     => 'user',
            'position' => 3,
        ]);
        // we translate the translatable fields
        $member->translateOrNew('fr')->name = 'Utilisateur';
        $member->translateOrNew('en')->name = 'User';
        $member->save();
    }
}
