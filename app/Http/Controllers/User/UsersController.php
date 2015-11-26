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
        $required = 'users.list';
        if (!\Sentinel::getUser()->hasAccess([$required])) {
            \Modal::alert([
                trans('permissions.denied') . " : <b>" . trans('permissions.' . $required) . "</b>",
            ], 'error');

            return Redirect()->back();
        }

        // SEO Meta settings
        $this->seoMeta['page_title'] = trans('seo.users.index');

        // we define the table list columns
        $columns = [
            [
                'title' => trans('users.view.label.photo'),
                'key'   => 'photo',
                'image' => [
                    'storage_path' => \Sentinel::createModel()->storagePath(),
                    'size'   => 'admin',
                ],
            ], [
                'title'   => trans('users.view.label.last_name'),
                'key'     => 'last_name',
                'sort_by' => 'users.last_name',
            ], [
                'title'   => trans('users.view.label.first_name'),
                'key'     => 'first_name',
                'sort_by' => 'users.first_name',
            ], [
                'title'   => trans('users.view.label.status'),
                'key'     => 'status',
                'config'  => 'user.status',
                'sort_by' => 'users.status',
                'button'  => true,
            ], [
                'title'   => trans('users.view.label.board'),
                'key'     => 'board',
                'config'  => 'user.board',
                'sort_by' => 'users.board',
                'button'  => true,
            ],
            [
                'title'      => trans('users.view.label.role'),
                'key'        => 'roles',
                'collection' => 'name',
                'sort_by'    => 'roles.name',
                'button'     => [
                    'attribute' => 'slug',
                ],
            ],
            [
                'title'    => trans('users.view.label.activation'),
                'key'      => 'activated',
                'activate' => 'users.activate',
            ],
        ];

        // we instantiate the query
        $query = \Sentinel::getUserRepository()->where('users.id', '<>', \Sentinel::getUser()->id);

        $query->groupBy('users.id');

        // we select the data we want
        $query->select('users.*');
        $query->selectRaw('if(activations.completed_at, true, false) as activated');

        // we execute the table joins
        $query->leftJoin('role_users', 'role_users.user_id', '=', 'users.id');
        $query->leftJoin('roles', 'roles.id', '=', 'role_users.role_id');
        $query->leftJoin('activations', 'activations.user_id', '=', 'users.id');

        // we prepare the confirm config
        $confirm_config = [
            'action'     => 'Supression de l\'utilisateur',
            'attributes' => ['first_name', 'last_name'],
        ];

        // we prepare the search config
        $search_config = [
            'users.first_name',
            'users.last_name',
        ];

        // we format the data for the needs of the view
        $tableListData = $this->prepareTableListData(
            $query,
            $request,
            $columns,
            'users',
            $confirm_config,
            $search_config
        );

        // prepare data for the view
        $data = [
            'tableListData' => $tableListData,
            'seoMeta'       => $this->seoMeta,
        ];

        // return the view with data
        return view('pages.back.users-list')->with($data);
    }

    public function show($id)
    {
        // we check the current user permission
        $required = 'users.view';
        if (!\Sentinel::getUser()->hasAccess([$required])) {
            \Modal::alert([
                trans('permissions.denied') . " : <b>" . trans('permissions.' . $required) . "</b>",
            ], 'error');

            return Redirect()->back();
        }

        // SEO Meta settings
        $this->seoMeta['page_title'] = trans('seo.users.show');

        // we get the role
        $user = \Sentinel::findById($id);

        // we check if the role exists
        if (!$user) {
            \Modal::alert([
                trans('users.message.find.failure'),
                trans('errors.contact', [
                    'email' => "<a href='mailto:" . config('settings.support_email') . "' >" .
                        config('settings.support_email') . "</a>.",
                ]),
            ], 'error');

            return Redirect()->back();
        }

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

    public function create()
    {
        // SEO Meta settings
        $this->seoMeta['page_title'] = trans('seo.users.create');

        // prepare data for the view
        $data = [
            'roles'   => \Sentinel::getRoleRepository()->all(),
            'seoMeta' => $this->seoMeta,
        ];

        // return the view with data
        return view('pages.back.user-edit')->with($data);
    }

    public function store(Request $request)
    {
        // we check the current user permission
        $required = 'users.create';
        if (!\Sentinel::getUser()->hasAccess([$required])) {
            \Modal::alert([
                trans('permissions.denied') . " : <b>" . trans('permissions.' . $required) . "</b>",
            ], 'error');

            return Redirect()->back();
        }

        // we get the inputs
        $inputs = $request->except('_token');

        // we convert the en/fr date to the database format
        if (isset($inputs['birth_date']) && $inputs['birth_date']) {
            $inputs['birth_date'] = Carbon::createFromFormat('d/m/Y', $inputs['birth_date'])->format('Y-m-d');
        }

        // we check the inputs
        $errors = [];
        $validator = \Validator::make($inputs, [
            'photo'        => 'mimes:jpg,jpeg,png',
            'gender'       => 'required|in:' . implode(',', array_keys(config('user.gender'))),
            'last_name'    => 'required',
            'first_name'   => 'required',
            'birth_date'   => 'required|date_format:Y-m-d',
            'phone_number' => 'required|phone:FR',
            'email'        => 'required|email|unique:users,email',
            'zip_code'     => 'digits:5',
            'role'         => 'required|numeric|exists:roles,id',
            'password'     => 'required|min:6|confirmed',
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

        // we create the user
        $user = \Sentinel::create($inputs);

        try {
            // we format the number into its international equivalent
            $inputs['phone_number'] = $formatted_phone_number = phone_format(
                $inputs['phone_number'],
                'FR',
                \libphonenumber\PhoneNumberFormat::INTERNATIONAL
            );

            // we store the photo
            if (isset($inputs['photo']) && !empty($photo = $inputs['photo'])) {
                // we resize, optimize and save the image
                $file_name = \ImageManager::resize(
                    $photo,
                    $user->id . '_photo', 'user',
                    $user->image_sizes
                );
                // we add the image name to the inputs for saving
                $inputs['photo'] = $file_name;
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

            return Redirect(route('users.index'));
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
                trans('errors.contact', [
                    'email' => "<a href='mailto:" . config('settings.support_email') . "' >" .
                        config('settings.support_email') . "</a>.",
                ]),
            ], 'error');

            return Redirect()->back();
        }
    }

    public function update(Request $request)
    {
        // we check the current user permission
        $required = 'users.update';
        if (!\Sentinel::getUser()->hasAccess([$required])) {
            \Modal::alert([
                trans('permissions.denied') . " : <b>" . trans('permissions.' . $required) . "</b>",
            ], 'error');

            return Redirect()->back();
        }

        // we get the user
        if (!$user = \Sentinel::findById($request->get('_id'))) {
            \Modal::alert([
                trans('users.message.find.failure', ['id' => $request->get('_id')]),
            ], 'error');

            return Redirect()->back();
        }

        // we convert the "on" value to the activation order to a boolean value
        $request->merge([
            'activation' => filter_var($request->get('activation'), FILTER_VALIDATE_BOOLEAN),
        ]);

        // we get the inputs, according if we update the current profile account or a user profile
        if ($user->id === \Sentinel::getUser()->id) {
            $rules = [
                '_id'          => 'required|numeric|exists:users,id',
                'photo'        => 'mimes:jpg,jpeg,png',
                'gender'       => 'required|in:' . implode(',', array_keys(config('user.gender'))),
                'last_name'    => 'required',
                'first_name'   => 'required',
                'birth_date'   => 'required|date_format:Y-m-d',
                'phone_number' => 'required|phone:FR',
                'email'        => 'required|email|unique:users,email,' . $request->get('_id'),
                'zip_code'     => 'digits:5',
                'password'     => 'min:6|confirmed',
            ];
            $inputs = $request->except('_token', '_method', 'activation', 'role');
        } else {
            $rules = [
                '_id'          => 'required|numeric|exists:users,id',
                'photo'        => 'mimes:jpg,jpeg,png',
                'gender'       => 'required|in:' . implode(',', array_keys(config('user.gender'))),
                'last_name'    => 'required',
                'first_name'   => 'required',
                'birth_date'   => 'required|date_format:Y-m-d',
                'phone_number' => 'required|phone:FR',
                'email'        => 'required|email|unique:users,email,' . $request->get('_id'),
                'zip_code'     => 'digits:5',
                'role'         => 'required|numeric|exists:roles,id',
                'activation'   => 'boolean',
                'password'     => 'min:6|confirmed',
            ];
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

        // we check the inputs
        $errors = [];
        $validator = \Validator::make($inputs, $rules);
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

//        try {
            // we format the number into its international equivalent
            $inputs['phone_number'] = $formatted_phone_number = phone_format(
                $inputs['phone_number'],
                'FR',
                \libphonenumber\PhoneNumberFormat::INTERNATIONAL
            );

            // we store the photo
            if (isset($inputs['photo']) && !empty($photo = $inputs['photo'])) {
                // we resize, optimize and save the image
                $file_name = \ImageManager::resize(
                    $photo,
                    $user->imageName(),
                    $user->storagePath(),
                    $user->availableSizes()
                );
                // we add the image name to the inputs for saving
                $inputs['photo'] = $file_name;
            }

            // we update the user
            \Sentinel::update($user, $inputs);

            // if we're not updating the current user profile
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
            }

            // we notify the current user
            \Modal::alert([
                trans('users.message.account.success'),
            ], 'success');

            return Redirect()->back();
//        } catch (\Exception $e) {
//            // we flash the request
//            $request->flash();
//
//            // we log the error and we notify the current user
//            \Log::error($e);
//            \Modal::alert([
//                trans('users.message.account.failure'),
//                trans('errors.contact', [
//                    'email' => "<a href='mailto:" . config('settings.support_email') . "' >" .
//                        config('settings.support_email') . "</a>.",
//                ]),
//            ], 'error');
//
//            return Redirect()->back();
//        }
    }

    public function destroy(Request $request)
    {
        // we check the current user permission
        $required = 'users.delete';
        if (!\Sentinel::getUser()->hasAccess([$required])) {
            \Modal::alert([
                trans('permissions.denied') . " : <b>" . trans('permissions.' . $required) . "</b>",
            ], 'error');

            return Redirect()->back();
        }

        // we get the user
        if (!$user = \Sentinel::findById($request->get('_id'))) {
            \Modal::alert([
                trans('users.message.find.failure'),
            ], 'error');

            return Redirect()->back();
        }

        // we delete the role
        try {
            $user->delete();
            \Modal::alert([
                trans('users.message.delete.success', ['name' => $user->first_name . ' ' . $user->last_name]),
            ], 'success');

            return Redirect()->back();
        } catch (\Exception $e) {
            \Log::error($e);
            \Modal::alert([
                trans('users.message.delete.failure', ['name' => $user->first_name . ' ' . $user->last_name]),
                trans('errors.contact', [
                    'email' => "<a href='mailto:" . config('settings.support_email') . "' >" .
                        config('settings.support_email') . "</a>.",
                ]),
            ], 'error');

            return Redirect()->back();
        }
    }

    public function activate(Request $request)
    {
        // we check the current user permission
        $required = 'users.update';
        if (!\Sentinel::getUser()->hasAccess([$required])) {
            \Modal::alert([
                trans('permissions.denied') . " : <b>" . trans('permissions.' . $required) . "</b>",
            ], 'error');

            return Redirect()->back();
        }

        // we convert the "on" value to the activation order to a boolean value
        $request->merge([
            'activation_order' => filter_var($request->get('activation_order'), FILTER_VALIDATE_BOOLEAN),
        ]);

        // we check the inputs
        $errors = [];
        $validator = \Validator::make($request->all(), [
            'id'               => 'required|exists:users,id',
            'activation_order' => 'required|boolean',
        ]);
        foreach ($validator->errors()->all() as $error) {
            $errors[] = $error;
        }
        // if errors are found
        if (count($errors)) {
            return response($errors, 401);
        }

        // we get the user
        $user = \Sentinel::findById($request->get('id'));

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
