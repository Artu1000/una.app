<?php

use Illuminate\Database\Seeder;

class SchedulesTableSeeder extends Seeder
{
    public function run()
    {
        $chedule_repo = app(\App\Repositories\Schedule\ScheduleRepositoryInterface::class);

        $chedule_repo->createMultiple([
            // monday
            [
                'label'           => 'Lundi - 12:00 / 13:30 - Tous publics',
                'day_id'          => config('schedule.day_of_week_key.monday'),
                'time_start'      => '12:00',
                'time_stop'       => '13:30',
                'public_category' => config('schedule.public_category_key.all-publics'),
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
                'public_category' => config('schedule.public_category_key.all-publics'),
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
                'public_category' => config('schedule.public_category_key.all-publics'),
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
                'public_category' => config('schedule.public_category_key.all-publics'),
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
                'public_category' => config('schedule.public_category_key.rowing-school'),
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
                'public_category' => config('schedule.public_category_key.rowing-school'),
                'active'          => true,
            ],
            // thursday
            [
                'label'           => 'Jeudi - 12:30 / 13:30 - Tous publics',
                'day_id'          => config('schedule.day_of_week_key.thursday'),
                'time_start'      => '12:00',
                'time_stop'       => '13:30',
                'public_category' => config('schedule.public_category_key.all-publics'),
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
                'public_category' => config('schedule.public_category_key.all-publics'),
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
                'public_category' => config('schedule.public_category_key.rowing-school'),
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
                'public_category' => config('schedule.public_category_key.all-publics'),
                'active'          => true,
            ],
            [
                'label'           => 'Samedi - 14:00 / 16:30 - École d\'Aviron (- de 18 ans)',
                'day_id'          => config('schedule.day_of_week_key.saturday'),
                'time_start'      => '14:00',
                'time_stop'       => '16:30',
                'public_category' => config('schedule.public_category_key.rowing-school'),
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
