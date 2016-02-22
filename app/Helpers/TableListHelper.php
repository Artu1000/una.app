<?php

namespace App\Helpers;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Modal;
use Validator;

class TableListHelper
{
    /**
     * @param Builder $query
     * @param Request $request
     * @param array $columns
     * @param array $routes
     * @param array $confirm_config
     * @param array $search_config
     * @param bool $enable_lines_choice
     * @param int|null $lines_number
     * @return mixed
     */
    public function prepare(
        Builder $query,
        Request $request,
        array $columns,
        array $routes,
        array $confirm_config = [],
        array $search_config = [],
        bool $enable_lines_choice = false,
        int $lines_number = null
    ) {
        // we set the default lines number
        $default_lines = isset($lines_number) ? $lines_number : config('tablelist.default.lines');

        // we set the default sort by and sort dir
        $possible_directions = ['asc', 'desc'];
        $default_sort_dir = true;
        $default_sort_by = null;
        foreach($columns as $key => $column){
            if(isset($column['sort_by_default']) && in_array($column['sort_by_default'], $possible_directions)){
                if(isset($column['sort_by'])){
                    $default_sort_by = $column['sort_by'];
                }
                $default_sort_dir = $column['sort_by_default'] === 'asc' ? true : false;
                break;
            }
        }
        // if there is no default sort in the given config,
        // we set the first column with a sort by attribute as the default sort
        $default_sort_by = isset($default_sort_by) ? $default_sort_by : array_first($columns, function ($key, $column) {
            return isset($column['sort_by']);
        })['sort_by'];

        // we set the default search
        $default_search = '';

        // we set the nav data accordingly to the inputs
        $tableListData['lines'] = $request->get('lines', $default_lines);
        $tableListData['sort_by'] = $request->get('sort-by', $default_sort_by);
        $tableListData['search'] = $request->get('search', $default_search);

        // we check the inputs
        $validator = Validator::make($tableListData, [
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
        $tableListData['sort_dir'] = $request->get('sort-dir', $default_sort_dir);

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
                    $query->orWhere($searched_field['database'], 'like', '%' . $tableListData['search'] . '%');
                } else {
                    $query->where($searched_field['database'], 'like', '%' . $tableListData['search'] . '%');
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
            $tableListData['nav_infos'] = $this->tableNavStatus($pagination);
        } else {
            $tableListData['pagination'] = $query->get();
        }

        // we put the columns into the table list data
        $tableListData['columns'] = $columns;

        // we put the route into the table list data
        $tableListData['routes'] = $routes;

        // we activate the confirm modal for the entity removal
        if (isset($routes['destroy']) && !empty($routes['destroy']) && !empty($confirm_config)) {
            Modal::confirm($confirm_config);
        }

        return $tableListData;
    }

    /**
     * @param LengthAwarePaginator $paginated_list
     * @return string
     */
    public function tableNavStatus(LengthAwarePaginator $paginated_list)
    {
        return trans('global.table_list.results.status', [
            'start' => ($paginated_list->perPage() * ($paginated_list->currentPage() - 1)) + 1,
            'stop'  => $paginated_list->count() + (($paginated_list->currentPage() - 1) * $paginated_list->perPage()),
            'total' => $paginated_list->total(),
        ]);
    }
}