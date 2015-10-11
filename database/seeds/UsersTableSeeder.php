<?php

use Illuminate\Database\Seeder;

class UsersTableSeeder extends Seeder
{
    public function run()
    {

        Sentinel::register([
            'last_name' => 'LORENT',
            'first_name' => 'Arthur',
            'photo' => '',
            'email' => 'admin@admin.fr',
            'status' => config('user.status_key.communication-commission'),
            'board' => config('user.board_key.leading-board'),
            'password' => 'admin',
            'permissions' => [
                'admin' => true
            ]
        ]);

        Sentinel::register([
            'last_name' => 'GIRARD',
            'first_name' => 'Lionel',
            'photo' => '',
            'email' => '',
            'status' => config('user.status_key.president'),
            'board' => config('user.board_key.leading-board'),
            'password' => 'una'
        ]);

        Sentinel::register([
            'last_name' => 'VARAINE',
            'first_name' => 'David',
            'photo' => '',
            'email' => '',
            'status' => config('user.status_key.vice-president'),
            'board' => config('user.board_key.leading-board'),
            'password' => 'una'
        ]);

        Sentinel::register([
            'last_name' => 'PLANCHENAULT',
            'first_name' => 'Thomas',
            'photo' => '',
            'email' => '',
            'status' => config('user.status_key.student-vice-president'),
            'board' => config('user.board_key.leading-board'),
            'password' => 'una'
        ]);

        Sentinel::register([
            'last_name' => 'DISCAZEAU',
            'first_name' => 'Gérard',
            'photo' => '',
            'email' => '',
            'status' => config('user.status_key.treasurer'),
            'board' => config('user.board_key.leading-board'),
            'password' => 'una'
        ]);

        Sentinel::register([
            'last_name' => 'PLANTIER',
            'first_name' => 'Christophe',
            'photo' => '',
            'email' => '',
            'status' => config('user.status_key.secretary-general'),
            'board' => config('user.board_key.leading-board'),
            'password' => 'una'
        ]);

        Sentinel::register([
            'last_name' => 'PROTT',
            'first_name' => 'Thierry',
            'photo' => '',
            'email' => '',
            'status' => config('user.status_key.sportive-commission'),
            'board' => config('user.board_key.executive-committee'),
            'password' => 'una'
        ]);

        Sentinel::register([
            'last_name' => 'ABRAHAMSON',
            'first_name' => 'Jeff',
            'photo' => '',
            'email' => '',
            'status' => config('user.status_key.leisure-commission'),
            'board' => config('user.board_key.leading-board'),
            'password' => 'una'
        ]);

        Sentinel::register([
            'last_name' => 'LECIEUX',
            'first_name' => 'Yann',
            'photo' => '',
            'email' => '',
            'board' => config('user.board_key.leading-board'),
            'password' => 'una'
        ]);

        Sentinel::register([
            'last_name' => 'BOUZIDI',
            'first_name' => 'Rabah',
            'photo' => '',
            'email' => '',
            'board' => config('user.board_key.executive-committee'),
            'password' => 'una'
        ]);

        Sentinel::register([
            'last_name' => 'CORNUEL',
            'first_name' => 'Benjamin',
            'photo' => '',
            'email' => '',
            'board' => config('user.board_key.executive-committee'),
            'password' => 'una'
        ]);

        Sentinel::register([
            'last_name' => 'MARINGER',
            'first_name' => 'Françoise',
            'photo' => '',
            'email' => '',
            'board' => config('user.board_key.executive-committee'),
            'password' => 'una'
        ]);

        Sentinel::register([
            'last_name' => 'MOUGEL',
            'first_name' => 'Jean-Bruno',
            'photo' => '',
            'email' => '',
            'board' => config('user.board_key.executive-committee'),
            'password' => 'una'
        ]);

        Sentinel::register([
            'last_name' => 'TARDY',
            'first_name' => 'Mickaël',
            'photo' => '',
            'email' => '',
            'board' => config('user.board_key.executive-committee'),
            'password' => 'una'
        ]);

        Sentinel::register([
            'last_name' => 'VESPERINI',
            'first_name' => 'Laurent',
            'photo' => '',
            'email' => '',
            'status' => config('user.status_key.employee'),
            'password' => 'una'
        ]);
    }
}
