<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Carbon\Carbon;
use Illuminate\Http\Request;

class UsersController extends Controller
{

    /**
     * UsersController constructor.
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
        if (!$this->requirePermission('users.list')) {
            return redirect()->back();
        }

        // SEO Meta settings
        $this->seoMeta['page_title'] = trans('seo.users.index');

        // we define the table list columns
        $columns = [
            [
                'title' => trans('users.page.label.photo'),
                'key'   => 'photo',
                'image' => [
                    'storage_path' => \Sentinel::createModel()->storagePath(),
                    'size'         => [
                        'thumbnail' => 'admin',
                        'detail'    => 'picture',
                    ],
                ],
            ],
            [
                'title'   => trans('users.page.label.last_name'),
                'key'     => 'last_name',
                'sort_by' => 'users.last_name',
            ],
            [
                'title'   => trans('users.page.label.first_name'),
                'key'     => 'first_name',
                'sort_by' => 'users.first_name',
            ],
            [
                'title'   => trans('users.page.label.status'),
                'key'     => 'status',
                'config'  => 'user.status',
                'sort_by' => 'users.status',
                'button'  => true,
            ],
            [
                'title'   => trans('users.page.label.board'),
                'key'     => 'board',
                'config'  => 'user.board',
                'sort_by' => 'users.board',
                'button'  => true,
            ],
            [
                'title'      => trans('users.page.label.role'),
                'key'        => 'roles',
                'collection' => 'name',
                'sort_by'    => 'roles.name',
                'button'     => [
                    'attribute' => 'slug',
                ],
            ],
            [
                'title'    => trans('users.page.label.activation'),
                'key'      => 'activated',
                'activate' => 'users.activate',
            ],
        ];

        // we set the routes used in the table list
        $routes = [
            'index'   => 'users.index',
            'create'  => 'users.create',
            'edit'    => 'users.edit',
            'destroy' => 'users.destroy',
        ];

        // we instantiate the query
        $query = \Sentinel::getUserRepository()->where('users.id', '<>', \Sentinel::getUser()->id);

        $query->groupBy('users.id');

        // we select the data we want
        $query->select('users.*');
        $query->selectRaw('if(activations.completed, true, false) as activated');

        // we execute the table joins
        $query->leftJoin('role_users', 'role_users.user_id', '=', 'users.id');
        $query->leftJoin('roles', 'roles.id', '=', 'role_users.role_id');
        $query->leftJoin('activations', 'activations.user_id', '=', 'users.id');

        // we prepare the confirm config
        $confirm_config = [
            'action'     => trans('users.page.action.delete'),
            'attributes' => ['first_name', 'last_name'],
        ];

        // we prepare the search config
        $search_config = [
            'users.first_name',
            'users.last_name',
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
            $enable_lines_choice
        );

        // prepare data for the view
        $data = [
            'tableListData' => $tableListData,
            'seoMeta'       => $this->seoMeta,
        ];

        // return the view with data
        return view('pages.back.users-list')->with($data);
    }

    public function create()
    {
        // we check the current user permission
        if (!$this->requirePermission('users.create')) {
            return redirect()->back();
        }

        // SEO Meta settings
        $this->seoMeta['page_title'] = trans('seo.users.create');

        // prepare data for the view
        $data = [
            'seoMeta'  => $this->seoMeta,
            'statuses' => config('user.status'),
            'boards'   => config('user.board'),
            'roles'    => \Sentinel::getRoleRepository()->orderBy('rank', 'asc')->get(),
        ];

        // return the view with data
        return view('pages.back.user-edit')->with($data);
    }

    public function store(Request $request)
    {
        // we check the current user permission
        if (!$this->requirePermission('users.create')) {
            return redirect()->back();
        }

        // we check if the new user role is not higher than the role of the current user
        $new_user_role = \Sentinel::findRoleById($request->get('role'));
        $current_user_role = \Sentinel::getUser()->roles->first();
        if ($new_user_role && $current_user_role && $new_user_role->rank < $current_user_role->rank) {
            // we flash the request
            $request->flash();

            // we notify the user
            \Modal::alert([
                trans('permissions.message.rank.denied', ['action' => trans('permissions.message.rank.action.create')]),
            ], 'error');

            return redirect()->back();
        }

        // we get the inputs
        $inputs = $request->except('_token');

        // we convert the en/fr date to the database format
        if (isset($inputs['birth_date']) && $inputs['birth_date']) {
            $inputs['birth_date'] = Carbon::createFromFormat('d/m/Y', $inputs['birth_date'])->format('Y-m-d');
        }

        // we check inputs validity
        $rules = [
            'photo'        => 'image|mimes:jpg,jpeg,png|image_size:>=145,>=160',
            'gender'       => 'in:' . implode(',', array_keys(config('user.gender'))),
            'last_name'    => 'required|string',
            'first_name'   => 'required|string',
            'birth_date'   => 'date_format:Y-m-d',
            'status'       => 'in:' . implode(',', array_keys(config('user.status'))),
            'board'        => 'in:' . implode(',', array_keys(config('user.board'))),
            'phone_number' => 'phone:FR',
            'email'        => 'required|email|unique:users,email',
            'address'      => 'string',
            'zip_code'     => 'digits:5',
            'city'         => 'string',
            'country'      => 'string',
            'role'         => 'required|numeric|exists:roles,id',
            'password'     => 'required|min:6|confirmed',
        ];
        if (!$this->checkInputsValidity($inputs, $rules, $request)) {
            return redirect()->back();
        }

        // we create the user
        $user = \Sentinel::create($inputs);

        try {
            // we format the number into its international equivalent
            if (isset($inputs['phone_number']) && !empty($photo = $inputs['phone_number'])) {
                $inputs['phone_number'] = $formatted_phone_number = phone_format(
                    $inputs['phone_number'],
                    'FR',
                    \libphonenumber\PhoneNumberFormat::INTERNATIONAL
                );
            }

            // we store the photo
            if (isset($inputs['photo']) && !empty($photo = $inputs['photo'])) {

                // we optimize, resize and save the image
                $file_name = \ImageManager::optimizeAndResize(
                    $photo->getRealPath(),
                    $user->imageName('photo'),
                    $photo->getClientOriginalExtension(),
                    $user->storagePath(),
                    $user->availableSizes('photo')
                );

                // we update the image name
                $user->photo = $file_name;
                $user->save();
            }

            // we attach the new role
            $role = \Sentinel::findRoleById($inputs['role']);
            $role->users()->attach($user);

            // if the order is to activate the user
            if (isset($inputs['activation']) && $inputs['activation']) {
                // we activate the user
                if (!$activation = \Activation::exists($user)) {
                    $activation = \Activation::create($user);
                }
                \Activation::complete($user, $activation->code);
            }

            // we notify the current user
            \Modal::alert([
                trans('users.message.creation.success', ['name' => $user->first_name . ' ' . $user->last_name]),
            ], 'success');

            return redirect(route('users.index'));
        } catch (\Exception $e) {
            // we flash the request
            $request->flash();

            // we delete the user if something went wrong after the user creation
            if ($user) {
                $user->delete();
            }

            // we log the error and we notify the current user
            \Log::error($e);
            \Modal::alert([
                trans('users.message.creation.failure', ['name' => $user->first_name . ' ' . $user->last_name]),
                trans('global.message.global.failure.contact.support', ['email' => config('settings.support_email'),]),
            ], 'error');

            return redirect()->back();
        }
    }

    public function edit($id)
    {
        // we check the current user permission
        if (!$this->requirePermission('users.view')) {
            return redirect()->back();
        }

        // we get the user
        $user = \Sentinel::findById($id);

        // we check if the current user has a role rank high enough to edit the user
        $edited_user_role = $user->roles->first();
        $current_user_role = \Sentinel::getUser()->roles->first();
        if ($edited_user_role && $current_user_role && $edited_user_role->rank < $current_user_role->rank) {
            \Modal::alert([
                trans('permissions.message.rank.denied', ['action' => trans('permissions.message.rank.action.edit')]),
            ], 'error');

            return redirect()->back();
        }

        // SEO Meta settings
        $this->seoMeta['page_title'] = trans('seo.users.edit');


        // we check if the role exists
        if (!$user) {
            \Modal::alert([
                trans('users.message.find.failure'),
                trans('global.message.global.failure.contact.support', ['email' => config('settings.support_email'),]),
            ], 'error');

            return redirect()->back();
        }

        // we convert the database date to the fr/en format
        if ($birth_date = $user->birth_date) {
            $user->birth_date = Carbon::createFromFormat('Y-m-d', $birth_date)->format('d/m/Y');
        }

        // we prepare the data for breadcrumbs
        $breadcrumbs_data = [
            $user->first_name . ' ' . $user->last_name,
        ];

        // prepare data for the view
        $data = [
            'seoMeta'          => $this->seoMeta,
            'user'             => $user,
            'statuses'         => config('user.status'),
            'boards'           => config('user.board'),
            'roles'            => \Sentinel::getRoleRepository()->orderBy('rank', 'asc')->get(),
            'breadcrumbs_data' => $breadcrumbs_data,
        ];

        // return the view with data
        return view('pages.back.user-edit')->with($data);
    }

    public function profile()
    {
        // SEO Meta settings
        $this->seoMeta['page_title'] = trans('seo.users.profile');

        // we get the current user
        $user = \Sentinel::getUser();

        // we convert the database date to the fr/en format
        if ($birth_date = $user->birth_date) {
            $user->birth_date = Carbon::createFromFormat('Y-m-d', $birth_date)->format('d/m/Y');
        }

        // prepare data for the view
        $data = [
            'seoMeta' => $this->seoMeta,
            'user'    => $user,
            'roles'   => \Sentinel::getRoleRepository()->all(),
        ];

        // return the view with data
        return view('pages.back.user-edit')->with($data);
    }

    public function update(Request $request)
    {
        // we check the current user permission
        if (!$this->requirePermission('users.update')) {
            return redirect()->back();
        }

        // we get the user
        if (!$user = \Sentinel::findById($request->get('_id'))) {
            \Modal::alert([
                trans('users.message.find.failure', ['id' => $request->get('_id')]),
            ], 'error');

            return redirect()->back();
        }

        // we check if the current user has a role rank high enough to edit the user
        $edited_user_role = $user->roles->first();
        $current_user_role = \Sentinel::getUser()->roles->first();
        if ($edited_user_role && $current_user_role && $edited_user_role->rank < $current_user_role->rank) {
            \Modal::alert([
                trans('permissions.message.rank.denied', ['action' => trans('permissions.message.rank.action.edit')]),
            ], 'error');

            return redirect()->back();
        }

        // we check if the chosen role is not higher than the role of the current user
        $new_user_role = \Sentinel::findRoleById($request->get('role'));
        $current_user_role = \Sentinel::getUser()->roles->first();
        if ($new_user_role && $current_user_role && $new_user_role->rank < $current_user_role->rank) {
            // we flash the request
            $request->flash();

            // we notify the user
            \Modal::alert([
                trans('permissions.message.rank.denied', ['action' => trans('permissions.message.rank.action.edit')]),
            ], 'error');

            return redirect()->back();
        }

        // we convert the "on" value to the activation order to a boolean value
        $request->merge([
            'activation' => filter_var($request->get('activation'), FILTER_VALIDATE_BOOLEAN),
        ]);

        // we set the validation rules
        $rules = [
            '_id'          => 'required|numeric|exists:users,id',
            'photo'        => 'image|mimes:jpg,jpeg,png|image_size:>=145,>=160',
            'gender'       => 'in:' . implode(',', array_keys(config('user.gender'))),
            'last_name'    => 'required|string',
            'first_name'   => 'required|string',
            'birth_date'   => 'date_format:Y-m-d',
            'status'       => 'in:' . implode(',', array_keys(config('user.status'))),
            'board'        => 'in:' . implode(',', array_keys(config('user.board'))),
            'phone_number' => 'phone:FR',
            'email'        => 'required|email|unique:users,email,' . $request->get('_id'),
            'address'      => 'string',
            'zip_code'     => 'digits:5',
            'city'         => 'string',
            'country'      => 'string',
            'password'     => 'min:6|confirmed',
        ];

        // we add inputs, according if we update the current profile account or a user profile
        if ($user->id === \Sentinel::getUser()->id) {
            $inputs = $request->except('_token', '_method', 'activation', 'role');
        } else {
            $rules['role'] = 'required|numeric|exists:roles,id';
            $rules['activation'] = 'boolean';
            $inputs = $request->except('_token', '_method');
        }

        // we don't update not filled inputs
        $inputs = array_filter($inputs, function ($input) {
            return strlen($input);
        });

        // we convert the en/fr date to the database format
        if (isset($inputs['birth_date']) && $inputs['birth_date']) {
            $inputs['birth_date'] = Carbon::createFromFormat('d/m/Y', $inputs['birth_date'])->format('Y-m-d');
        }

        // we check inputs validity
        if (!$this->checkInputsValidity($inputs, $rules, $request)) {
            return redirect()->back();
        }

        try {
            // we format the number into its international equivalent
            if (isset($inputs['phone_number']) && !empty($photo = $inputs['phone_number'])) {
                $inputs['phone_number'] = $formatted_phone_number = phone_format(
                    $inputs['phone_number'],
                    'FR',
                    \libphonenumber\PhoneNumberFormat::INTERNATIONAL
                );
            }

            // we store the photo
            if (isset($inputs['photo']) && !empty($photo = $inputs['photo'])) {
                // we optimize, resize and save the image
                $file_name = \ImageManager::optimizeAndResize(
                    $photo->getRealPath(),
                    $user->imageName('photo'),
                    $photo->getClientOriginalExtension(),
                    $user->storagePath(),
                    $user->availableSizes('photo')
                );
                // we add the image name to the inputs for saving
                $inputs['photo'] = $file_name;
            }

            // we update the user
            \Sentinel::update($user, $inputs);

            // if we're updating the profile of another user
            if ($user->id !== \Sentinel::getUser()->id) {
                // we check is the user is attached to roles
                $current_roles = $user->roles;
                // we detach each roles found
                foreach ($current_roles as $role) {
                    $role->users()->detach($user);
                }
                // we attach the new role
                $role = \Sentinel::findRoleById($inputs['role']);
                $role->users()->attach($user);

                // if the order is to activate the user
                if (isset($inputs['activation']) && $inputs['activation']) {
                    // we activate the user
                    if (!$activation = \Activation::exists($user)) {
                        $activation = \Activation::create($user);
                    }
                    \Activation::complete($user, $activation->code);
                } else {
                    // or we deactivate him
                    \Activation::remove($user);
                }

                // we notify the current user
                \Modal::alert([
                    trans('users.message.update.success', ['name' => $user->first_name . ' ' . $user->last_name]),
                ], 'success');

                return redirect()->back();
            }

            // we notify the current user
            \Modal::alert([
                trans('users.message.account.success'),
            ], 'success');

            return redirect()->back();
        } catch (\Exception $e) {
            // we flash the request
            $request->flash();

            // we log the error and we notify the current user
            \Log::error($e);

            // if we're updating the profile of another user
            if ($user->id !== \Sentinel::getUser()->id) {
                \Modal::alert([
                    trans('users.message.update.failure'),
                    trans('global.message.global.failure.contact.support', ['email' => config('settings.support_email'),]),
                ], 'error');
            } else {
                \Modal::alert([
                    trans('users.message.account.failure'),
                    trans('global.message.global.failure.contact.support', ['email' => config('settings.support_email'),]),
                ], 'error');
            }

            return redirect()->back();
        }
    }

    public function destroy(Request $request)
    {
        // we check the current user permission
        if (!$this->requirePermission('users.delete')) {
            return redirect()->back();
        }

        // we get the user
        if (!$user = \Sentinel::findById($request->get('_id'))) {
            \Modal::alert([
                trans('users.message.find.failure'),
            ], 'error');

            return redirect()->back();
        }

        // we check if the current user has a role rank high enough to edit the user
        $edited_user_role = $user->roles->first();
        $current_user_role = \Sentinel::getUser()->roles->first();
        if ($edited_user_role && $current_user_role && $edited_user_role->rank < $current_user_role->rank) {
            \Modal::alert([
                trans('permissions.message.rank.denied', ['action' => trans('permissions.message.rank.action.delete')]),
            ], 'error');

            return redirect()->back();
        }

        try {
            // we remove the users photos
            if ($user->photo) {
                \ImageManager::remove(
                    $user->photo,
                    $user->storagePath(),
                    $user->availableSizes()
                );
            }

            // we delete the role
            $user->delete();

            \Modal::alert([
                trans('users.message.delete.success', ['name' => $user->first_name . ' ' . $user->last_name]),
            ], 'success');

            return redirect()->back();
        } catch (\Exception $e) {
            \Log::error($e);
            \Modal::alert([
                trans('users.message.delete.failure', ['name' => $user->first_name . ' ' . $user->last_name]),
                trans('global.message.global.failure.contact.support', ['email' => config('settings.support_email')]),
            ], 'error');

            return redirect()->back();
        }
    }

    public function activate(Request $request)
    {
        // we check the current user permission
        if (!$this->requirePermission('users.update')) {
            return redirect()->back();
        }

        // we get the user
        if (!$user = \Sentinel::findById($request->get('id'))) {
            return response([
                trans('users.message.find.failure', ['id' => $request->get('id')]),
            ], 401);
        }

        // we check if the current user has a role rank high enough to edit the user
        $edited_user_role = $user->roles->first();
        $current_user_role = \Sentinel::getUser()->roles->first();
        if ($edited_user_role && $current_user_role && $edited_user_role->rank < $current_user_role->rank) {
            return response([
                trans('permissions.message.rank.denied', ['action' => trans('permissions.message.rank.action.delete')]),
            ], 401);
        }

        // we convert the "on" value to the activation order to a boolean value
        $request->merge([
            'activation_order' => filter_var($request->get('activation_order'), FILTER_VALIDATE_BOOLEAN),
        ]);

        // we check inputs validity
        $rules = [
            'id'               => 'required|exists:users,id',
            'activation_order' => 'required|boolean',
        ];
        if (!$this->checkInputsValidity($request->all(), $rules, $request)) {
            return redirect()->back();
        }

        try {
            // if the order is : activation
            if ($request->get('activation_order')) {
                // we activate the user
                if (!$activation = \Activation::exists($user)) {
                    $activation = \Activation::create($user);
                }
                \Activation::complete($user, $activation->code);
            } else {
                \Activation::remove($user);
            }

            return response([
                trans('users.message.activation.success'),
            ], 200);
        } catch (\Exception $e) {
            \Log::error($e);

            return response([
                trans('users.message.activation.failure'),
            ], 401);
        }
    }
}
