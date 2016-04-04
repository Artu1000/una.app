<?php

namespace App\Http\Controllers\Schedule;

use App\Http\Controllers\Controller;
use App\Repositories\Schedule\ScheduleRepositoryInterface;
use Carbon\Carbon;
use CustomLog;
use Entry;
use Exception;
use Illuminate\Http\Request;
use Modal;
use Permission;
use Sentinel;
use TableList;
use Validation;

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
                            $formated_schedules[$time][$day][$schedule->public_category] = [
                                'status'             => 'start',
                                'public_category_id' => $schedule->public_category,
                            ];
                        } elseif ($schedule->time_stop === $carbon_time->addMinutes(30)->format('H:i')) {
                            $formated_schedules[$time][$day][$schedule->public_category] = [
                                'status'             => 'stop',
                                'public_category_id' => $schedule->public_category,
                            ];
                        } else {
                            $formated_schedules[$time][$day][$schedule->public_category] = [
                                'status'             => '',
                                'public_category_id' => $schedule->public_category,
                            ];
                        }
                        $columns[$day][$schedule->public_category] = [];
                        $ii++;
                    } else {
                        if (empty($formated_schedules[$time][$day])) {
                            $formated_schedules[$time][$day] = [];
                        }
                    }
                }
            }
        }

        // we get the json schedules content
        $schedules = null;
        if (is_file(storage_path('app/schedules/content.json'))) {
            $schedules = json_decode(file_get_contents(storage_path('app/schedules/content.json')));
        }

        // we parse the markdown content
        $parsedown = new \Parsedown();
        $description = isset($schedules->description) ? $parsedown->text($schedules->description) : null;

        // prepare data for the view
        $data = [
            'seoMeta'          => $this->seoMeta,
            'title'            => isset($schedules->title) ? $schedules->title : null,
            'description'      => $description,
            'background_image' => isset($schedules->background_image) ? $schedules->background_image : null,
            'days'             => config('schedule.day_of_week'),
            'hours'            => $hours,
            'schedules'        => $formated_schedules,
            'columns'          => $columns,
            'css'              => url(elixir('css/app.schedule.css')),
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
        if (!Permission::hasPermission('schedules.list')) {
            return redirect()->route('dashboard.index');
        }

        // SEO Meta settings
        $this->seoMeta['page_title'] = trans('seo.back.schedules.list');

        // we define the table list columns
        $columns = [
            [
                'title'   => trans('schedules.page.label.day_id'),
                'key'     => 'day_id',
                'config'  => 'schedule.day_of_week',
                'trans'   => 'schedules.config.day_of_week',
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
                'trans'   => 'schedules.config.category',
                'sort_by' => 'schedules.public_category',
                'button'  => true,
            ],
            [
                'title'    => trans('schedules.page.label.activation'),
                'key'      => 'active',
                'activate' => [
                    'route'  => 'schedules.activate',
                    'params' => [],
                ],
            ],
        ];

        // we set the routes used in the table list
        $routes = [
            'index'   => [
                'route'  => 'schedules.list',
                'params' => [],
            ],
            'create'  => [
                'route'  => 'schedules.create',
                'params' => [],
            ],
            'edit'    => [
                'route'  => 'schedules.edit',
                'params' => [],
            ],
            'destroy' => [
                'route'  => 'schedules.destroy',
                'params' => [],
            ],
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
            [
                'key'      => trans('schedules.page.label.search'),
                'database' => 'schedules.label',
            ],
        ];

        // we enable the lines choice
        $enable_lines_choice = true;

        // we format the data for the needs of the view
        $tableListData = TableList::prepare(
            $query,
            $request,
            $columns,
            $routes,
            $confirm_config,
            $search_config,
            $enable_lines_choice
        );

        // we get the json schedules content
        $schedules = null;
        if (is_file(storage_path('app/schedules/content.json'))) {
            $schedules = json_decode(file_get_contents(storage_path('app/schedules/content.json')));
        }

        // prepare data for the view
        $data = [
            'seoMeta'          => $this->seoMeta,
            'title'            => isset($schedules->title) ? $schedules->title : null,
            'description'      => isset($schedules->description) ? $schedules->description : null,
            'background_image' => isset($schedules->background_image) ? $schedules->background_image : null,
            'tableListData'    => $tableListData,
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
        if (!Permission::hasPermission('schedules.create')) {
            // we redirect the current user to the schedule list if he has the required permission
            if (Sentinel::getUser()->hasAccess('schedules.list')) {
                return redirect()->route('schedules.index');
            } else {
                // or we redirect the current user to the dashboard
                return redirect()->route('dashboard.index');
            }
        }

        // SEO Meta settings
        $this->seoMeta['page_title'] = trans('seo.back.schedules.create');

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
        if (!Permission::hasPermission('schedules.create')) {
            // we redirect the current user to the news list if he has the required permission
            if (Sentinel::getUser()->hasAccess('schedules.list')) {
                return redirect()->route('schedules.index');
            } else {
                // or we redirect the current user to the home page
                return redirect()->route('dashboard.index');
            }
        }

        // if the active field is not given, we set it to false
        $request->merge(['active' => $request->get('active', false)]);

        // we sanitize the entries
        $request->replace(Entry::sanitizeAll($request->all()));

        // we set the label
        $request->merge([
            'label' => trans('schedules.config.day_of_week.' . config('schedule.day_of_week.' . $request->get('day_id'))) .
                ' - ' .
                $request->get('time_start') .
                ' / ' .
                $request->get('time_stop') .
                ' - ' .
                trans('schedules.config.category.' . config('schedule.public_category.' . $request->get('public_category'))),
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
        // we check the inputs validity
        if (!Validation::check($request->all(), $rules)) {
            // we flash the request
            $request->flash();

            return redirect()->back();
        }

        $schedule = null;

        try {
            // we create the schedule
            $schedule = $this->repository->create($request->except('_token'));

            // we notify the current user
            \Modal::alert([
                trans('schedules.message.creation.success', ['schedule' => $schedule->label]),
            ], 'success');

            return redirect(route('schedules.list'));
        } catch (\Exception $e) {
            // we flash the request
            $request->flash();

            // we log the error
            CustomLog::error($e);

            // we notify the current user
            Modal::alert([
                trans('schedules.message.creation.failure', ['schedule' => $schedule->label]),
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
        if (!Permission::hasPermission('schedules.view')) {
            // we redirect the current user to the news list if he has the required permission
            if (Sentinel::getUser()->hasAccess('schedules.list')) {
                return redirect()->route('schedules.index');
            } else {
                // or we redirect the current user to the home page
                return redirect()->route('dashboard.index');
            }
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
        $this->seoMeta['page_title'] = trans('seo.back.schedules.edit');

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

    /**
     * @param $id
     * @param Request $request
     * @return mixed
     */
    public function update($id, Request $request)
    {
        // we get the schedule
        try {
            $schedule = $this->repository->find($id);
        } catch (Exception $e) {
            // we flash the request
            $request->flash();

            // we notify the current user
            Modal::alert([
                trans('schedules.message.find.failure', ['id' => $id]),
                trans('global.message.global.failure.contact.support', ['email' => config('settings.support_email')]),
            ], 'error');

            return redirect()->back();
        }

        // we check the current user permission
        if (!Permission::hasPermission('schedules.update')) {
            // we redirect the current user to the permissions list if he has the required permission
            if (Sentinel::getUser()->hasAccess('schedules.view')) {
                return redirect()->route('schedules.edit', ['id' => $id]);
            } else {
                // or we redirect the current user to the home page
                return redirect()->route('dashboard.index');
            }
        }

        // if the active field is not given, we set it to false
        $request->merge(['active' => $request->get('active', false)]);

        // we sanitize the entries
        $request->replace(Entry::sanitizeAll($request->all()));

        // we set the label
        $request->merge([
            'label' => trans('schedules.config.day_of_week.' . config('schedule.day_of_week.' . $request->get('day_id'))) .
                ' - ' .
                $request->get('time_start') .
                ' / ' .
                $request->get('time_stop') .
                ' - ' .
                trans('schedules.config.category.' . config('schedule.public_category.' . $request->get('public_category'))),
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
        // we check the inputs validity
        if (!Validation::check($request->all(), $rules)) {
            // we flash the request
            $request->flash();

            return redirect()->back();
        }

        try {
            // we update the schedule
            $schedule->update($request->except('_id', '_token'));

            // we notify the current user
            Modal::alert([
                trans('schedules.message.update.success', ['schedule' => $schedule->label]),
            ], 'success');

            return redirect(route('schedules.list'));
        } catch (Exception $e) {
            // we flash the request
            $request->flash();

            // we log the error
            CustomLog::error($e);

            // we notify the current user
            Modal::alert([
                trans('schedules.message.update.failure', ['schedule' => $schedule->label]),
                trans('global.message.global.failure.contact.support', ['email' => config('settings.support_email')]),
            ], 'error');

            return redirect()->back();
        }
    }

    /**
     * @param Request $request
     * @return mixed
     */
    public function dataUpdate(Request $request)
    {
        // we check the current user permission
        if (!Permission::hasPermission('schedules.update')) {
            // we redirect the current user to the schedules list if he has the required permission
            if (Sentinel::getUser()->hasAccess('schedules.view')) {
                return redirect()->route('schedules.edit');
            } else {
                // or we redirect the current user to the home page
                return redirect()->route('dashboard.index');
            }
        }

        // we get the json schedules content
        $schedules = null;
        if (is_file(storage_path('app/schedules/content.json'))) {
            $schedules = json_decode(file_get_contents(storage_path('app/schedules/content.json')));
        }

        // if the active field is not given, we set it to false
        $request->merge(['remove_background_image' => $request->get('remove_background_image', false)]);

        // we sanitize the entries
        $request->replace(\Entry::sanitizeAll($request->all()));

        // we check inputs validity
        $rules = [
            'title'                   => 'required|string',
            'description'             => 'required|string|min:250',
            'background_image'        => 'image|mimes:jpg,jpeg|image_size:>=2560,>=1440',
            'remove_background_image' => 'required|boolean',
        ];
        // we check the inputs validity
        if (!Validation::check($request->all(), $rules)) {
            // we flash the request
            $request->flashExcept('background_image');

            return redirect()->back();
        }

        try {
            $inputs = $request->except('_token', '_method', 'background_image');

            // we store the background image file
            if ($background_image = $request->file('background_image')) {
                // we optimize, resize and save the image
                $file_name = \ImageManager::optimizeAndResize(
                    $background_image->getRealPath(),
                    config('image.schedules.background_image.name'),
                    $background_image->getClientOriginalExtension(),
                    config('image.schedules.background_image.storage_path'),
                    config('image.schedules.background_image.sizes')
                );
                // we set the file name
                $inputs['background_image'] = $file_name;
            } elseif ($request->get('remove_background_image')) {
                // we remove the background image
                if (isset($schedules->background_image)) {
                    \ImageManager::remove(
                        $schedules->background_image,
                        config('image.schedules.storage_path'),
                        config('image.schedules.background_image.sizes')
                    );
                }
                $inputs['background_image'] = null;
            } else {
                $inputs['background_image'] = isset($schedules->background_image) ? $schedules->background_image : null;
            }

            // we store the content into a json file
            file_put_contents(
                storage_path('app/schedules/content.json'),
                json_encode($inputs)
            );

            \Modal::alert([
                trans('schedules.message.content_update.success', ['title' => $request->get('title')]),
            ], 'success');

            return redirect()->back();
        } catch (\Exception $e) {

            // we flash the request
            $request->flashExcept('background_image');

            // we log the error
            \CustomLog::error($e);

            // we notify the current user
            \Modal::alert([
                trans('schedules.message.content_update.failure', ['title' => isset($schedules->title) ? $schedules->title : null]),
                trans('global.message.global.failure.contact.support', ['email' => config('settings.support_email')]),
            ], 'error');

            return redirect()->back();
        }
    }

    /**
     * @param $id
     * @param Request $request
     * @return mixed
     */
    public function destroy($id, Request $request)
    {
        // we check the current user permission
        if (!Permission::hasPermission('schedules.delete')) {
            // we redirect the current user to the permissions list if he has the required permission
            if (Sentinel::getUser()->hasAccess('schedules.list')) {
                return redirect()->route('schedules.index');
            } else {
                // or we redirect the current user to the home page
                return redirect()->route('dashboard.index');
            }
        }

        // we get the schedule
        try {
            $schedule = $this->repository->find($id);
        } catch (Exception $e) {
            // we flash the request
            $request->flash();

            // we notify the current user
            Modal::alert([
                trans('schedules.message.find.failure', ['id' => $id]),
                trans('global.message.global.failure.contact.support', ['email' => config('settings.support_email')]),
            ], 'error');

            return redirect()->back();
        }

        try {
            // we delete the role
            $schedule->delete();

            \Modal::alert([
                trans('schedules.message.delete.success', ['schedule' => $schedule->label]),
            ], 'success');

            return redirect()->back();
        } catch (\Exception $e) {
            // we log the error
            CustomLog::error($e);

            // we notify the current user
            Modal::alert([
                trans('schedules.message.delete.failure', ['schedule' => $schedule->label]),
                trans('global.message.global.failure.contact.support', ['email' => config('settings.support_email')]),
            ], 'error');

            return redirect()->back();
        }
    }

    /**
     * @param $id
     * @param Request $request
     * @return mixed
     */
    public function activate($id, Request $request)
    {
        // we get the schedule
        try {
            $schedule = $this->repository->find($id);
        } catch (Exception $e) {
            // we flash the request
            $request->flash();

            // we notify the current user
            Modal::alert([
                trans('schedules.message.find.failure', ['id' => $id]),
                trans('global.message.global.failure.contact.support', ['email' => config('settings.support_email')]),
            ], 'error');

            return redirect()->back();
        }

        // we check the current user permission
        if ($permission_denied = Permission::hasPermissionJson('schedules.update')) {
            return response([
                'active'  => $schedule->active,
                'message' => [$permission_denied],
            ], 401);
        }

        // if the active field is not given, we set it to false
        $request->merge(['active' => $request->get('active', false)]);

        // we sanitize the entries
        $request->replace(Entry::sanitizeAll($request->all()));

        // we check the inputs validity
        $rules = [
            'active' => 'required|boolean',
        ];
        if (is_array($errors = Validation::check($request->all(), $rules, true))) {
            return response([
                'active'  => $schedule->active,
                'message' => $errors,
            ], 401);
        }

        try {
            $schedule->active = $request->get('active');
            $schedule->save();

            return response([
                'active'  => $schedule->active,
                'message' => [
                    trans('schedules.message.activation.success.label', ['action' => trans_choice('schedules.message.activation.success.action', $schedule->active), 'schedule' => $schedule->label]),
                ],
            ], 200);
        } catch (\Exception $e) {
            // we log the error
            CustomLog::error($e);

            return response([
                trans('schedules.message.activation.failure', ['schedule' => $schedule->label]),
            ], 401);
        }
    }

}
