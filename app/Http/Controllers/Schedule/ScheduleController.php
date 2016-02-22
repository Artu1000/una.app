<?php

namespace App\Http\Controllers\Schedule;

use App\Http\Controllers\Controller;
use App\Repositories\Schedule\ScheduleRepositoryInterface;
use Carbon\Carbon;
use Illuminate\Http\Request;

class ScheduleController extends Controller
{

    /**
     * Create a new home controller instance.
     *
     * @return void
     */
    public function __construct(ScheduleRepositoryInterface $schedule)
    {
        parent::__construct();
        $this->repository = $schedule;
    }

    /**
     * @return $this
     */
    public function index()
    {
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
        while ($hour < $end_of_day) {
            $hours[] = $hour->format('H:i');
            $hour->addMinutes(30);
        }

        // we prepare empty arrays to format the columns and the schedule grid
        $columns = [];
        $formated_schedules = [];

        // for each database schedule
        foreach ($schedules as $schedule) {
            $ii = 0;
            // for each hour
            foreach ($hours as $time) {
                // for each day
                foreach (config('schedule.day_of_week') as $id => $day) {

                    if ($schedule->time_start <= $time && $schedule->time_stop > $time && $schedule->day_id === $id) {

                        list($hour, $minutes) = explode(':', $time);
                        $carbon_time = Carbon::createFromTime($hour, $minutes, 00);

                        if ($schedule->start === $time) {
                            $formated_schedules[$time][$day['title']][$schedule->public_category] = [
                                'status'             => 'start',
                                'public_category_id' => $schedule->public_category,
                            ];
                        } elseif ($schedule->time_stop === $carbon_time->addMinutes(30)->format('H:i')) {
                            $formated_schedules[$time][$day['title']][$schedule->public_category] = [
                                'status'             => 'stop',
                                'public_category_id' => $schedule->public_category,
                            ];
                        } else {
                            $formated_schedules[$time][$day['title']][$schedule->public_category] = [
                                'status'             => '',
                                'public_category_id' => $schedule->public_category,
                            ];
                        }
                        $columns[$day['title']][$schedule->public_category] = [];
                        $ii++;
                    } else {
                        if (empty($formated_schedules[$time][$day['title']])) {
                            $formated_schedules[$time][$day['title']] = [];
                        }
                    }
                }
            }
        }

        // prepare data for the view
        $data = [
            'seoMeta'   => $this->seoMeta,
            'days'      => config('schedule.day_of_week'),
            'hours'     => $hours,
            'schedules' => $formated_schedules,
            'columns'   => $columns,
            'css'       => url(elixir('css/app.schedule.css')),
        ];

        // return the view with data
        return view('pages.front.schedule')->with($data);
    }

    /**
     * @param Request $request
     * @return $this
     */
    public function adminList(Request $request)
    {
        // we check the current user permission
        if (!$this->requirePermission('schedules.list')) {
            return redirect()->back();
        }

        // SEO Meta settings
        $this->seoMeta['page_title'] = trans('seo.schedules.list');

        // we define the table list columns
        $columns = [
            [
                'title'   => trans('schedules.page.label.day_id'),
                'key'     => 'day_id',
                'config'  => 'schedule.day_of_week',
                'sort_by' => 'schedules.day_id',
                'button'  => true,
            ],
            [
                'title'   => trans('schedules.page.label.time_start'),
                'key'     => 'time_start',
                'sort_by' => 'schedules.time_start',
            ],
            [
                'title'   => trans('schedules.page.label.time_stop'),
                'key'     => 'time_stop',
                'sort_by' => 'schedules.time_stop',
            ],
            [
                'title'   => trans('schedules.page.label.public_category'),
                'key'     => 'public_category',
                'config'  => 'schedule.public_category',
                'sort_by' => 'schedules.public_category',
                'button'  => true,
            ],
            [
                'title'    => trans('schedules.page.label.activation'),
                'key'      => 'active',
                'activate' => 'schedules.activate',
            ],
        ];

        // we set the routes used in the table list
        $routes = [
            'index'   => 'schedules.list',
            'create'  => 'schedules.create',
            'edit'    => 'schedules.edit',
            'destroy' => 'schedules.destroy',
        ];

        // we instantiate the query
        $query = $this->repository->getModel()->query();

        // we prepare the confirm config
        $confirm_config = [
            'action'     => trans('schedules.page.action.delete'),
            'attributes' => ['label'],
        ];

        // we prepare the search config
        $search_config = [
            'label',
        ];

        // we enable the lines choice
        $enable_lines_choice = true;

        // we format the data for the needs of the view
        $tableListData = $this->prepareTableListData(
            $query,
            $request,
            $columns,
            $routes,
            $confirm_config,
            $search_config,
            $enable_lines_choice
        );

        // we get the json home content
        $schedule_data = null;
        if (is_file(storage_path('app/schedules/data.json'))) {
            $schedule_data = json_decode(file_get_contents(storage_path('app/schedules/data.json')));
        }

        // prepare data for the view
        $data = [
            'seoMeta'          => $this->seoMeta,
            'tableListData'    => $tableListData,
            'title'            => isset($schedule_data->title) ? $schedule_data->title : null,
            'description'      => isset($schedule_data->description) ? $schedule_data->description : null,
            'background_image' => isset($schedule_data->background_image) ? $schedule_data->background_image : null,
        ];

        // return the view with data
        return view('pages.back.schedules-list')->with($data);
    }

