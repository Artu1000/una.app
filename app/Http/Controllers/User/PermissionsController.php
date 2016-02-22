<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Repositories\Roles\RoleRepositoryInterface;
use Illuminate\Http\Request;
use Permission;
use Sentinel;
use TableList;
use Validation;

class PermissionsController extends Controller
{
    /**
     * PermissionsController constructor.
     * @param RoleRepositoryInterface $role
     */
    public function __construct(RoleRepositoryInterface $role)
    {
        parent::__construct();

        // we add js variables
        \JavaScript::put([
            'permissions_separator' => config('permissions.separator'),
        ]);

        // we set the controller repository
        $this->repository = $role;
    }

    /**
     * @param Request $request
     * @return $this|\Illuminate\Http\RedirectResponse
     */
    public function index(Request $request)
    {
        // we check the current user permission
        if (!Permission::hasPermission('permissions.list')) {
            return redirect()->route('dashboard.index');
        }

        // SEO Meta settings
        $this->seoMeta['page_title'] = trans('seo.permissions.index');

        // we define the table list columns
        $columns = [[
            'title'   => trans('permissions.page.label.name'),
            'key'     => 'name',
            'sort_by' => 'role_translations.name',
        ], [
            'title'   => trans('permissions.page.label.slug'),
            'key'     => 'slug',
            'sort_by' => 'roles.slug',
        ], [
            'title'           => trans('permissions.page.label.position'),
            'key'             => 'position',
            'sort_by'         => 'roles.position',
            'sort_by_default' => 'asc',
        ], [
            'title'   => trans('permissions.page.label.created_at'),
            'key'     => 'created_at',
            'date'    => 'd/m/Y H:i:s',
            'sort_by' => 'roles.created_at',
        ], [
            'title'   => trans('permissions.page.label.updated_at'),
            'key'     => 'updated_at',
            'date'    => 'd/m/Y H:i:s',
            'sort_by' => 'roles.updated_at',
        ]];

        // we set the routes used in the table list
        $routes = [
            'index'   => [
                'route'  => 'permissions.index',
                'params' => [],
            ],
            'create'  => [
                'route'  => 'permissions.create',
                'params' => [],
            ],
            'edit'    => [
                'route'  => 'permissions.edit',
                'params' => [],
            ],
            'destroy' => [
                'route'  => 'permissions.destroy',
                'params' => [],
            ],
        ];

        // we instantiate the query
        $query = \Sentinel::getRoleRepository()->query();

        // we group the results
        $query->groupBy('roles.id');

        // we select the data we want
        $query->select('roles.*');

        // we execute the table joins
        $query->leftJoin('role_translations', 'role_translations.role_id', '=', 'roles.id');

        $confirm_config = [
            'action'     => trans('permissions.page.action.delete'),
            'attributes' => ['name'],
        ];

        // we prepare the search config
        $search_config = [
            [
                'key'      => trans('permissions.page.label.name'),
                'database' => 'role_translations.name',
            ],
            [
                'key'      => trans('permissions.page.label.slug'),
                'database' => 'roles.slug',
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

        // prepare data for the view
        $data = [
            'tableListData' => $tableListData,
            'seoMeta'       => $this->seoMeta,
        ];

        // return the view with data
        return view('pages.back.permissions-list')->with($data);
    }

    /**
     * @return $this
     */
    public function create()
    {
        // we check the current user permission
        if (!Permission::hasPermission('permissions.create')) {
            // we redirect the current user to the permissions list if he has the required permission
            if (Sentinel::getUser()->hasAccess('permissions.list')) {
                return redirect()->route('permissions.index');
            } else {
                // or we redirect the current user to the home page
                return redirect()->route('dashboard.index');
            }
        }

        // SEO Meta settings
        $this->seoMeta['page_title'] = trans('seo.permissions.create');

        // we get the role list without the current
        $role_list = \Sentinel::getRoleRepository()->orderBy('position', 'asc')->get();

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

    /**
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        // we check the current user permission
        if (!Permission::hasPermission('permissions.create')) {
            // we redirect the current user to the permissions list if he has the required permission
            if (Sentinel::getUser()->hasAccess('permissions.list')) {
                return redirect()->route('permissions.index');
            } else {
                // or we redirect the current user to the home page
                return redirect()->route('dashboard.index');
            }
        }

        // we sanitize the entries
        $request->replace(\Entry::sanitizeAll($request->all()));

        // we replace the permission slugs from the request params by the correct slugs
        $inputs = $this->repository->translatePermissionsSlugs($request->all());

        // we replace the request by the cleaned one
        $request->replace($inputs);

        // we set the rules according to the multilingual config
        $rules = [
            'name_fr' => 'required|string',
            'slug'    => 'required|alpha_dash|unique:roles,slug',
        ];
        if (config('settings.multilingual')) {
            $rules['name_en'] = 'required|string';
        }
        // we set the rules according to the parent role
        if ($request->get('parent_role_id') === 0) {
            $rules['parent_role_id'] = 'numeric';
        } else {
            $rules['parent_role_id'] = 'required|numeric|exists:roles,id';
        }
        // we sort the rules by keys
        ksort($rules);
        // we check the inputs validity
        if (!Validation::check($request->all(), $rules)) {
            // we flash the request
            $request->flash();

            return redirect()->back();
        }

        try {
            // we update the roles hierarchy
            $new_position = $this->repository->updatePositions($request->get('parent_role_id'));

            // we set the data for the role creation
            $data = [
                'slug'        => str_slug($request->get('slug')),
                'position'    => $new_position,
                'permissions' => $request->except('_token', 'parent_role_id', 'name_fr', 'name_en', 'slug', 'parent_role'),
            ];
            if (config('settings.multilingual')) {
                $data['fr'] = ['name' => $request->get('name_fr')];
                $data['en'] = ['name' => $request->get('name_en')];
            } else {
                $data['name'] = $request->get('name_fr');
            }

            // we create the role
            $role = \Sentinel::getRoleRepository()->createModel()->create($data);

            // we sanitize the roles positions
            $this->repository->sanitizePositions();

            \Modal::alert([
                trans('permissions.message.creation.success', ['name' => $role->name]),
            ], 'success');

            return redirect()->route('permissions.index');
        } catch (\Exception $e) {
            // we flash the request
            $request->flash();

            // we log the error
            \CustomLog::error($e);

            // we notify the user
            \Modal::alert([
                trans('permissions.message.creation.failure', ['name' => $request->get('name')]),
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
        if (!Permission::hasPermission('permissions.view')) {
            // we redirect the current user to the permissions list if he has the required permission
            if (Sentinel::getUser()->hasAccess('permissions.list')) {
                return redirect()->route('permissions.index');
            } else {
                // or we redirect the current user to the home page
                return redirect()->route('dashboard.index');
            }
        }

        // we get the role
        if (!$role = \Sentinel::findRoleById($id)) {
            \Modal::alert([
                trans('permissions.message.find.failure', ['id' => $id]),
                trans('global.message.global.failure.contact.support', ['email' => config('settings.support_email')]),
            ], 'error');

            // we redirect the current user to the permissions list if he has the required permission
            if (Sentinel::getUser()->hasAccess('permissions.list')) {
                return redirect()->route('permissions.index');
            } else {
                // or we redirect the current user to the home page
                return redirect()->route('dashboard.index');
            }
        }

        // SEO Meta settings
        $this->seoMeta['page_title'] = trans('seo.permissions.edit', ['role' => $role->name]);

        // we prepare the data for breadcrumbs
        $breadcrumbs_data = [
            'role' => $role,
        ];

        // we get the list without the current entity
        $role_list = \Sentinel::getRoleRepository()->orderBy('position', 'asc')->where('id', '<>', $role->id)->get();

        // we prepare the first entity and we add it at the beginning of the list
        $master_role = new \stdClass();
        $master_role->id = 0;
        $master_role->name = trans('permissions.page.label.master');
        $role_list->prepend($master_role);

        // if the current entity is the first one
        if ($role->position === 1) {
            // we set the parent role as null
            $parent_role = null;
        } else {
            // we get the parent role of the current role
            $parent_role = \Sentinel::getRoleRepository()->where('position', ($role->position - 1))->firstOrFail();
        }

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


    public function update($id, Request $request)
    {
        // we check the current user permission
        if (!Permission::hasPermission('permissions.update')) {
            // we redirect the current user to the permissions list if he has the required permission
            if (Sentinel::getUser()->hasAccess('permissions.view') && $id) {
                return redirect()->route('permissions.edit', ['id' => $id]);
            } else {
                // or we redirect the current user to the home page
                return redirect()->route('dashboard.index');
            }
        }

        // we get the role
        if (!$role = \Sentinel::findRoleById($id)) {
            // we flash the request
            $request->flash();

            // we notify the user
            \Modal::alert([
                trans('permissions.message.find.failure', ['id' => $id]),
                trans('global.message.global.failure.contact.support', ['email' => config('settings.support_email')]),
            ], 'error');

            // we redirect the current user to the permissions list if he has the required permission
            if (Sentinel::getUser()->hasAccess('permissions.list')) {
                return redirect()->route('permissions.index');
            } else {
                // or we redirect the current user to the home page
                return redirect()->route('dashboard.index');
            }
        }

        // we add the id to the request params
        $request->merge(['id' => $id]);

        // if we do not find a parent role id, we set it to 0
        if(!$request->get('parent_role_id')){
            $request->merge(['parent_role_id' => 0]);
        }

        // we sanitize the entries
        $request->replace(\Entry::sanitizeAll($request->all()));

        // we replace the permission slugs from the request params by the correct slugs
        $inputs = $this->repository->translatePermissionsSlugs($request->all());

        // we replace the request by the cleaned one
        $request->replace($inputs);

        // we prevent current user from removing the list / view / update permissions authorizations from the role he belongs
        $to_check = [
            'permissions.list',
            'permissions.view',
            'permissions.update',
        ];
        if ($role->id === \Sentinel::getUser()->roles->first()->id) {
            foreach ($to_check as $permission) {
                if (!array_key_exists($permission, $request->all())) {
                    // we flash the request
                    $request->flash();

                    // we notify the user
                    \Modal::alert([
                        trans('permissions.message.update.denied'),
                        '<b>' . trans('permissions.permissions.list') . '</b>.',
                        '<b>' . trans('permissions.permissions.view') . '</b>.',
                        '<b>' . trans('permissions.permissions.update') . '</b>.',
                    ], 'error');

                    return redirect()->back();
                }
            }
        }

        // we set the rules according to the multilingual config
        $rules = [
            'name_fr'        => 'required|string',
            'slug'           => 'required|alpha_dash|unique:roles,slug,' . $id,
            'parent_role_id' => 'numeric|exists:roles,id|different:id',
        ];
        if (config('settings.multilingual')) {
            $rules['name_en'] = 'required|string';
        }

        // we set the rules according to the parent role
        if ($request->get('parent_role_id') === 0) {
            $rules['parent_role_id'] = 'numeric';
        } else {
            $rules['parent_role_id'] = 'required|numeric|exists:roles,id|different:id';
        }

        // we sort the rules by keys
        ksort($rules);

        // we check the inputs validity
        if (!Validation::check($request->all(), $rules)) {
            // we flash the request
            $request->flash();

            return redirect()->back();
        }

        try {
            // we update the roles hierarchy
            $new_position = $this->repository->updatePositions($request->get('parent_role_id'));

            // we update the role
            $role = \Sentinel::findRoleById($id);
            if (config('settings.multilingual')) {
                $role->translate('fr')->name = $request->get('name_fr');
                $role->translate('en')->name = $request->get('name_en');
            } else {
                $role->name = $request->get('name_fr');
            }
            $role->slug = str_slug($request->get('slug'));
            $role->position = $new_position;
            $role->permissions = $request->except('_method', 'id', '_token', 'name_fr', 'name_en', 'slug', 'parent_role_id');
            $role->save();

            // we sanitize the roles positions
            $this->repository->sanitizePositions();

            // we notify the user
            \Modal::alert([
                trans('permissions.message.update.success', ['name' => $role->name]),
            ], 'success');

            return redirect()->back();
        } catch (\Exception $e) {
            // we flash the request
            $request->flash();

            // we log the error
            \CustomLog::error($e);

            // we notify the current user
            \Modal::alert([
                trans('permissions.message.update.failure', ['name' => $role->name]),
                trans('global.message.global.failure.contact.support', ['email' => config('settings.support_email')]),
            ], 'error');

            return redirect()->back();
        }
    }

    /**
     * @param $id
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy($id, Request $request)
    {
        // we check the current user permission
        if (!Permission::hasPermission('permissions.delete')) {
            // we redirect the current user to the permissions list if he has the required permission
            if (Sentinel::getUser()->hasAccess('permissions.list')) {
                return redirect()->route('permissions.index');
            } else {
                // or we redirect the current user to the home page
                return redirect()->route('dashboard.index');
            }
        }

        // we get the role
        if (!$role = \Sentinel::findRoleById($id)) {
            \Modal::alert([
                trans('permissions.message.find.failure', ['id' => $id]),
                trans('global.message.global.failure.contact.support', ['email' => config('settings.support_email')]),
            ], 'error');

            // we redirect the current user to the permissions list if he has the required permission
            if (Sentinel::getUser()->hasAccess('permissions.list')) {
                return redirect()->route('permissions.index');
            } else {
                // or we redirect the current user to the home page
                return redirect()->route('dashboard.index');
            }
        }

        // we check that the role is not attached to the current user to avoid redirection problems
        if ($role->id === \Sentinel::getUser()->roles->first()->id) {
            // we notify the user
            \Modal::alert([
                trans('permissions.message.delete.denied', ['name' => $role->name]),
            ], 'error');

            return redirect()->back();
        }

        // we delete the role
        try {
            // we notify the user
            \Modal::alert([
                trans('permissions.message.delete.success', ['name' => $role->name]),
            ], 'success');

            // we delete the role
            $role->delete();

            // we sanitize the roles positions
            $this->repository->sanitizePositions();

            return redirect()->back();
        } catch (\Exception $e) {
            // we log the error
            \CustomLog::error($e);

            // we notify the current user
            \Modal::alert([
                trans('permissions.message.delete.failure', ['name' => $role->name]),
                trans('global.message.global.failure.contact.support', ['email' => config('settings.support_email')]),
            ], 'error');

            return redirect()->back();
        }
    }
}
