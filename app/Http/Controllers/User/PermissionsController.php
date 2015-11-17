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

        $tableListData = $this->prepareTableListData($request, $columns, 'permissions');

        if (isset($tableListData['errors']) && !empty($errors = $tableListData['errors'])) {
            // trigger the alert on this load
            \Modal::alert($errors, 'error', true);
        }

        // prepare data for the view
        $data = [
            'tableListData' => $tableListData,
            'seoMeta' => $this->seoMeta,
        ];

        // return the view with data
        return view('pages.back.permissions-list')->with($data);
    }

    public function show()
    {
        dd('show');
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
        return view('pages.back.permission-creation')->with($data);
    }

    public function store(Request $request)
    {
        $inputs = $request->all();

        // we check the inputs
        $errors = [];
        $validator = \Validator::make($inputs, [
            'name' => 'required'
        ]);
        foreach ($validator->errors()->all() as $error) {
            $errors[] = $error;
        }
        // if errors are found
        if (count($errors)) {
            \Modal::alert($errors, 'error');
            return Redirect()->back();
        }

        // we slugify the name
        $inputs['slug'] = str_slug($inputs['name']);

        if($role = \Sentinel::getRoleRepository()->createModel()->create($inputs)){
            \Modal::alert([
                'Le rôle <b>' . $role->name . '</b> a bien été créé.'
            ], 'success');
            return Redirect(route('permissions'));
        }

        \Modal::alert([
            "Une erreur est survenue lors de la création du rôle utilisateur." .
            "Veuillez contacter le support :" . "<a href='mailto:" . config('settings.support_email') . "' >" .
            config('settings.support_email') . "</a>."
        ], 'error');
        return Redirect()->back();
    }

    public function edit()
    {
        dd('edit');
    }

    public function destroy()
    {

    }
}
