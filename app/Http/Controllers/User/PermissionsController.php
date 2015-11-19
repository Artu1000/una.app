<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class PermissionsController extends Controller
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
     * @return $this
     */
    public function index(Request $request)
    {
        // SEO Meta settings
        $this->seoMeta['page_title'] = 'Gestion des permissions';

        // we define the table list columns
        $columns = [[
            'title' => 'Nom',
            'slug' => 'name',
            'sort_by' => 'roles.name'
        ], [
            'title' => 'Date création',
            'slug' => 'created_at',
            'sort_by' => 'roles.created_at'
        ], [
            'title' => 'Date modification',
            'slug' => 'updated_at',
            'sort_by' => 'roles.updated_at'
        ]];
        // we format the data for the needs of the view
        $tableListData = $this->prepareTableListData($request, $columns, 'permissions');

        // we activate the modal confirm for the page
        \Modal::confirm([
            'Êtes-vous certains de vouloir supprimer le rôle <b>[name]</b> ?'
        ]);

        // prepare data for the view
        $data = [
            'tableListData' => $tableListData,
            'seoMeta' => $this->seoMeta,
        ];

        // return the view with data
        return view('pages.back.permissions-list')->with($data);
    }

    public function show($id)
    {
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
            $role = \Sentinel::findById($request->get('_id'));
            $role->name = $request->get('name');
            $role->slug = str_slug($request->get('name'));
            $role->permissions = $request->except('_token', 'name');
            \Modal::alert([
                'Le rôle <b>' . $role->name . '</b> a bien été mis à jour.'
            ], 'success');
            return Redirect(route('permissions'));
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

    public function destroy()
    {
        dd('destroy');
    }
}
