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
        'page_title' => '',
        'meta_desc' => '',
        'meta_keywords' => ''
    ];

    public function __construct()
    {
        // load base JS
        \JavaScript::put([
            'csrf_token' => csrf_token(),
            'base_url' => url('/'),
            'app_name' => config('settings.app_name'),
            'loading_spinner' => config('settings.loading_spinner'),
            'locale' => config('app.locale')
        ]);

        // load modal if an alert is waiting
        if (\Session::get('alert')) {
            \Javascript::put([
                'modal_alert' => true
            ]);
        }
    }

    public function prepareTableListData($query, \Illuminate\Http\Request $request, array $columns, $route, array $confirm_config, array $search_config = [])
    {
        // we set the default data
        $default_lines = 20;
        $default_sort_by = array_first($columns, function($key, $column){
            return isset($column['sort_by']);
        })['sort_by'];
        $default_sort_by = isset($default_sort_by) ? $default_sort_by : '';
        $default_search = '';

        // we set the nav data accordingly to the inputs
        $tableListData['lines'] = $request->get('lines', $default_lines);
        $tableListData['sort_by'] = $request->get('sort-by', $default_sort_by);
        $tableListData['search'] = $request->get('search', $default_search);

        // we check the inputs
        $validator = \Validator::make($tableListData, [
            'lines' => 'required|numeric',
            'search' => 'alpha_dash'
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
        $query->orderBy($tableListData['sort_by'], $query_sort_dir);

        // we search into the request
        $tableListData['search_config'] = $search_config;
        if ($tableListData['search'] = $request->get('search')) {
            // we search only the configured field
            foreach($tableListData['search_config'] as $key => $searched_field){
                if($key > 0) {
                    $query->orWhere($searched_field, 'like', '%' . $tableListData['search'] . '%');
                } else {
                    $query->where($searched_field, 'like', '%' . $tableListData['search'] . '%');
                }
            }
        }

        // we paginate the results
        $pagination = $query->paginate($tableListData['lines']);

        // we add the lines and search inputs to the pagination url
        $pagination->appends([
            'lines' => $tableListData['lines'],
            'search' => $tableListData['search']
        ]);
        $tableListData['pagination'] = $pagination;

        // we put the columns into the table list data
        $tableListData['columns'] = $columns;

        // we put the route into the table list data
        $tableListData['route'] = $route;

        // we generate the table nav infos
        $tableListData['nav_infos'] = $this->tableNavStatus($tableListData['pagination']);

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
            'stop' => $paginated_list->count() + (($paginated_list->currentPage() - 1) * $paginated_list->perPage()),
            'total' => $paginated_list->total()
        ]);
    }
}
