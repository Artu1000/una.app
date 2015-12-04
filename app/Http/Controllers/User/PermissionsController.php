<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class PermissionsController extends Controller
{
    /**
     * PermissionsController constructor.
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * @param Request $request
     * @return $this|\Illuminate\Http\RedirectResponse
     */
    public function index(Request $request)
    {
        // we check the current user permission
        $required = 'permissions.list';
        if (!\Sentinel::getUser()->hasAccess([$required])) {
            \Modal::alert([
                trans('permissions.message.access.denied') . " : <b>" . trans('permissions.' . $required) . "</b>",
            ], 'error');

            return redirect()->back();
        }

        // SEO Meta settings
        $this->seoMeta['page_title'] = trans('seo.permissions.index');

        // we define the table list columns
        $columns = [[
            'title'   => trans('permissions.page.label.name'),
            'key'     => 'name',
            'sort_by' => 'roles.name',
        ], [
            'title'   => trans('permissions.page.label.slug'),
            'key'     => 'slug',
            'sort_by' => 'roles.slug',
        ], [
            'title'   => trans('permissions.page.label.rank'),
            'key'     => 'rank',
            'sort_by' => 'roles.rank',
        ], [
            'title'           => trans('permissions.page.label.created_at'),
            'key'             => 'created_at',
            'sort_by'         => 'roles.created_at',
            'sort_by_default' => true,
        ], [
            'title'   => trans('permissions.page.label.updated_at'),
            'key'     => 'updated_at',
            'sort_by' => 'roles.updated_at',
        ]];

        // we set the routes used in the table list
        $routes = [
            'index'   => 'permissions.index',
            'create'  => 'permissions.create',
            'edit'    => 'permissions.edit',
            'destroy' => 'permissions.destroy',
        ];

        // we instantiate the query
        $query = \Sentinel::getRoleRepository()->query();

        $confirm_config = [
            'action'     => trans('permissions.page.action.delete'),
            'attributes' => ['name'],
        ];

        $search_config = [
            'name',
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
            $enable_lines_choice);

        // prepare data for the view
        $data = [
            'tableListData' => $tableListData,
            'seoMeta'       => $this->seoMeta,
        ];

        // return the view with data
        return view('pages.back.permissions-list')->with($data);
    }

    public function edit($id)
    {
        // we check the current user permission
        $required = 'permissions.view';
        if (!\Sentinel::getUser()->hasAccess([$required])) {
            \Modal::alert([
                trans('permissions.message.access.denied') . " : <b>" . trans('permissions.' . $required) . "</b>",
            ], 'error');

            return redirect()->back();
        }

        // SEO Meta settings
        $this->seoMeta['page_title'] = trans('seo.permissions.edit');

        // we get the role
        $role = \Sentinel::findRoleById($id);

        // we check if the role exists
        if (!$role) {
            \Modal::alert([
                trans('permissions.message.find.failure', ['id' => $id]),
                trans('global.message.global.failure.contact.support', ['email' => config('settings.support_email')]),
            ], 'error');

            return redirect()->back();
        }

        // we prepare the data for breadcrumbs
        $breadcrumbs_data = [
            $role->name,
        ];

        // we get the role
        $parent_role = $role->rank > 1 ? \Sentinel::getRoleRepository()->where('rank', ($role->rank - 1))->firstOrFail() : null;

        // we get the role list without the current
        $role_list = \Sentinel::getRoleRepository()->where('id', '<>', $role->id)->orderBy('rank', 'asc')->get();

        // we prepare the master role status and we add at the beginning of the role list
        $master_role = new \stdClass();
        $master_role->id = 0;
        $master_role->name = trans('permissions.page.label.master');
        $role_list->prepend($master_role);

        // prepare data for the view
        $data = [
            'seoMeta'          => $this->seoMeta,
            'role'             => $role,
            'parent_role'      => $parent_role,
            'role_list'        => $role_list,
            'breadcrumbs_data' => $breadcrumbs_data,
        ];

        // return the view with data
        return view('pages.back.permission-edit')->with($data);
    }

    public function create()
    {
        // SEO Meta settings
        $this->seoMeta['page_title'] = trans('seo.permissions.create');

        // we get the role list without the current
        $role_list = \Sentinel::getRoleRepository()->orderBy('rank', 'asc')->get();

        // we prepare the master role status and we add at the beginning of the role list
        $master_role = new \stdClass();
        $master_role->id = 0;
        $master_role->name = trans('permissions.page.label.master');
        $role_list->prepend($master_role);

        // prepare data for the view
        $data = [
            'parent_role' => null,
            'seoMeta'     => $this->seoMeta,
            'role_list'   => $role_list,
        ];

        // return the view with data
        return view('pages.back.permission-edit')->with($data);
    }

    public function store(Request $request)
    {
        // we check the current user permission
        $required = 'permissions.create';
        if (!\Sentinel::getUser()->hasAccess([$required])) {
            \Modal::alert([
                trans('permissions.message.access.denied') . " : <b>" . trans('permissions.' . $required) . "</b>",
            ], 'error');

            return redirect()->back();
        }

        // we get the original request content
        $inputs = $request->all();
        // we replace the wrong keys (php forbid dots and replace them by underscores)
        foreach (array_dot(config('permissions')) as $permission => $value) {
            // we translate the permission slug to the wrong key given by php
            $wrong_key = str_replace('.', '_', $permission);
            // we translate "on" value in boolean value
            if (isset($inputs[$wrong_key])) {
                $inputs[$permission] = filter_var($inputs[$wrong_key], FILTER_VALIDATE_BOOLEAN);
                // we delete the wrong key if a dot is found (it is the case for children permissions
                if (strpos($permission, '.')) {
                    // we delete the wrong key
                    unset ($inputs[$wrong_key]);
                }
            }
        }

        // we replace the request by the cleaned one
        $request->replace($inputs);

        // we flash the request
        $request->flash();

        // we check the inputs
        $errors = [];
        $validator = \Validator::make($request->all(), [
            'name'        => 'required|unique:roles,name',
            'slug'        => 'required|alpha_dash|unique:roles,slug',
            'parent_role' => 'required|numeric',
        ]);
        foreach ($validator->errors()->all() as $error) {
            $errors[] = $error;
        }
        // if errors are found
        if (count($errors)) {
            \Modal::alert($errors, 'error');

            return redirect()->back();
        }

        // we manage the roles ranks according to the given parent
        if ($parent_role = \Sentinel::findRoleById($request->get('parent_role'))) {

            // we increment the rank of the following roles
            $roles = \Sentinel::getRoleRepository()->where('rank', '>=', $parent_role->rank)
                ->orderBy('rank', 'desc')
                ->get();

            foreach ($roles as $r) {
                $r->rank = $r->rank + 1;
                $r->save();
            }
        } else {
            // we put the role as the master one

        }

        try {
            // we create the role
            $role = \Sentinel::getRoleRepository()->createModel()->create([
                'slug'        => str_slug($request->get('slug')),
                'name'        => $request->get('name'),
                'rank'        => $parent_role->rank,
                'permissions' => $request->except('_token', 'name', 'slug', 'parent_role'),
            ]);
            \Modal::alert([
                trans('permissions.message.create.success', ['name' => $role->name]),
            ], 'success');

            return redirect(route('permissions.index'));
        } catch (\Exception $e) {
            \Log::error($e);
            \Modal::alert([
                trans('permissions.message.create.failure', ['name' => $role->name]),
                trans('global.message.global.failure.contact.support', ['email' => config('settings.support_email')]),
            ], 'error');

            return redirect()->back();
        }
    }

    public function update(Request $request)
    {
        // we check the current user permission
        $required = 'permissions.update';
        if (!\Sentinel::getUser()->hasAccess([$required])) {
            \Modal::alert([
                trans('permissions.message.access.denied') . " : <b>" . trans('permissions.' . $required) . "</b>",
            ], 'error');

            return redirect()->back();
        }

        // we get the original request content
        $inputs = $request->all();
        // we replace the wrong keys (php forbid dots and replace them by underscores)
        foreach (array_dot(config('permissions')) as $permission => $value) {
            // we translate the permission slug to the wrong key given by php
            $wrong_key = str_replace('.', '_', $permission);
            // we translate "on" value in boolean value
            if (isset($inputs[$wrong_key])) {
                $inputs[$permission] = filter_var($inputs[$wrong_key], FILTER_VALIDATE_BOOLEAN);
                // we delete the wrong key if a dot is found (it is the case for children permissions
                if (strpos($permission, '.')) {
                    // we delete the wrong key
                    unset ($inputs[$wrong_key]);
                }
            }
        }

        // we replace the request by the cleaned one
        $request->replace($inputs);

        // we check the inputs
        $errors = [];
        $validator = \Validator::make($request->all(), [
            '_id'  => 'required|exists:roles,id',
            'name' => 'required|unique:roles,name,' . $request->get('_id'),
        ]);
        foreach ($validator->errors()->all() as $error) {
            $errors[] = $error;
        }
        // if errors are found
        if (count($errors)) {
            // we flash the request
            $request->flash();

            // we notify the current user
            \Modal::alert($errors, 'error');

            return redirect()->back();
        }

        try {
            // we update the role
            $role = \Sentinel::findRoleById($request->get('_id'));
            $role->name = $request->get('name');
            $role->slug = str_slug($request->get('name'));
            $role->permissions = $request->except('_method', '_id', '_token', 'name', 'slug', 'parent_role');
            $role->save();

            // we notify the user
            \Modal::alert([
                trans('permissions.message.update.success', ['name' => $role->name]),
            ], 'success');

            return redirect()->back();
        } catch (\Exception $e) {
            // we flash the request
            $request->flash();

            // we log the error and notify the current use
            \Log::error($e);
            \Modal::alert([
                trans('permissions.message.update.failure', ['name' => $role->name]),
                trans('global.message.global.failure.contact.support', ['email' => config('settings.support_email')]),
            ], 'error');

            return redirect()->back();
        }
    }

    public function destroy(Request $request)
    {
        // we check the current user permission
        $required = 'permissions.delete';
        if (!\Sentinel::getUser()->hasAccess([$required])) {
            \Modal::alert([
                trans('permissions.message.access.denied') . " : <b>" . trans('permissions.' . $required) . "</b>",
            ], 'error');

            return redirect()->back();
        }

        // we get the role
        if (!$role = \Sentinel::findRoleById($request->get('_id'))) {
            \Modal::alert([
                trans('permissions.message.find.failure', ['name' => $request->get('_id')]),
            ], 'error');

            return redirect()->back();
        }

        // we delete the role
        try {
            \Modal::alert([
                trans('permissions.message.delete.success', ['name' => $role->name]),
            ], 'success');
            $role->delete();

            return redirect()->back();
        } catch (\Exception $e) {
            \Log::error($e);
            \Modal::alert([
                trans('permissions.message.delete.failure', ['name' => $role->name]),
                trans('global.message.global.failure.contact.support', ['email' => config('settings.support_email')]),
            ], 'error');

            return redirect()->back();
        }
    }
}
