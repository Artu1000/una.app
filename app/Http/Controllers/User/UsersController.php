<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class UsersController extends Controller
{

    /**
     * Create a new home controller instance.
     *
     * @return void
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
        $required = 'users.list';
        if (!\Sentinel::getUser()->hasAccess([$required])) {
            \Modal::alert([
                "Vous n'avez pas l'autorisation d'effectuer l'action : <b>" . trans('permissions.' . $required) . "</b>"
            ], 'error');
            return Redirect()->back();
        }

        // SEO Meta settings
        $this->seoMeta['page_title'] = 'Gestion des utilisateurs';

        // we define the table list columns
        $columns = [[
            'title' => trans('users.last_name'),
            'key' => 'last_name',
            'sort_by' => 'users.last_name'
        ],[
            'title' => trans('users.first_name'),
            'key' => 'first_name',
            'sort_by' => 'users.first_name'
        ],[
            'title' => trans('users.board'),
            'key' => 'board',
            'config' => 'user.board',
            'sort_by' => 'users.first_name',
            'button' => true
        ],[
            'title' => trans('users.role'),
            'key' => 'roles',
            'collection' => 'name',
            'sort_by' => 'users.roles',
            'button' => [
                'attribute' => 'slug'
            ]
        ]];

        // we instantiate the query
        $query = \Sentinel::getUserRepository()->query();

        // we execute the table joins
//        $query->with('roles');
//        $query->select('users.*');
//        $query->addSelect('roles.name');
//        $query->leftJoin('role_users', 'role_users.id', '=', 'role_users.user_id');
//        $query->leftJoin('roles', 'roles.id', '=', 'role_users.role_id');

        // select `roles`.*, `role_users`.`user_id` as `pivot_user_id`, `role_users`.`role_id` as `pivot_role_id`,
        // `role_users`.`created_at` as `pivot_created_at`, `role_users`.`updated_at` as `pivot_updated_at` from `
        //roles` inner join `role_users` on `roles`.`id` = `role_users`.`role_id`
        // where `role_users`.`user_id` in (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)

        // we format the data for the needs of the view
        $tableListData = $this->prepareTableListData($query, $request, $columns, 'users', [
            'action' => 'Supression de l\'utilisateur',
            'attribute' => 'name',
        ]);

        // prepare data for the view
        $data = [
            'tableListData' => $tableListData,
            'seoMeta' => $this->seoMeta,
        ];

        // return the view with data
        return view('pages.back.users-list')->with($data);
    }

    public function show($id)
    {
        // we check the current user permission
        $required = 'permissions.view';
        if (!\Sentinel::getUser()->hasAccess([$required])) {
            \Modal::alert([
                "Vous n'avez pas l'autorisation d'effectuer l'action : <b>" . trans('permissions.' . $required) . "</b>"
            ], 'error');
            return Redirect()->back();
        }

        // SEO Meta settings
        $this->seoMeta['page_title'] = "Edition d'un rôle";

        // we get the role
        $role = \Sentinel::findRoleById($id);

        // we check if the role exists
        if (!$role) {
            \Modal::alert([
                "Le rôle que vous avez sélectionné n'existe pas." .
                "Veuillez contacter le support si l'erreur persiste :" . "<a href='mailto:" .
                config('settings.support_email') . "' >" . config('settings.support_email') . "</a>."
            ], 'error');
            return Redirect()->back();
        }

        // prepare data for the view
        $data = [
            'role' => $role,
            'seoMeta' => $this->seoMeta
        ];

        // return the view with data
        return view('pages.back.permission-edit')->with($data);
    }

    public function create()
    {
        // SEO Meta settings
        $this->seoMeta['page_title'] = "Création d'un rôle";

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
                "Vous n'avez pas l'autorisation d'effectuer l'action : <b>" . trans('permissions.' . $required) . "</b>"
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
            'name' => 'required|unique:roles,name'
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
                'slug' => str_slug($request->get('name')),
                'name' => $request->get('name'),
                'permissions' => $request->except('_token', 'name')
            ]);
            \Modal::alert([
                'Le rôle <b>' . $role->name . '</b> a bien été créé.'
            ], 'success');
            return Redirect(route('permissions'));
        } catch (\Exception $e) {
            \Log::error($e);
            \Modal::alert([
                "Une erreur est survenue lors de la création du rôle <b>" . $request->get('name') . "</b>.",
                "Veuillez contacter le support :" . "<a href='mailto:" . config('settings.support_email') . "' >" .
                config('settings.support_email') . "</a>."
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
                "Vous n'avez pas l'autorisation d'effectuer l'action : <b>" . trans('permissions.' . $required) . "</b>"
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

        // we flash the request
        $request->flash();

        // we check the inputs
        $errors = [];
        $validator = \Validator::make($request->all(), [
            '_id' => 'required|exists:roles,id',
            'name' => 'required|unique:roles,name,' . $request->get('_id')
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
            // we update the role
            $role = \Sentinel::findRoleById($request->get('_id'));
            $role->name = $request->get('name');
            $role->slug = str_slug($request->get('name'));
            $role->permissions = $request->except('_method', '_id', '_token', 'name');
            $role->save();

            \Modal::alert([
                'Le rôle <b>' . $role->name . '</b> a bien été mis à jour.'
            ], 'success');
            return Redirect()->back();
        } catch (\Exception $e) {
            \Log::error($e);
            \Modal::alert([
                "Une erreur est survenue lors de la mise à jour du rôle <b>" . $request->get('name') . "</b>.",
                "Veuillez contacter le support :" . "<a href='mailto:" . config('settings.support_email') . "' >" .
                config('settings.support_email') . "</a>."
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
                "Vous n'avez pas l'autorisation d'effectuer l'action : <b>" . trans('permissions.' . $required) . "</b>"
            ], 'error');
            return Redirect()->back();
        }

        // we get the role
        if (!$role = \Sentinel::findRoleById($request->get('_id'))) {
            \Modal::alert([
                "Le rôle séléctionné n'existe pas."
            ], 'error');
            return Redirect()->back();
        }

        // we delete the role
        try {
            \Modal::alert([
                "Le rôle <b>" . $role->name . "</b> a bien été supprimé."
            ], 'success');
            $role->delete();
            return Redirect()->back();
        } catch (\Exception $e) {
            \Log::error($e);
            \Modal::alert([
                "Une erreur est survenue lors de la mise à jour du rôle <b>" . $request->get('name') . "</b>.",
                "Veuillez contacter le support :" . "<a href='mailto:" . config('settings.support_email') . "' >" .
                config('settings.support_email') . "</a>."
            ], 'error');
            return Redirect()->back();
        }
    }
}
