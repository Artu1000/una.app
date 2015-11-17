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
            'base_url' => url('/'),
            'app_name' => config('settings.app_name'),
            'loading_spinner' => config('settings.loading_spinner')
        ]);

        // load modal if an alert is waiting
        if (\Session::get('alert')) {
            \Javascript::put([
                'modal_alert' => true
            ]);
        }
    }

    public function prepareTableListData(\Illuminate\Http\Request $request, Array $columns, $route)
    {
        // we set the default data
        $default_lines = 20;
        $default_sort_by = $columns[0]['sort_by'];
        $default_search = '';

        // we set the nav data accordingly to the inputs
        $tableListData['lines'] = $request->get('lines', $default_lines);
        $tableListData['sort_by'] = $request->get('sort-by', $default_sort_by);
        $tableListData['search'] = $request->get('search', $default_search);

        // we check the inputs
        $errors = [];
        $validator = \Validator::make($tableListData, [
            'lines' => 'required|numeric',
            'search' => 'alpha_dash'
        ]);
        foreach ($validator->errors()->all() as $error) {
            $errors[] = $error;
        }

        // if errors are found
        if (count($errors)) {
            // we put the errors into the data
            $tableListData['errors'] = $errors;
            // we use the default values
            $tableListData['lines'] = $default_lines;
            $tableListData['sort_by'] = $default_sort_by;
            $tableListData['search'] = $default_search;
        }

        // we start the query
        $query = \Sentinel::getRoleRepository()->query();

        // we order the request
        $tableListData['sort_dir'] = null === $request->get('sort-dir') ? true : (
        $request->get('sort-dir') ? $request->get('sort-dir') : false
        );
        $query_sort_dir = $tableListData['sort_dir'] ? 'asc' : 'desc';
        $query->orderBy($tableListData['sort_by'], $query_sort_dir);

        // we filter the request
        if ($tableListData['search'] = $request->get('search')) {
            // we only get the searched results
            $query->where('name', 'like', '%' . $tableListData['search'] . '%');
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

        return $tableListData;
    }

    /**
     * @param \Illuminate\Pagination\LengthAwarePaginator $paginated_list
     * @return string
     */
    public function tableNavStatus(\Illuminate\Pagination\LengthAwarePaginator $paginated_list)
    {
        $infos = 'Affichage des résultats ' .
            (($paginated_list->perPage() * ($paginated_list->currentPage() - 1)) + 1) .
            ' à ' . ($paginated_list->count() + (($paginated_list->currentPage() - 1) * $paginated_list->perPage())) .
            ', sur un total de ' . $paginated_list->total();
        return $infos;
    }
}
