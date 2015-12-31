<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

abstract class Controller extends BaseController
{
    use DispatchesJobs, ValidatesRequests;

    protected $repository;

    protected $seoMeta = [
        'page_title'    => '',
        'meta_desc'     => '',
        'meta_keywords' => '',
    ];

    /**
     * Controller constructor.
     */
    public function __construct()
    {
        // load base JS
        \JavaScript::put([
            'csrf_token'      => csrf_token(),
            'base_url'        => url('/'),
            'app_name'        => config('settings.app_name_' . config('app.locale')),
            'loading_spinner' => config('settings.loading_spinner'),
            'success_icon'    => config('settings.success_icon'),
            'error_icon'      => config('settings.error_icon'),
            'info_icon'       => config('settings.info_icon'),
            'locale'          => config('app.locale'),
            'multilingual'    => config('settings.multilingual'),
        ]);

        // load modal if an alert is waiting
        if (\Session::get('alert')) {
            \Javascript::put([
                'modal_alert' => true,
            ]);
        }
    }

    /**
     * @param string $permission
     * @return \Illuminate\Http\RedirectResponse
     */
    public function requirePermission($permission)
    {

        if (!\Sentinel::getUser()->hasAccess([$permission])) {
            \Modal::alert([
                trans('permissions.message.access.denied') . " : <b>" .
                trans('permissions.' . $permission) . "</b>",
            ], 'error');

            return false;
        }

        return true;
    }

    /**
     * @param array $inputs
     * @param array $rules
     * @param \Illuminate\Http\Request $request
     * @return bool
     */
    public function checkInputsValidity(array $inputs, array $rules, \Illuminate\Http\Request $request)
    {
        // we check the inputs
        $errors = [];
        $validator = \Validator::make($inputs, $rules);
        foreach ($validator->errors()->all() as $error) {
            $errors[] = $error;
        }
        // if errors are found
        if (count($errors)) {
            // we flash the request
            $request->flash();

            // we notify the current user
            \Modal::alert($errors, 'error');

            return false;
        }

        return true;
    }

    /**
     * @param $query
     * @param \Illuminate\Http\Request $request
     * @param array $columns
     * @param array $routes
     * @param array $confirm_config
     * @param array $search_config
     * @param bool|false $enable_lines_choice
     * @return mixed
     */
    public function prepareTableListData(
        $query,
        \Illuminate\Http\Request $request,
        array $columns,
        array $routes,
        array $confirm_config,
        array $search_config = [],
        $enable_lines_choice = false
    )
    {
        // we set the default data
        $default_lines = 20;
        $default_sort_by = array_first($columns, function ($key, $column) {
            return isset($column['sort_by_default']) && $column['sort_by_default'] === true;
        })['sort_by'];
        $default_sort_by = isset($default_sort_by) ? $default_sort_by : array_first($columns, function ($key, $column) {
            return isset($column['sort_by']);
        })['sort_by'];
        $default_sort_by = isset($default_sort_by) ? $default_sort_by : null;
        $default_search = '';

        // we set the nav data accordingly to the inputs
        $tableListData['lines'] = $request->get('lines', $default_lines);
        $tableListData['sort_by'] = $request->get('sort-by', $default_sort_by);
        $tableListData['search'] = $request->get('search', $default_search);

        // we check the inputs
        $validator = \Validator::make($tableListData, [
            'lines'  => 'required|numeric',
            'search' => 'alpha_dash',
        ]);
        // if errors are found
        if ($validator->fails()) {
            // we use the default values
            $tableListData['lines'] = $default_lines;
            $tableListData['sort_by'] = $default_sort_by;
            $tableListData['search'] = $default_search;
        }

        // we order the request
        $tableListData['sort_dir'] = null === $request->get('sort-dir') ? true : (
        $request->get('sort-dir') ? $request->get('sort-dir') : false
        );
        $query_sort_dir = $tableListData['sort_dir'] ? 'asc' : 'desc';
        if ($tableListData['sort_by']) {
            $query->orderBy($tableListData['sort_by'], $query_sort_dir);
        }

        // we search into the request
        $tableListData['search_config'] = $search_config;
        if ($tableListData['search'] = $request->get('search')) {
            // we search only the configured field
            foreach ($tableListData['search_config'] as $key => $searched_field) {
                if ($key > 0) {
                    $query->orWhere($searched_field, 'like', '%' . $tableListData['search'] . '%');
                } else {
                    $query->where($searched_field, 'like', '%' . $tableListData['search'] . '%');
                }
            }
        }

        // if the number of lines to show is defined
        $tableListData['enable_lines_choice'] = $enable_lines_choice;
        if ($tableListData['enable_lines_choice']) {
            // we paginate the results
            $pagination = $query->paginate($tableListData['lines']);

            // we add the lines and search inputs to the pagination url
            $pagination->appends([
                'lines'  => $tableListData['lines'],
                'search' => $tableListData['search'],
            ]);
            $tableListData['pagination'] = $pagination;

            // we generate the table nav infos
            $tableListData['nav_infos'] = $this->tableNavStatus($tableListData['pagination']);
        } else {
            $tableListData['pagination'] = $query->get();
        }

        // we put the columns into the table list data
        $tableListData['columns'] = $columns;

        // we put the route into the table list data
        $tableListData['routes'] = $routes;

        // we activate the confirm modal for the entity removal
        \Modal::confirm($confirm_config);

        return $tableListData;
    }

    /**
     * @param \Illuminate\Pagination\LengthAwarePaginator $paginated_list
     * @return string
     */
    public function tableNavStatus(\Illuminate\Pagination\LengthAwarePaginator $paginated_list)
    {
        return trans('global.table_list.results.status', [
            'start' => ($paginated_list->perPage() * ($paginated_list->currentPage() - 1)) + 1,
            'stop'  => $paginated_list->count() + (($paginated_list->currentPage() - 1) * $paginated_list->perPage()),
            'total' => $paginated_list->total(),
        ]);
    }
}
