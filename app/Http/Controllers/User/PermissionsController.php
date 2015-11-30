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

            return Redirect()->back();
        }

        // SEO Meta settings
        $this->seoMeta['page_title'] = trans('seo.permissions.index');

        // we define the table list columns
        $columns = [[
            'title'   => trans('permissions.page.label.name'),
            'key'     => 'name',
            'sort_by' => 'roles.name',
        ], [
            'title'   => trans('permissions.page.label.created_at'),
            'key'     => 'created_at',
            'sort_by' => 'roles.created_at',
        ], [
            'title'   => trans('permissions.page.label.updated_at'),
            'key'     => 'updated_at',
            'sort_by' => 'roles.updated_at',
        ]];

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
            'permissions',
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

            return Redirect()->back();
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

            return Redirect()->back();
        }

        // prepare data for the view
        $data = [
            'role'    => $role,
            'seoMeta' => $this->seoMeta,
        ];

        // return the view with data
        return view('pages.back.permission-edit')->with($data);
    }

    public function create()
    {
        // SEO Meta settings
        $this->seoMeta['page_title'] = trans('seo.permissions.create');

        // prepare data for the view
        $data = [
            'seoMeta' => $this->seoMeta,
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

            return Redirect()->back();
        }

        // we get the original request content
        $inputs = $request->all();
        // we replace the wrong keys (php forbid dots and replace them by underscores)
        foreach (array_dot(config('permissions')) as $permission => $value) {
            // we only take care about the children permissions
            if (strpos($permission, '.')) {
                // we translate the permission slug to the wrong key given by php
                $wrong_key = str_replace('.', '_', $permission);
                // we get the value and store it into the correct key
                if (isset($inputs[$wrong_key])) {
                    $inputs[$permission] = $inputs[$wrong_key];
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
            'name' => 'required|unique:roles,name',
        ]);
        foreach ($validator->errors()->all() as $error) {
            $errors[] = $error;
        }
        // if errors are found
        if (count($errors)) {
            \Modal::alert($errors, 'error');

            return Redirect()->back();
        }

        try {
            // we create the role
            $role = \Sentinel::getRoleRepository()->createModel()->create([
                'slug'        => str_slug($request->get('name')),
                'name'        => $request->get('name'),
                'permissions' => $request->except('_token', 'name'),
            ]);
            \Modal::alert([
                trans('permissions.message.create.success', ['name' => $role->name]),
            ], 'success');

            return Redirect(route('permissions.index'));
        } catch (\Exception $e) {
            \Log::error($e);
            \Modal::alert([
                trans('permissions.message.create.failure', ['name' => $role->name]),
                trans('global.message.global.failure.contact.support', ['email' => config('settings.support_email')]),
            ], 'error');

            return Redirect()->back();
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

            return Redirect()->back();
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

            return Redirect()->back();
        }

        try {
            // we update the role
            $role = \Sentinel::findRoleById($request->get('_id'));
            $role->name = $request->get('name');
            $role->slug = str_slug($request->get('name'));
            $role->permissions = $request->except('_method', '_id', '_token', 'name');
            $role->save();

            // we notify the user
            \Modal::alert([
                trans('permissions.message.update.success', ['name' => $role->name]),
            ], 'success');

            return Redirect()->back();
        } catch (\Exception $e) {
            // we flash the request
            $request->flash();

            // we log the error and notify the current use
            \Log::error($e);
            \Modal::alert([
                trans('permissions.message.update.failure', ['name' => $role->name]),
                trans('global.message.global.failure.contact.support', ['email' => config('settings.support_email')]),
            ], 'error');

            return Redirect()->back();
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

            return Redirect()->back();
        }

        // we get the role
        if (!$role = \Sentinel::findRoleById($request->get('_id'))) {
            \Modal::alert([
                trans('permissions.message.find.failure', ['name' => $request->get('_id')]),
            ], 'error');

            return Redirect()->back();
        }

        // we delete the role
        try {
            \Modal::alert([
                trans('permissions.message.delete.success', ['name' => $role->name]),
            ], 'success');
            $role->delete();

            return Redirect()->back();
        } catch (\Exception $e) {
            \Log::error($e);
            \Modal::alert([
                trans('permissions.message.delete.failure', ['name' => $role->name]),
                trans('global.message.global.failure.contact.support', ['email' => config('settings.support_email')]),
            ], 'error');

            return Redirect()->back();
        }
    }
}
