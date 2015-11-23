<?php

use Illuminate\Database\Seeder;

class UsersTableSeeder extends Seeder
{
    public function run()
    {
        // we create a user
        $user = Sentinel::register([
            'last_name' => 'LORENT',
            'first_name' => 'Arthur',
            'photo' => '',
            'email' => 'admin@admin.fr',
            'status' => config('user.status_key.communication-commission'),
            'board' => config('user.board_key.leading-board'),
            'password' => 'admin'
        ]);

        // we activate the user
        $activation = Activation::create($user);
        Activation::complete($user, $activation->code);

        // we create the admin role
        $role = \Sentinel::getRoleRepository()->createModel()->create([
            'name' => 'Admin',
            'slug' => 'admin'
        ]);

        // we give all permissions to the admin role
        $permissions = [];
        foreach(array_dot(config('permissions')) as $permission => $value){
            $permissions[$permission] = true;
        }
        $role->permissions = $permissions;
        $role->save();

        // we attach the user to the admin role
        $role->users()->attach($user);

        Sentinel::register([
            'last_name' => 'GIRARD',
            'first_name' => 'Lionel',
            'email' => 'a',
            'status' => config('user.status_key.president'),
            'board' => config('user.board_key.leading-board'),
            'password' => 'una'
        ]);

        Sentinel::register([
            'last_name' => 'VARAINE',
            'first_name' => 'David',
            'email' => 'b',
            'status' => config('user.status_key.vice-president'),
            'board' => config('user.board_key.leading-board'),
            'password' => 'una'
        ]);

        Sentinel::register([
            'last_name' => 'PLANCHENAULT',
            'first_name' => 'Thomas',
            'email' => 'c',
            'status' => config('user.status_key.student-vice-president'),
            'board' => config('user.board_key.leading-board'),
            'password' => 'una'
        ]);

        Sentinel::register([
            'last_name' => 'DISCAZEAU',
            'first_name' => 'Gérard',
            'email' => 'd',
            'status' => config('user.status_key.treasurer'),
            'board' => config('user.board_key.leading-board'),
            'password' => 'una'
        ]);

        Sentinel::register([
            'last_name' => 'PLANTIER',
            'first_name' => 'Christophe',
            'email' => 'e',
            'status' => config('user.status_key.secretary-general'),
            'board' => config('user.board_key.leading-board'),
            'password' => 'una'
        ]);

        Sentinel::register([
            'last_name' => 'PROTT',
            'first_name' => 'Thierry',
            'email' => 'f',
            'status' => config('user.status_key.sportive-commission'),
            'board' => config('user.board_key.executive-committee'),
            'password' => 'una'
        ]);

        Sentinel::register([
            'last_name' => 'ABRAHAMSON',
            'first_name' => 'Jeff',
            'email' => 'g',
            'status' => config('user.status_key.leisure-commission'),
            'board' => config('user.board_key.leading-board'),
            'password' => 'una'
        ]);

        Sentinel::register([
            'last_name' => 'LECIEUX',
            'first_name' => 'Yann',
            'email' => 'h',
            'board' => config('user.board_key.leading-board'),
            'password' => 'una'
        ]);

        Sentinel::register([
            'last_name' => 'BOUZIDI',
            'first_name' => 'Rabah',
            'email' => 'i',
            'board' => config('user.board_key.executive-committee'),
            'password' => 'una'
        ]);

        Sentinel::register([
            'last_name' => 'CORNUEL',
            'first_name' => 'Benjamin',
            'email' => 'j',
            'board' => config('user.board_key.executive-committee'),
            'password' => 'una'
        ]);

        Sentinel::register([
            'last_name' => 'MARINGER',
            'first_name' => 'Françoise',
            'email' => 'k',
            'board' => config('user.board_key.executive-committee'),
            'password' => 'una'
        ]);

        Sentinel::register([
            'last_name' => 'MOUGEL',
            'first_name' => 'Jean-Bruno',
            'email' => 'l',
            'board' => config('user.board_key.executive-committee'),
            'password' => 'una'
        ]);

        Sentinel::register([
            'last_name' => 'TARDY',
            'first_name' => 'Mickaël',
            'email' => 'm',
            'board' => config('user.board_key.executive-committee'),
            'password' => 'una'
        ]);

        Sentinel::register([
            'last_name' => 'VESPERINI',
            'first_name' => 'Laurent',
            'email' => 'n',
            'status' => config('user.status_key.employee'),
            'password' => 'una'
        ]);
    }
}
