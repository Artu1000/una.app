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
            'email' => 'arthur.lorent@gmail.com',
            'status' => config('user.status_key.communication-commission'),
            'board' => config('user.board_key.leading-board'),
            'password' => 'unanantes1.0',
            'permissions' => [
                'admin' => true
            ]
        ]);

        Sentinel::register([
            'last_name' => 'GIRARD',
            'first_name' => 'Lionel',
            'photo' => '',
            'email' => 'lionel.girard@univ-nantes.fr',
            'status' => config('user.status_key.president'),
            'board' => config('user.board_key.leading-board'),
            'password' => 'una'
        ]);

        Sentinel::register([
            'last_name' => 'VARAINE',
            'first_name' => 'David',
            'photo' => '',
            'email' => 'davidvaraine@gmail.com',
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
            'email' => 'discazeaux.gerardetdany@neuf.fr',
            'status' => config('user.status_key.treasurer'),
            'board' => config('user.board_key.leading-board'),
            'password' => 'una'
        ]);

        Sentinel::register([
            'last_name' => 'PLANTIER',
            'first_name' => 'Christophe',
            'photo' => '',
            'email' => 'christophe.pl@orange.fr',
            'status' => config('user.status_key.secretary-general'),
            'board' => config('user.board_key.leading-board'),
            'password' => 'una'
        ]);

        Sentinel::register([
            'last_name' => 'PROTT',
            'first_name' => 'Thierry',
            'photo' => '',
            'email' => 'thprott@free.fr',
            'status' => config('user.status_key.sportive-commission'),
            'board' => config('user.board_key.executive-committee'),
            'password' => 'una'
        ]);

        Sentinel::register([
            'last_name' => 'ABRAHAMSON',
            'first_name' => 'Jeff',
            'photo' => '',
            'email' => 'Jeff@purple.com',
            'status' => config('user.status_key.leisure-commission'),
            'board' => config('user.board_key.leading-board'),
            'password' => 'una'
        ]);

        Sentinel::register([
            'last_name' => 'LECIEUX',
            'first_name' => 'Yann',
            'photo' => '',
            'email' => 'yann.lecieux@univ-nantes.fr',
            'board' => config('user.board_key.leading-board'),
            'password' => 'una'
        ]);

        Sentinel::register([
            'last_name' => 'BOUZIDI',
            'first_name' => 'Rabah',
            'photo' => '',
            'email' => 'rabah.bouzidi@univ-nantes.fr',
            'board' => config('user.board_key.executive-committee'),
            'password' => 'una'
        ]);

        Sentinel::register([
            'last_name' => 'CORNUEL',
            'first_name' => 'Benjamin',
            'photo' => '',
            'email' => 'benjamin.cornuel@gmail.com',
            'board' => config('user.board_key.executive-committee'),
            'password' => 'una'
        ]);

        Sentinel::register([
            'last_name' => 'MARINGER',
            'first_name' => 'Françoise',
            'photo' => '',
            'email' => 'fmaringer@orange.fr',
            'board' => config('user.board_key.executive-committee'),
            'password' => 'una'
        ]);

        Sentinel::register([
            'last_name' => 'MOUGEL',
            'first_name' => 'Jean-Bruno',
            'photo' => '',
            'email' => 'jean-bruno.mougel@cnrs-imn.fr',
            'board' => config('user.board_key.executive-committee'),
            'password' => 'una'
        ]);

        Sentinel::register([
            'last_name' => 'TARDY',
            'first_name' => 'Mickaël',
            'photo' => '',
            'email' => 'mishgunn@gmail.com',
            'board' => config('user.board_key.executive-committee'),
            'password' => 'una'
        ]);

        Sentinel::register([
            'last_name' => 'VESPERINI',
            'first_name' => 'Laurent',
            'photo' => '',
            'email' => 'laurentvesperini@yahoo.fr',
            'status' => config('user.status_key.employee'),
            'password' => 'una'
        ]);
    }
}
