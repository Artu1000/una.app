<?php

namespace App\Http\Controllers\Schedule;

use App\Http\Controllers\Controller;
use App\Models\Schedule;
use App\Repositories\Schedule\ScheduleRepositoryInterface;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;

class ScheduleController extends Controller
{

    /**
     * Create a new home controller instance.
     *
     * @return void
     */
    public function __construct(ScheduleRepositoryInterface $schedule)
    {
        $this->loadBaseJs();
        $this->repository = $schedule;
    }

    /**
     * @return $this
     */
    public function index(){
        // SEO Meta settings
        $this->seoMeta['page_title'] = 'Horaires';
        $this->seoMeta['meta_desc'] = 'Découvrez nos horaires d\'encadrement de séances d\'aviron,
        selon votre catéorie et votre type de pratique.';
        $this->seoMeta['meta_keywords'] = 'club, universite, nantes, aviron, sport, universitaire, horaires,
        séances, encadrement';

        // we get the schedules from database
        $schedules = $this->repository->all();

        // we prepare the start and end of day data
        list($hour, $minutes) = explode(':', config('schedule.day_start'));
        $start_of_day = Carbon::createFromTime($hour, $minutes, 00);
        list($hour, $minutes) = explode(':', config('schedule.day_stop'));
        $end_of_day = Carbon::createFromTime($hour, $minutes, 00);

        // we format an hour array
        $hours = [];
        $hour = $start_of_day;
        while($hour < $end_of_day) {
            $hours[] = $hour->format('H:i');
            $hour->addMinutes(30);
        }

        // we prepare empty arrays to format the columns and the schedule grid
        $columns = [];
        $formated_schedules = [];

        // for each database schedule
        foreach($schedules as $schedule) {
            $ii = 0;
            // for each hour
            foreach ($hours as $time) {
                // for each day
                foreach (config('schedule.day_of_week') as $id => $day) {

                    if($schedule->start <= $time && $schedule->stop > $time && $schedule->day_id === $id) {

                        list($hour, $minutes) = explode(':', $time);
                        $carbon_time = Carbon::createFromTime($hour, $minutes, 00);

                        if($schedule->start === $time){
                            $formated_schedules[$time][$day['title']][$schedule->public_category] = [
                                'status' => 'start',
                                'public_category_id' => $schedule->public_category
                            ];
                        } elseif($schedule->stop === $carbon_time->addMinutes(30)->format('H:i')) {
                            $formated_schedules[$time][$day['title']][$schedule->public_category] = [
                                'status' => 'stop',
                                'public_category_id' => $schedule->public_category
                            ];
                        } else {
                            $formated_schedules[$time][$day['title']][$schedule->public_category] = [
                                'status' => '',
                                'public_category_id' => $schedule->public_category
                            ];
                        }
                        $columns[$day['title']][$schedule->public_category] = [];
                        $ii++;
                    } else {
                        if(empty($formated_schedules[$time][$day['title']])){
                            $formated_schedules[$time][$day['title']] = [];
                        }
                    }
                }
            }
        }

        // prepare data for the view
        $data = [
            'seoMeta' => $this->seoMeta,
            'days' => config('schedule.day_of_week'),
            'hours' => $hours,
            'schedules' => $formated_schedules,
            'columns' => $columns,
            'css' => elixir('css/app.schedule.css')
        ];

        // return the view with data
        return view('pages.front.schedule')->with($data);
    }

}