    /**
     * @return $this|\Illuminate\Http\RedirectResponse
     */
    public function create()
    {
        // we check the current user permission
        if (!$this->requirePermission('schedules.create')) {
            return redirect()->back();
        }

        // SEO Meta settings
        $this->seoMeta['page_title'] = trans('seo.schedules.create');

        // prepare data for the view
        $data = [
            'seoMeta'           => $this->seoMeta,
            'days'              => config('schedule.day_of_week'),
            'public_categories' => config('schedule.public_category'),
        ];

        // return the view with data
        return view('pages.back.schedule-edit')->with($data);
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function store(Request $request)
    {
        // we check the current user permission
        if (!$this->requirePermission('schedules.create')) {
            return redirect()->back();
        }

        // we convert the "on" value to a boolean value
        $request->merge([
            'active' => filter_var($request->get('active'), FILTER_VALIDATE_BOOLEAN),
        ]);

        // we set the label with the schedule data
        $request->merge([
            'label' => config('schedule.day_of_week.' . $request->get('day_id') . '.title') . ' - ' .
                $request->get('time_start') . ' / ' . $request->get('time_stop') . ' - ' .
                config('schedule.public_category.' . $request->get('public_category') . '.title'),
        ]);

        // we check inputs validity
        $rules = [
            'label'           => 'required|string',
            'day_id'          => 'required|in:' . implode(',', array_keys(config('schedule.day_of_week'))),
            'time_start'      => 'required|date_format:H:i|before:time_stop',
            'time_stop'       => 'required|date_format:H:i',
            'public_category' => 'required|in:' . implode(',', array_keys(config('schedule.public_category'))),
            'active'          => 'required|boolean',
        ];
        if (!$this->checkInputsValidity($request->all(), $rules, $request)) {
            return redirect()->back();
        };

        $schedule = null;

        try {
            // we create the schedule
            $schedule = $this->repository->create($request->except('_token'));

            // we notify the current user
            \Modal::alert([
                trans('schedules.message.creation.success', ['label' => $schedule->label]),
            ], 'success');

            return redirect(route('schedules.list'));
        } catch (\Exception $e) {
            // we flash the request
            $request->flash();

            // we log the error and we notify the current user
            \Log::error($e);
            \Modal::alert([
                trans('schedules.message.creation.failure', ['label' => $schedule->label]),
                trans('global.message.global.failure.contact.support', ['email' => config('settings.support_email')]),
            ], 'error');

            return redirect()->back();
        }
    }

    /**
     * @param $id
     * @return $this|\Illuminate\Http\RedirectResponse
     */
    public function edit($id)
    {
        // we check the current user permission
        if (!$this->requirePermission('schedules.view')) {
            return redirect()->back();
        }

        // we get the news
        $schedule = $this->repository->find($id);

        // we check if the news exists
        if (!$schedule) {
            \Modal::alert([
                trans('schedules.message.find.failure', ['id' => $id]),
                trans('global.message.global.failure.contact.support', ['email' => config('settings.support_email'),]),
            ], 'error');

            return redirect()->back();
        }

        // SEO Meta settings
        $this->seoMeta['page_title'] = trans('seo.schedules.edit');

        // we prepare the data for breadcrumbs
        $breadcrumbs_data = [
            $schedule->label,
        ];

        // prepare data for the view
        $data = [
            'seoMeta'           => $this->seoMeta,
            'schedule'          => $schedule,
            'days'              => config('schedule.day_of_week'),
            'public_categories' => config('schedule.public_category'),
            'breadcrumbs_data'  => $breadcrumbs_data,
        ];

        // return the view with data
        return view('pages.back.schedule-edit')->with($data);
    }

    public function update(Request $request)
    {
        // we check the current user permission
        if (!$this->requirePermission('schedules.update')) {
            return redirect()->back();
        }

        // we get the schedule
        if (!$schedule = $this->repository->find($request->get('_id'))) {
            \Modal::alert([
                trans('schedules.message.find.failure', ['id' => $request->get('_id')]),
            ], 'error');

            return redirect()->back();
        }

        // we convert the "on" value to a boolean value
        $request->merge([
            'active' => filter_var($request->get('active'), FILTER_VALIDATE_BOOLEAN),
        ]);

        // we set the label with the schedule data
        $request->merge([
            'label' => config('schedule.day_of_week.' . $request->get('day_id') . '.title') . ' - ' .
                $request->get('time_start') . ' / ' . $request->get('time_stop') . ' - ' .
                config('schedule.public_category.' . $request->get('public_category') . '.title'),
        ]);

        // we check inputs validity
        $rules = [
            'label'           => 'required|string',
            'day_id'          => 'required|in:' . implode(',', array_keys(config('schedule.day_of_week'))),
            'time_start'      => 'required|date_format:H:i|before:time_stop',
            'time_stop'       => 'required|date_format:H:i',
            'public_category' => 'required|in:' . implode(',', array_keys(config('schedule.public_category'))),
            'active'          => 'required|boolean',
        ];
        if (!$this->checkInputsValidity($request->all(), $rules, $request)) {
            return redirect()->back();
        };

        try {
            // we update the schedule
            $schedule->update($request->except('_id', '_token'));

            // we notify the current user
            \Modal::alert([
                trans('schedules.message.update.success', ['label' => $schedule->label]),
            ], 'success');

            return redirect(route('schedules.list'));
        } catch (\Exception $e) {
            // we flash the request
            $request->flash();

            // we log the error and we notify the current user
            \Log::error($e);
            \Modal::alert([
                trans('schedules.message.update.failure', ['label' => $schedule->label]),
                trans('global.message.global.failure.contact.support', ['email' => config('settings.support_email')]),
            ], 'error');

            return redirect()->back();
        }
    }

    public function dataUpdate(Request $request)
    {
        dd('data update');
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(Request $request)
    {
        // we check the current user permission
        if (!$this->requirePermission('schedules.delete')) {
            return redirect()->back();
        }

        // we get the partner
        if (!$schedule = $this->repository->find($request->get('_id'))) {
            \Modal::alert([
                trans('schedules.message.find.failure'),
            ], 'error');

            return redirect()->back();
        }

        try {
            // we delete the role
            $schedule->delete();

            \Modal::alert([
                trans('schedules.message.delete.success', ['label' => $schedule->label]),
            ], 'success');

            return redirect()->back();
        } catch (\Exception $e) {
            \Log::error($e);
            \Modal::alert([
                trans('schedules.message.delete.failure', ['label' => $schedule->label]),
                trans('global.message.global.failure.contact.support', ['email' => config('settings.support_email')]),
            ], 'error');

            return redirect()->back();
        }
    }

    /**
     * @param Request $request
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Symfony\Component\HttpFoundation\Response
     */
    public function activate(Request $request)
    {
        // we check the current user permission
        $permission = 'schedules.update';
        if (!\Sentinel::getUser()->hasAccess([$permission])) {
            return response([
                trans('permissions.message.access.denied') . " : <b>" .
                trans('permissions.' . $permission) . "</b>",
            ], 400);
        }

        // we get the model
        if (!$schedule = $this->repository->find($request->get('id'))) {
            return response([
                trans('schedules.message.find.failure', ['id' => $request->get('id')]),
            ], 401);
        }

        // we convert the "on" value to the activation order to a boolean value
        $request->merge([
            'activation_order' => filter_var($request->get('activation_order'), FILTER_VALIDATE_BOOLEAN),
        ]);

        // we check the inputs validity
        $errors = [];
        $validator = \Validator::make($request->all(), [
            'id'               => 'required|exists:schedules,id',
            'activation_order' => 'required|boolean',
        ]);
        foreach ($validator->errors()->all() as $error) {
            $errors[] = $error;
        }
        // if errors are found
        if (count($errors)) {
            return response([
                $errors,
            ], 400);
        }

        try {
            $schedule->active = $request->activation_order;
            $schedule->save();

            return response([
                trans('schedules.message.activation.success', ['label' => $schedule->label]),
            ], 200);
        } catch (\Exception $e) {
            \Log::error($e);

            return response([
                trans('schedules.message.activation.failure', ['label' => $schedule->label]),
            ], 401);
        }
    }

}
