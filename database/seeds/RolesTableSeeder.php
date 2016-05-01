<?php

use Illuminate\Database\Seeder;

class RolesTableSeeder extends Seeder
{
    public function run()
    {
        // we create the admin role
        $admin = Sentinel::getRoleRepository()->createModel()->create([
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
        $mod = Sentinel::getRoleRepository()->createModel()->create([
            'slug'        => 'moderator',
            'position'    => 2,
            'permissions' => [
                // users
                'users.list'                 => true,
                'users.view'                 => true,
                // home
                'home.page.view'             => true,
                'home.slides.view'           => true,
                // news
                'news.page.view'             => true,
                'news.create'                => true,
                'news.view'                  => true,
                'news.update'                => true,
                // schedules
                'schedules'                  => true,
                'schedules.page.view'        => true,
                'schedules.create'           => true,
                'schedules.view'             => true,
                'schedules.update'           => true,
                'schedules.delete'           => true,
                // registration
                'registration.page.view'     => true,
                'registration.prices.create' => true,
                'registration.prices.view'   => true,
                'registration.prices.update' => true,
                'registration.prices.delete' => true,
                // partners
                'partners.list'              => true,
                'partners.view'              => true,
            ],
        ]);
        // we translate the translatable fields
        $mod->translateOrNew('fr')->name = 'Modérateur';
        $mod->translateOrNew('en')->name = 'Moderator';
        $mod->save();


        // we create the moderator role
        $mod = Sentinel::getRoleRepository()->createModel()->create([
            'slug'        => 'coach',
            'position'    => 3,
            'permissions' => [
                // users
                'users.list'                 => true,
                'users.view'                 => true,
                // home
                'home.page.view'             => true,
                'home.slides.view'           => true,
                // news
                'news.page.view'             => true,
                'news.create'                => true,
                'news.view'                  => true,
                'news.update'                => true,
                // schedules
                'schedules'                  => true,
                'schedules.page.view'        => true,
                'schedules.create'           => true,
                'schedules.view'             => true,
                'schedules.update'           => true,
                'schedules.delete'           => true,
                // registration
                'registration.page.view'     => true,
                'registration.prices.create' => true,
                'registration.prices.view'   => true,
                'registration.prices.update' => true,
                'registration.prices.delete' => true,
                // partners
                'partners.list'              => true,
                'partners.view'              => true,
            ],
        ]);
        // we translate the translatable fields
        $mod->translateOrNew('fr')->name = 'Coach';
        $mod->translateOrNew('en')->name = 'Coach';
        $mod->save();
        
        
        // we create the user role
        $member = Sentinel::getRoleRepository()->createModel()->create([
            'slug'     => 'user',
            'position' => 4,
        ]);
        // we translate the translatable fields
        $member->translateOrNew('fr')->name = 'Utilisateur';
        $member->translateOrNew('en')->name = 'User';
        $member->save();
    }
}
