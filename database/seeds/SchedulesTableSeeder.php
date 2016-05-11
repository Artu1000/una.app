<?php

use App\Repositories\Schedule\ScheduleRepositoryInterface;
use Illuminate\Database\Seeder;

class SchedulesTableSeeder extends Seeder
{
    public function run()
    {
        $chedule_repo = app(ScheduleRepositoryInterface::class);

        // we remove all the files in the config folder
        $files = glob(storage_path('app/schedules/*'));
        foreach ($files as $file) {
            if (is_file($file))
                unlink($file);
        }

        // we create the folder if it doesn't exist
        if (!is_dir($storage_path = storage_path('app/schedules'))) {
            if (!is_dir($path = storage_path('app'))) {
                mkdir($path);
            }
            mkdir($path . '/schedules');
        }

        // we insert the schedules page content
        $file_name = ImageManager::optimizeAndResize(
            database_path('seeds/files/schedules/una_schedules.jpg'),
            config('image.schedules.background_image.name'),
            'jpg',
            config('image.schedules.storage_path'),
            config('image.schedules.background_image.sizes'),
            false
        );
        $inputs = [
            'title'            => 'Horaires et séances encadrées sur l\'eau',
            'description'      => "### <span class=\"text-primary\"><i class=\"fa fa-bullhorn\"></i> A noter :</span>\r\n- Il est important d'**être en tenue et prêt à ramer** lors du début d'un créneau d'encadrement, afin de profiter au maximum de l'amplitude horaire et de ne pas pénaliser ses équipiers.\r\n- La pratique en autonomie est **strictement interdite**, sauf dérogation nominative délivrée par le Comité Directeur, approuvée par l'équipe d'encadrement.\r\n- Les horaires des séances d'encadrement **évoluent en cours d'année**, merci de visiter régulièrement cette page.",
            'background_image' => $file_name,
        ];
        file_put_contents(storage_path('app/schedules/content.json'), json_encode($inputs));

        $chedule_repo->createMultiple([
            // monday
            [
                'label'           => 'Lundi - 12:00 / 13:30 - Tous publics',
                'day_id'          => config('schedule.day_of_week_key.monday'),
                'time_start'      => '12:00',
                'time_stop'       => '13:30',
                'public_category' => config('schedule.public_category_key.all_publics'),
                'active'          => true,
            ],
            [
                'label'           => 'Lundi - 12:00 / 13:30 - SUAPS (étudiants)',
                'day_id'          => config('schedule.day_of_week_key.monday'),
                'time_start'      => '12:00',
                'time_stop'       => '13:30',
                'public_category' => config('schedule.public_category_key.suaps'),
                'active'          => true,
            ],
            // tuesday
            [
                'label'           => 'Mardi - 12:00 / 13:30 - Tous publics',
                'day_id'          => config('schedule.day_of_week_key.tuesday'),
                'time_start'      => '12:00',
                'time_stop'       => '13:30',
                'public_category' => config('schedule.public_category_key.all_publics'),
                'active'          => true,
            ],
            [
                'label'           => 'Mardi - 12:30 / 13:30 - SUAPS (étudiants)',
                'day_id'          => config('schedule.day_of_week_key.tuesday'),
                'time_start'      => '12:00',
                'time_stop'       => '13:30',
                'public_category' => config('schedule.public_category_key.suaps'),
                'active'          => true,
            ],
            [
                'label'           => 'Mardi - 14:00 / 15:30 - Tous publics',
                'day_id'          => config('schedule.day_of_week_key.tuesday'),
                'time_start'      => '14:00',
                'time_stop'       => '15:30',
                'public_category' => config('schedule.public_category_key.all_publics'),
                'active'          => true,
            ],
            [
                'label'           => 'Mardi - 14:00 / 15:30 - SUAPS (étudiants)',
                'day_id'          => config('schedule.day_of_week_key.tuesday'),
                'time_start'      => '14:00',
                'time_stop'       => '15:30',
                'public_category' => config('schedule.public_category_key.suaps'),
                'active'          => true,
            ],
            [
                'label'           => 'Mardi - 18:00 / 20:00 - Tous publics',
                'day_id'          => config('schedule.day_of_week_key.tuesday'),
                'time_start'      => '18:00',
                'time_stop'       => '20:00',
                'public_category' => config('schedule.public_category_key.all_publics'),
                'active'          => true,
            ],
            [
                'label'           => 'Mardi - 18:00 / 20:00 - SUAPS (étudiants)',
                'day_id'          => config('schedule.day_of_week_key.tuesday'),
                'time_start'      => '18:00',
                'time_stop'       => '20:00',
                'public_category' => config('schedule.public_category_key.suaps'),
                'active'          => true,
            ],
            [
                'label'           => 'Mardi - 18:00 / 20:00 - École d\'Aviron (- de 18 ans)',
                'day_id'          => config('schedule.day_of_week_key.tuesday'),
                'time_start'      => '18:00',
                'time_stop'       => '20:00',
                'public_category' => config('schedule.public_category_key.rowing_school'),
                'active'          => true,
            ],
            // wednesday
            [
                'label'           => 'Mercredi - 12:30 / 13:30 - SUAPS (étudiants)',
                'day_id'          => config('schedule.day_of_week_key.wednesday'),
                'time_start'      => '12:00',
                'time_stop'       => '13:30',
                'public_category' => config('schedule.public_category_key.suaps'),
                'active'          => true,
            ],
            [
                'label'           => 'Mercredi - 14:00 / 16:30 - École d\'Aviron (- de 18 ans)',
                'day_id'          => config('schedule.day_of_week_key.wednesday'),
                'time_start'      => '14:00',
                'time_stop'       => '16:30',
                'public_category' => config('schedule.public_category_key.rowing_school'),
                'active'          => true,
            ],
            // thursday
            [
                'label'           => 'Jeudi - 12:30 / 13:30 - Tous publics',
                'day_id'          => config('schedule.day_of_week_key.thursday'),
                'time_start'      => '12:00',
                'time_stop'       => '13:30',
                'public_category' => config('schedule.public_category_key.all_publics'),
                'active'          => true,
            ],
            [
                'label'           => 'Jeudi - 12:30 / 13:30 - SUAPS (étudiants)',
                'day_id'          => config('schedule.day_of_week_key.thursday'),
                'time_start'      => '12:00',
                'time_stop'       => '13:30',
                'public_category' => config('schedule.public_category_key.suaps'),
                'active'          => true,
            ],
            [
                'label'           => 'Jeudi - 13:30 / 15:30 - SUAPS (étudiants)',
                'day_id'          => config('schedule.day_of_week_key.thursday'),
                'time_start'      => '13:30',
                'time_stop'       => '15:30',
                'public_category' => config('schedule.public_category_key.suaps'),
                'active'          => true,
            ],
            [
                'label'           => 'Jeudi - 15:30 / 17:30 - SUAPS (étudiants)',
                'day_id'          => config('schedule.day_of_week_key.thursday'),
                'time_start'      => '15:30',
                'time_stop'       => '17:30',
                'public_category' => config('schedule.public_category_key.suaps'),
                'active'          => true,
            ],
            // friday
            [
                'label'           => 'Vendredi - 12:30 / 13:30 - Tous publics',
                'day_id'          => config('schedule.day_of_week_key.friday'),
                'time_start'      => '12:00',
                'time_stop'       => '13:30',
                'public_category' => config('schedule.public_category_key.all_publics'),
                'active'          => true,
            ],
            [
                'label'           => 'Vendredi - 12:30 / 13:30 - SUAPS (étudiants)',
                'day_id'          => config('schedule.day_of_week_key.friday'),
                'time_start'      => '12:00',
                'time_stop'       => '13:30',
                'public_category' => config('schedule.public_category_key.suaps'),
                'active'          => true,
            ],
            [
                'label'           => 'Vendredi - 18:00 / 20:00 - SUAPS (étudiants)',
                'day_id'          => config('schedule.day_of_week_key.friday'),
                'time_start'      => '18:00',
                'time_stop'       => '20:00',
                'public_category' => config('schedule.public_category_key.suaps'),
                'active'          => true,
            ],
            [
                'label'           => 'Vendredi - 18:00 / 20:00 - École d\'Aviron (- de 18 ans)',
                'day_id'          => config('schedule.day_of_week_key.friday'),
                'time_start'      => '18:00',
                'time_stop'       => '20:00',
                'public_category' => config('schedule.public_category_key.rowing_school'),
                'active'          => true,
            ],
            // saturday
            [
                'label'           => 'Samedi - 08:00 / 10:00 - Compétition',
                'day_id'          => config('schedule.day_of_week_key.saturday'),
                'time_start'      => '08:00',
                'time_stop'       => '10:00',
                'public_category' => config('schedule.public_category_key.competition'),
                'active'          => true,
            ],
            [
                'label'           => 'Samedi - 09:00 / 12:00 - Tous publics',
                'day_id'          => config('schedule.day_of_week_key.saturday'),
                'time_start'      => '09:00',
                'time_stop'       => '12:00',
                'public_category' => config('schedule.public_category_key.all_publics'),
                'active'          => true,
            ],
            [
                'label'           => 'Samedi - 14:00 / 16:30 - École d\'Aviron (- de 18 ans)',
                'day_id'          => config('schedule.day_of_week_key.saturday'),
                'time_start'      => '14:00',
                'time_stop'       => '16:30',
                'public_category' => config('schedule.public_category_key.rowing_school'),
                'active'          => true,
            ],
            [
                'label'           => 'Samedi - 15:30 / 17:30 - SUAPS (étudiants)',
                'day_id'          => config('schedule.day_of_week_key.saturday'),
                'time_start'      => '15:30',
                'time_stop'       => '17:30',
                'public_category' => config('schedule.public_category_key.suaps'),
                'active'          => true,
            ],
        ]);
    }
}
