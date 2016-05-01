<?php

use Illuminate\Database\Seeder;

class UsersTableSeeder extends Seeder
{
    public function run()
    {
        // we remove all the files in the storage user folder
        $files = glob(storage_path('app/users/*'));
        foreach ($files as $file) {
            if (is_file($file))
                unlink($file);
        }

        // we create the folder if it doesn't exist
        if (!is_dir($storage_path = storage_path('app/users'))) {
            if (!is_dir($path = storage_path('app'))) {
                mkdir($path);
            }
            mkdir($path . '/users');
        }

        /**
         * LEADING BOARD
         */

        $moderator_role = Sentinel::findRoleBySlug('moderator');

        // we create a user
        $user = Sentinel::register([
            'last_name'  => 'GIRARD',
            'first_name' => 'Lionel',
            'email'      => 'dlsba.girard@free.fr',
            'status_id'  => config('user.status_key.president'),
            'board_id'   => config('user.board_key.leading_board'),
            'password'   => str_random(),
        ], true);
        // we attach the user to the user role
        $moderator_role->users()->attach($user);
        // we set the una logo as the user image
        $file_name = ImageManager::optimizeAndResize(
            database_path('seeds/files/users/girard.jpg'),
            $user->imageName('photo'),
            'jpg',
            $user->storagePath(),
            $user->availableSizes('photo'),
            false
        );
        $user->photo = $file_name;
        $user->save();

        // we create a user
        $user = Sentinel::register([
            'last_name'  => 'VARAINE',
            'first_name' => 'David',
            'email'      => 'davidvaraine@gmail.com',
            'status_id'  => config('user.status_key.vice_president'),
            'board_id'   => config('user.board_key.leading_board'),
            'password'   => str_random(),
        ], true);
        // we attach the user to the user role
        $moderator_role->users()->attach($user);
        // we set the una logo as the user image
        $file_name = ImageManager::optimizeAndResize(
            database_path('seeds/files/users/varaine.jpg'),
            $user->imageName('photo'),
            'jpg',
            $user->storagePath(),
            $user->availableSizes('photo'),
            false
        );
        $user->photo = $file_name;
        $user->save();

        // we create a user
        $user = Sentinel::register([
            'last_name'  => 'DISCAZEAU',
            'first_name' => 'Gérard',
            'email'      => 'discazeaux.una@gmail.com',
            'status_id'  => config('user.status_key.treasurer'),
            'board_id'   => config('user.board_key.leading_board'),
            'password'   => str_random(),
        ], true);
        $moderator_role->users()->attach($user);
        // we set the una logo as the user image
        $file_name = ImageManager::optimizeAndResize(
            database_path('seeds/files/users/users-default-avatar.png'),
            $user->imageName('photo'),
            'png',
            $user->storagePath(),
            $user->availableSizes('photo'),
            false
        );
        $user->photo = $file_name;
        $user->save();

        // we create a user
        $user = Sentinel::register([
            'last_name'  => 'THERIOT',
            'first_name' => 'David',
            'email'      => 'david.theriot@free.fr',
            'status_id'  => config('user.status_key.secretary_general'),
            'board_id'   => config('user.board_key.leading_board'),
            'password'   => str_random(),
        ], true);
        $moderator_role->users()->attach($user);
        // we set the una logo as the user image
        $file_name = ImageManager::optimizeAndResize(
            database_path('seeds/files/users/theriot.png'),
            $user->imageName('photo'),
            'png',
            $user->storagePath(),
            $user->availableSizes('photo'),
            false
        );
        $user->photo = $file_name;
        $user->save();

        /**
         * STUDENT LEADING BOARD
         */

        // we create a user
        $user = Sentinel::register([
            'last_name'  => 'PLANCHENAULT',
            'first_name' => 'Thomas',
            'email'      => 't.planchenault@gmail.com',
            'status_id'  => config('user.status_key.student_president'),
            'board_id'   => config('user.board_key.student_leading_board'),
            'password'   => str_random(),
        ], true);
        $moderator_role->users()->attach($user);
        // we set the una logo as the user image
        $file_name = ImageManager::optimizeAndResize(
            database_path('seeds/files/users/planchenault.jpg'),
            $user->imageName('photo'),
            'jpg',
            $user->storagePath(),
            $user->availableSizes('photo'),
            false
        );
        $user->photo = $file_name;
        $user->save();

        // we create a user
        $user = Sentinel::register([
            'last_name'  => 'DIETER',
            'first_name' => 'Lara',
            'email'      => 'laradieter@hotmail.de',
            'status_id'  => config('user.status_key.student_secretary'),
            'board_id'   => config('user.board_key.student_leading_board'),
            'password'   => str_random(),
        ], true);
        $moderator_role->users()->attach($user);
        // we set the una logo as the user image
        $file_name = ImageManager::optimizeAndResize(
            database_path('seeds/files/users/dieter.jpg'),
            $user->imageName('photo'),
            'jpg',
            $user->storagePath(),
            $user->availableSizes('photo'),
            false
        );
        $user->photo = $file_name;
        $user->save();

        // we create a user
        $user = Sentinel::register([
            'last_name'  => 'ETIENVRE',
            'first_name' => 'Marianne',
            'email'      => 'marianne.etienvre@hotmail.fr',
            'status_id'  => config('user.status_key.student_treasurer'),
            'board_id'   => config('user.board_key.student_leading_board'),
            'password'   => str_random(),
        ], true);
        $moderator_role->users()->attach($user);
        // we set the una logo as the user image
        $file_name = ImageManager::optimizeAndResize(
            database_path('seeds/files/users/etienvre.jpg'),
            $user->imageName('photo'),
            'jpg',
            $user->storagePath(),
            $user->availableSizes('photo'),
            false
        );
        $user->photo = $file_name;
        $user->save();

        // we create a user
        $user = Sentinel::register([
            'last_name'  => 'LEGOFF',
            'first_name' => 'Benoit',
            'email'      => 'legoff.b@gmail.com',
            'status_id'  => config('user.status_key.student_vice_president'),
            'board_id'   => config('user.board_key.student_leading_board'),
            'password'   => str_random(),
        ], true);
        $moderator_role->users()->attach($user);
        // we set the una logo as the user image
        $file_name = ImageManager::optimizeAndResize(
            database_path('seeds/files/users/legoff.jpg'),
            $user->imageName('photo'),
            'jpg',
            $user->storagePath(),
            $user->availableSizes('photo'),
            false
        );
        $user->photo = $file_name;
        $user->save();

        /**
         * EXECUTIVE COMMITTEE
         */

        // we create a user
        $user = Sentinel::register([
            'last_name'  => 'LORENT',
            'first_name' => 'Arthur',
            'email'      => 'arthur.lorent@gmail.com',
            'status_id'  => config('user.status_key.communication_commission'),
            'board_id'   => config('user.board_key.executive_committee'),
            'password'   => 'password',
        ], true);
        // we attach the user to the admin role
        $admin = \Sentinel::findRoleBySlug('admin');
        $admin->users()->attach($user);
        // we set the una logo as the user image
        $file_name = ImageManager::optimizeAndResize(
            database_path('seeds/files/users/lorent.jpg'),
            $user->imageName('photo'),
            'jpg',
            $user->storagePath(),
            $user->availableSizes('photo'),
            false
        );
        $user->photo = $file_name;
        $user->save();

        // we create a user
        $user = Sentinel::register([
            'last_name'  => 'PROTT',
            'first_name' => 'Thierry',
            'email'      => 'thprott@free.fr',
            'status_id'  => config('user.status_key.sportive_commission'),
            'board_id'   => config('user.board_key.executive_committee'),
            'password'   => str_random(),
        ], true);
        $moderator_role->users()->attach($user);
        // we set the una logo as the user image
        $file_name = ImageManager::optimizeAndResize(
            database_path('seeds/files/users/prott.jpg'),
            $user->imageName('photo'),
            'jpg',
            $user->storagePath(),
            $user->availableSizes('photo'),
            false
        );
        $user->photo = $file_name;
        $user->save();

        // we create a user
        $user = Sentinel::register([
            'last_name'  => 'ROUSSEAU',
            'first_name' => 'Benjamin',
            'email'      => 'benjaminrousseau96@yahoo.fr',
            'status_id'  => config('user.status_key.sportive_commission'),
            'board_id'   => config('user.board_key.executive_committee'),
            'password'   => str_random(),
        ], true);
        $moderator_role->users()->attach($user);
        // we set the una logo as the user image
        $file_name = ImageManager::optimizeAndResize(
            database_path('seeds/files/users/rousseau.jpg'),
            $user->imageName('photo'),
            'jpg',
            $user->storagePath(),
            $user->availableSizes('photo'),
            false
        );
        $user->photo = $file_name;
        $user->save();

        // we create a user
        $user = Sentinel::register([
            'last_name'  => 'LECIEUX',
            'first_name' => 'Yann',
            'email'      => 'yann.lecieux@univ-nantes.fr',
            'board_id'   => config('user.board_key.executive_committee'),
            'password'   => str_random(),
        ], true);
        $moderator_role->users()->attach($user);
        // we set the una logo as the user image
        $file_name = ImageManager::optimizeAndResize(
            database_path('seeds/files/users/lecieux.jpg'),
            $user->imageName('photo'),
            'jpg',
            $user->storagePath(),
            $user->availableSizes('photo'),
            false
        );
        $user->photo = $file_name;
        $user->save();

        // we create a user
        $user = Sentinel::register([
            'last_name'  => 'BOUZIDI',
            'first_name' => 'Rabah',
            'email'      => 'rabah.bouzidi@univ-nantes.fr',
            'board_id'   => config('user.board_key.executive_committee'),
            'password'   => str_random(),
        ], true);
        $moderator_role->users()->attach($user);
        // we set the una logo as the user image
        $file_name = ImageManager::optimizeAndResize(
            database_path('seeds/files/users/users-default-avatar.png'),
            $user->imageName('photo'),
            'png',
            $user->storagePath(),
            $user->availableSizes('photo'),
            false
        );
        $user->photo = $file_name;
        $user->save();

        // we create a user
        $user = Sentinel::register([
            'last_name'  => 'ROBIN',
            'first_name' => 'Pauline',
            'email'      => 'pauline.robin@live.fr',
            'board_id'   => config('user.board_key.executive_committee'),
            'password'   => str_random(),
        ], true);
        $moderator_role->users()->attach($user);
        // we set the una logo as the user image
        $file_name = ImageManager::optimizeAndResize(
            database_path('seeds/files/users/robin.png'),
            $user->imageName('photo'),
            'png',
            $user->storagePath(),
            $user->availableSizes('photo'),
            false
        );
        $user->photo = $file_name;
        $user->save();

        // we create a user
        $user = Sentinel::register([
            'last_name'  => 'ROBIN DIOT',
            'first_name' => 'Ainhoa',
            'email'      => 'ainhoa.robin@gmail.com',
            'board_id'   => config('user.board_key.executive_committee'),
            'password'   => str_random(),
        ], true);
        $moderator_role->users()->attach($user);
        // we set the una logo as the user image
        $file_name = ImageManager::optimizeAndResize(
            database_path('seeds/files/users/robin-diot.jpg'),
            $user->imageName('photo'),
            'jpg',
            $user->storagePath(),
            $user->availableSizes('photo'),
            false
        );
        $user->photo = $file_name;
        $user->save();

        // we create a user
        $user = Sentinel::register([
            'last_name'  => 'VERNAY',
            'first_name' => 'Solenn',
            'email'      => 'vernaysolenn@orange.fr',
            'board_id'   => config('user.board_key.executive_committee'),
            'password'   => str_random(),
        ], true);
        $moderator_role->users()->attach($user);
        // we set the una logo as the user image
        $file_name = ImageManager::optimizeAndResize(
            database_path('seeds/files/users/vernay.jpg'),
            $user->imageName('photo'),
            'jpg',
            $user->storagePath(),
            $user->availableSizes('photo'),
            false
        );
        $user->photo = $file_name;
        $user->save();

        /**
         * EMPLOYEES
         */

        // we create a user
        $user = Sentinel::register([
            'last_name'  => 'VESPERINI',
            'first_name' => 'Laurent',
            'email'      => 'laurentvesperini@yahoo.fr',
            'status_id'  => config('user.status_key.employee'),
            'password'   => str_random(),
        ], true);
        $coach_role = Sentinel::findRoleBySlug('coach');
        $coach_role->users()->attach($user);
        // we set the una logo as the user image
        $file_name = ImageManager::optimizeAndResize(
            database_path('seeds/files/users/users-default-avatar.png'),
            $user->imageName('photo'),
            'png',
            $user->storagePath(),
            $user->availableSizes('photo'),
            false
        );
        $user->photo = $file_name;
        $user->save();
    }
}
