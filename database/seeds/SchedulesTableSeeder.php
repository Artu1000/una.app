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
                'day_id' => config('schedule.day_of_week_key.monday'),
                'start' => '12:00',
                'stop' => '13:30',
                'public_category' => config('schedule.public_category_key.all-publics'),
            ],
            [
                'day_id' => config('schedule.day_of_week_key.monday'),
                'start' => '12:00',
                'stop' => '13:30',
                'public_category' => config('schedule.public_category_key.suaps'),
            ],
            // tuesday
            [
                'day_id' => config('schedule.day_of_week_key.tuesday'),
                'start' => '12:00',
                'stop' => '13:30',
                'public_category' => config('schedule.public_category_key.all-publics'),
            ],
            [
                'day_id' => config('schedule.day_of_week_key.tuesday'),
                'start' => '12:00',
                'stop' => '13:30',
                'public_category' => config('schedule.public_category_key.suaps'),
            ],
            [
                'day_id' => config('schedule.day_of_week_key.tuesday'),
                'start' => '14:00',
                'stop' => '15:30',
                'public_category' => config('schedule.public_category_key.all-publics'),
            ],
            [
                'day_id' => config('schedule.day_of_week_key.tuesday'),
                'start' => '14:00',
                'stop' => '15:30',
                'public_category' => config('schedule.public_category_key.suaps'),
            ],
            [
                'day_id' => config('schedule.day_of_week_key.tuesday'),
                'start' => '18:00',
                'stop' => '20:00',
                'public_category' => config('schedule.public_category_key.all-publics'),
            ],
            [
                'day_id' => config('schedule.day_of_week_key.tuesday'),
                'start' => '18:00',
                'stop' => '20:00',
                'public_category' => config('schedule.public_category_key.suaps'),
            ],
            [
                'day_id' => config('schedule.day_of_week_key.tuesday'),
                'start' => '18:00',
                'stop' => '20:00',
                'public_category' => config('schedule.public_category_key.rowing-school'),
            ],
            // wednesday
            [
                'day_id' => config('schedule.day_of_week_key.wednesday'),
                'start' => '12:00',
                'stop' => '13:30',
                'public_category' => config('schedule.public_category_key.suaps'),
            ],
            [
                'day_id' => config('schedule.day_of_week_key.wednesday'),
                'start' => '14:00',
                'stop' => '16:30',
                'public_category' => config('schedule.public_category_key.rowing-school'),
            ],
            // thursday
            [
                'day_id' => config('schedule.day_of_week_key.thursday'),
                'start' => '12:00',
                'stop' => '13:30',
                'public_category' => config('schedule.public_category_key.all-publics'),
            ],
            [
                'day_id' => config('schedule.day_of_week_key.thursday'),
                'start' => '12:00',
                'stop' => '13:30',
                'public_category' => config('schedule.public_category_key.suaps'),
            ],
            [
                'day_id' => config('schedule.day_of_week_key.thursday'),
                'start' => '13:30',
                'stop' => '15:30',
                'public_category' => config('schedule.public_category_key.suaps'),
            ],
            [
                'day_id' => config('schedule.day_of_week_key.thursday'),
                'start' => '15:30',
                'stop' => '17:30',
                'public_category' => config('schedule.public_category_key.suaps'),
            ],
            // friday
            [
                'day_id' => config('schedule.day_of_week_key.friday'),
                'start' => '12:00',
                'stop' => '13:30',
                'public_category' => config('schedule.public_category_key.all-publics'),
            ],
            [
                'day_id' => config('schedule.day_of_week_key.friday'),
                'start' => '12:00',
                'stop' => '13:30',
                'public_category' => config('schedule.public_category_key.suaps'),
            ],
            [
                'day_id' => config('schedule.day_of_week_key.friday'),
                'start' => '18:00',
                'stop' => '20:00',
                'public_category' => config('schedule.public_category_key.suaps'),
            ],
            [
                'day_id' => config('schedule.day_of_week_key.friday'),
                'start' => '18:00',
                'stop' => '20:00',
                'public_category' => config('schedule.public_category_key.rowing-school'),
            ],
            // saturday
            [
                'day_id' => config('schedule.day_of_week_key.saturday'),
                'start' => '08:00',
                'stop' => '10:00',
                'public_category' => config('schedule.public_category_key.competition'),
            ],
            [
                'day_id' => config('schedule.day_of_week_key.saturday'),
                'start' => '09:00',
                'stop' => '12:00',
                'public_category' => config('schedule.public_category_key.all-publics'),
            ],
            [
                'day_id' => config('schedule.day_of_week_key.saturday'),
                'start' => '14:00',
                'stop' => '16:30',
                'public_category' => config('schedule.public_category_key.rowing-school'),
            ],
            [
                'day_id' => config('schedule.day_of_week_key.saturday'),
                'start' => '15:30',
                'stop' => '17:30',
                'public_category' => config('schedule.public_category_key.suaps'),
            ],
        ]);
    }
}
