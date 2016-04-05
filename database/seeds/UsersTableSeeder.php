<?php

use Illuminate\Database\Seeder;

class UsersTableSeeder extends Seeder
{
    public function run()
    {
        // we remove all the files in the storage user folder
        $files = glob(storage_path('app/users/*'));
        foreach($files as $file){
            if(is_file($file))
                unlink($file);
        }

        // we create the folder if it doesn't exist
        if (!is_dir($storage_path = storage_path('app/users'))) {
            if (!is_dir($path = storage_path('app'))) {
                mkdir($path);
            }
            mkdir($path . '/users');
        }


        // we create a user
        $user = Sentinel::register([
            'last_name' => 'LORENT',
            'first_name' => 'Arthur',
            'photo' => '',
            'email' => 'admin@admin.fr',
            'status_id' => config('user.status_key.communication_commission'),
            'board_id' => config('user.board_key.leading_board'),
            'password' => 'admin'
        ], true);
        // we attach the user to the admin role
        $admin = \Sentinel::findRoleBySlug('admin');
        $admin->users()->attach($user);
        // we set the una logo as the user image
        $file_name = ImageManager::optimizeAndResize(
            database_path('seeds/files/settings/logo-una-dark.png'),
            $user->imageName('photo'),
            config('image.settings.logo.extension'),
            $user->storagePath(),
            $user->availableSizes('photo'),
            false
        );
        $user->photo = $file_name;
        $user->save();


        // we create a user
        $user = Sentinel::register([
            'last_name' => 'GIRARD',
            'first_name' => 'Lionel',
            'email' => 'a',
            'status_id' => config('user.status_key.president'),
            'board_id' => config('user.board_key.leading_board'),
            'password' => 'una'
        ]);
        // we attach the user to the user role
        $user_role = Sentinel::findRoleBySlug('user');
        $user_role->users()->attach($user);
        // we set the una logo as the user image
        $file_name = ImageManager::optimizeAndResize(
            database_path('seeds/files/settings/logo-una-dark.png'),
            $user->imageName('photo'),
            config('image.settings.logo.extension'),
            $user->storagePath(),
            $user->availableSizes('photo'),
            false
        );
        $user->photo = $file_name;
        $user->save();


        // we create a user
        $user = Sentinel::register([
            'last_name' => 'VARAINE',
            'first_name' => 'David',
            'email' => 'b',
            'status_id' => config('user.status_key.vice_president'),
            'board_id' => config('user.board_key.leading_board'),
            'password' => 'una'
        ]);
        // we attach the user to the user role
        $user_role->users()->attach($user);
        // we set the una logo as the user image
        $file_name = ImageManager::optimizeAndResize(
            database_path('seeds/files/settings/logo-una-dark.png'),
            $user->imageName('photo'),
            config('image.settings.logo.extension'),
            $user->storagePath(),
            $user->availableSizes('photo'),
            false
        );
        $user->photo = $file_name;
        $user->save();


        // we create a user
        $user = Sentinel::register([
            'last_name' => 'PLANCHENAULT',
            'first_name' => 'Thomas',
            'email' => 'c',
            'status_id' => config('user.status_key.student_president'),
            'board_id' => config('user.board_key.student_leading_board'),
            'password' => 'una'
        ]);
        $user_role->users()->attach($user);
        // we set the una logo as the user image
        $file_name = ImageManager::optimizeAndResize(
            database_path('seeds/files/settings/logo-una-dark.png'),
            $user->imageName('photo'),
            config('image.settings.logo.extension'),
            $user->storagePath(),
            $user->availableSizes('photo'),
            false
        );
        $user->photo = $file_name;
        $user->save();


        // we create a user
        $user = Sentinel::register([
            'last_name' => 'DIETER',
            'first_name' => 'Lara',
            'email' => 'cc',
            'status_id' => config('user.status_key.student_secretary'),
            'board_id' => config('user.board_key.student_leading_board'),
            'password' => 'una'
        ]);
        $user_role->users()->attach($user);
        // we set the una logo as the user image
        $file_name = ImageManager::optimizeAndResize(
            database_path('seeds/files/settings/logo-una-dark.png'),
            $user->imageName('photo'),
            config('image.settings.logo.extension'),
            $user->storagePath(),
            $user->availableSizes('photo'),
            false
        );
        $user->photo = $file_name;
        $user->save();


        // we create a user
        $user = Sentinel::register([
            'last_name' => 'DISCAZEAU',
            'first_name' => 'Gérard',
            'email' => 'd',
            'status_id' => config('user.status_key.treasurer'),
            'board_id' => config('user.board_key.leading_board'),
            'password' => 'una'
        ]);
        $user_role->users()->attach($user);
        // we set the una logo as the user image
        $file_name = ImageManager::optimizeAndResize(
            database_path('seeds/files/settings/logo-una-dark.png'),
            $user->imageName('photo'),
            config('image.settings.logo.extension'),
            $user->storagePath(),
            $user->availableSizes('photo'),
            false
        );
        $user->photo = $file_name;
        $user->save();


        // we create a user
        $user = Sentinel::register([
            'last_name' => 'PLANTIER',
            'first_name' => 'Christophe',
            'email' => 'e',
            'status_id' => config('user.status_key.secretary_general'),
            'board_id' => config('user.board_key.leading_board'),
            'password' => 'una'
        ]);
        $user_role->users()->attach($user);
        // we set the una logo as the user image
        $file_name = ImageManager::optimizeAndResize(
            database_path('seeds/files/settings/logo-una-dark.png'),
            $user->imageName('photo'),
            config('image.settings.logo.extension'),
            $user->storagePath(),
            $user->availableSizes('photo'),
            false
        );
        $user->photo = $file_name;
        $user->save();


        // we create a user
        $user = Sentinel::register([
            'last_name' => 'PROTT',
            'first_name' => 'Thierry',
            'email' => 'f',
            'status_id' => config('user.status_key.sportive_commission'),
            'board_id' => config('user.board_key.executive_committee'),
            'password' => 'una'
        ]);
        $user_role->users()->attach($user);
        // we set the una logo as the user image
        $file_name = ImageManager::optimizeAndResize(
            database_path('seeds/files/settings/logo-una-dark.png'),
            $user->imageName('photo'),
            config('image.settings.logo.extension'),
            $user->storagePath(),
            $user->availableSizes('photo'),
            false
        );
        $user->photo = $file_name;
        $user->save();


        // we create a user
        $user = Sentinel::register([
            'last_name' => 'ABRAHAMSON',
            'first_name' => 'Jeff',
            'email' => 'g',
            'status_id' => config('user.status_key.leisure_commission'),
            'board_id' => config('user.board_key.leading_board'),
            'password' => 'una'
        ]);
        $user_role->users()->attach($user);
        // we set the una logo as the user image
        $file_name = ImageManager::optimizeAndResize(
            database_path('seeds/files/settings/logo-una-dark.png'),
            $user->imageName('photo'),
            config('image.settings.logo.extension'),
            $user->storagePath(),
            $user->availableSizes('photo'),
            false
        );
        $user->photo = $file_name;
        $user->save();


        // we create a user
        $user = Sentinel::register([
            'last_name' => 'LECIEUX',
            'first_name' => 'Yann',
            'email' => 'h',
            'board_id' => config('user.board_key.leading_board'),
            'password' => 'una'
        ]);
        $user_role->users()->attach($user);
        // we set the una logo as the user image
        $file_name = ImageManager::optimizeAndResize(
            database_path('seeds/files/settings/logo-una-dark.png'),
            $user->imageName('photo'),
            config('image.settings.logo.extension'),
            $user->storagePath(),
            $user->availableSizes('photo'),
            false
        );
        $user->photo = $file_name;
        $user->save();


        // we create a user
        $user = Sentinel::register([
            'last_name' => 'BOUZIDI',
            'first_name' => 'Rabah',
            'email' => 'i',
            'board_id' => config('user.board_key.executive_committee'),
            'password' => 'una'
        ]);
        $user_role->users()->attach($user);
        // we set the una logo as the user image
        $file_name = ImageManager::optimizeAndResize(
            database_path('seeds/files/settings/logo-una-dark.png'),
            $user->imageName('photo'),
            config('image.settings.logo.extension'),
            $user->storagePath(),
            $user->availableSizes('photo'),
            false
        );
        $user->photo = $file_name;
        $user->save();


        // we create a user
        $user = Sentinel::register([
            'last_name' => 'CORNUEL',
            'first_name' => 'Benjamin',
            'email' => 'j',
            'board_id' => config('user.board_key.executive_committee'),
            'password' => 'una'
        ]);
        $user_role->users()->attach($user);
        // we set the una logo as the user image
        $file_name = ImageManager::optimizeAndResize(
            database_path('seeds/files/settings/logo-una-dark.png'),
            $user->imageName('photo'),
            config('image.settings.logo.extension'),
            $user->storagePath(),
            $user->availableSizes('photo'),
            false
        );
        $user->photo = $file_name;
        $user->save();


        // we create a user
        $user = Sentinel::register([
            'last_name' => 'MARINGER',
            'first_name' => 'Françoise',
            'email' => 'k',
            'board_id' => config('user.board_key.executive_committee'),
            'password' => 'una'
        ]);
        $user_role->users()->attach($user);
        // we set the una logo as the user image
        $file_name = ImageManager::optimizeAndResize(
            database_path('seeds/files/settings/logo-una-dark.png'),
            $user->imageName('photo'),
            config('image.settings.logo.extension'),
            $user->storagePath(),
            $user->availableSizes('photo'),
            false
        );
        $user->photo = $file_name;
        $user->save();


        // we create a user
        $user = Sentinel::register([
            'last_name' => 'MOUGEL',
            'first_name' => 'Jean-Bruno',
            'email' => 'l',
            'board_id' => config('user.board_key.executive_committee'),
            'password' => 'una'
        ]);
        $user_role->users()->attach($user);
        // we set the una logo as the user image
        $file_name = ImageManager::optimizeAndResize(
            database_path('seeds/files/settings/logo-una-dark.png'),
            $user->imageName('photo'),
            config('image.settings.logo.extension'),
            $user->storagePath(),
            $user->availableSizes('photo'),
            false
        );
        $user->photo = $file_name;
        $user->save();


        // we create a user
        $user = Sentinel::register([
            'last_name' => 'TARDY',
            'first_name' => 'Mickaël',
            'email' => 'm',
            'board_id' => config('user.board_key.executive_committee'),
            'password' => 'una'
        ]);
        $user_role->users()->attach($user);
        // we set the una logo as the user image
        $file_name = ImageManager::optimizeAndResize(
            database_path('seeds/files/settings/logo-una-dark.png'),
            $user->imageName('photo'),
            config('image.settings.logo.extension'),
            $user->storagePath(),
            $user->availableSizes('photo'),
            false
        );
        $user->photo = $file_name;
        $user->save();


        // we create a user
        $user = Sentinel::register([
            'last_name' => 'VESPERINI',
            'first_name' => 'Laurent',
            'email' => 'n',
            'status_id' => config('user.status_key.employee'),
            'password' => 'una'
        ]);
        $user_role->users()->attach($user);
        // we set the una logo as the user image
        $file_name = ImageManager::optimizeAndResize(
            database_path('seeds/files/settings/logo-una-dark.png'),
            $user->imageName('photo'),
            config('image.settings.logo.extension'),
            $user->storagePath(),
            $user->availableSizes('photo'),
            false
        );
        $user->photo = $file_name;
        $user->save();
    }
}
