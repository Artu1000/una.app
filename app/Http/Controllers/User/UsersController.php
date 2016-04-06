<?php

namespace App\Http\Controllers\User;

use Activation;
use App\Http\Controllers\Controller;
use Carbon\Carbon;
use CustomLog;
use Entry;
use Exception;
use Illuminate\Http\Request;
use ImageManager;
use libphonenumber\PhoneNumberFormat;
use Modal;
use Permission;
use Sentinel;
use TableList;
use Validation;

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
        if (!Permission::hasPermission('users.list')) {
            return redirect()->route('dashboard.index');
        }

        // SEO Meta settings
        $this->seoMeta['page_title'] = trans('seo.back.users.index');

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
                'title'   => trans('users.page.label.status_id'),
                'key'     => 'status_id',
                'config'  => 'user.status',
                'trans'   => 'users.config.status',
                'sort_by' => 'users.status_id',
                'button'  => [
                    'attribute' => 'key',
                ],
            ],
            [
                'title'   => trans('users.page.label.board_id'),
                'key'     => 'board_id',
                'config'  => 'user.board',
                'trans'   => 'users.config.board',
                'sort_by' => 'users.board_id',
                'button'  => [
                    'attribute' => 'key',
                ],
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
                'title'    => trans('users.page.label.active'),
                'key'      => 'active',
                'activate' => [
                    'route'  => 'users.activate',
                    'params' => [],
                ],
            ],
        ];

        // we set the routes used in the table list
        $routes = [
            'index'   => [
                'route'  => 'users.index',
                'params' => [],
            ],
            'create'  => [
                'route'  => 'users.create',
                'params' => [],
            ],
            'edit'    => [
                'route'  => 'users.edit',
                'params' => [],
            ],
            'destroy' => [
                'route'  => 'users.destroy',
                'params' => [],
            ],
        ];

        // we instantiate the query
        $query = \Sentinel::getUserRepository()->where('users.id', '<>', \Sentinel::getUser()->id);

        // we group the results
        $query->groupBy('users.id');

        // we select the data we want to show
        $query->select('users.*');
        $query->selectRaw('if(activations.completed, true, false) as active');

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
            [
                'key'      => trans('users.page.label.first_name'),
                'database' => 'users.first_name',
            ],
            [
                'key'      => trans('users.page.label.last_name'),
                'database' => 'users.last_name',
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
        return view('pages.back.users-list')->with($data);
    }

    public function create()
    {
        // we check the current user permission
        if (!Permission::hasPermission('users.create')) {
            // we redirect the current user to the user list if he has the required permission
            if (Sentinel::getUser()->hasAccess('users.list')) {
                return redirect()->route('users.list');
            } else {
                // or we redirect the current user to the home page
                return redirect()->route('dashboard.index');
            }
        }

        // SEO Meta settings
        $this->seoMeta['page_title'] = trans('seo.back.users.create');

        // prepare data for the view
        $data = [
            'seoMeta'  => $this->seoMeta,
            'statuses' => config('user.status'),
            'boards'   => config('user.board'),
            'roles'    => \Sentinel::getRoleRepository()->orderBy('position', 'asc')->get(),
        ];

        // return the view with data
        return view('pages.back.user-edit')->with($data);
    }

    public function store(Request $request)
    {
        // we check the current user permission
        if (!Permission::hasPermission('users.create')) {
            // we redirect the current user to the user list if he has the required permission
            if (Sentinel::getUser()->hasAccess('users.list')) {
                return redirect()->route('users.index');
            } else {
                // or we redirect the current user to the home page
                return redirect()->route('dashboard.index');
            }
        }

        // we check if the new user role is not higher than the role of the current user
        $new_user_role = Sentinel::findRoleById($request->get('role'));
        $current_user_role = Sentinel::getUser()->roles->first();
        if ($new_user_role && $current_user_role && $new_user_role->position < $current_user_role->position) {
            // we flash the request
            $request->flashExcept('photo');

            // we notify the user
            Modal::alert([
                trans('users.message.permission.denied', ['action' => trans('users.message.permission.action.create')]),
            ], 'error');

            return redirect()->back();
        }

        // we sanitize the entries
        $request->replace(Entry::sanitizeAll($request->all()));

        try {
            // we convert the en/fr date to the database format
            if ($birth_date = $request->get('birth_date')) {
                $request->merge(['birth_date' => Carbon::createFromFormat('d/m/Y', $birth_date)->format('Y-m-d')]);
            }
        } catch (Exception $e) {
            // we log the error
            CustomLog::error($e);
        }

        // we check inputs validity
        $rules = [
            'photo'        => 'image|mimes:jpg,jpeg,png|image_size:>=145,>=160',
            'gender'       => 'in:' . implode(',', array_keys(config('user.gender'))),
            'last_name'    => 'required|string',
            'first_name'   => 'required|string',
            'birth_date'   => 'date_format:Y-m-d',
            'status_id'    => 'required|numeric|in:' . implode(',', array_keys(config('user.status'))),
            'board_id'     => 'numeric|in:' . implode(',', array_keys(config('user.board'))),
            'phone_number' => 'phone:FR',
            'email'        => 'required|email|unique:users,email',
            'address'      => 'string',
            'zip_code'     => 'digits:5',
            'city'         => 'string',
            'country'      => 'string',
            'role'         => 'required|numeric|exists:roles,id',
            'active'       => 'boolean',
            'password'     => 'required|min:' . config('password.min.length') . '|confirmed',
        ];
        if (!Validation::check($request->all(), $rules)) {
            // we flash the request
            $request->flashExcept('photo');

            return redirect()->back();
        }

        // we create the user variable
        $user = null;

        try {
            // we create the user
            $user = \Sentinel::create($request->all());

            // we format the number into its international equivalent
            if ($phone_number = $request->get('phone_number')) {
                $user->phone_number = phone_format(
                    $phone_number,
                    'FR',
                    PhoneNumberFormat::INTERNATIONAL
                );
                $user->save();
            }

            // we store the photo
            if ($photo = $request->file('photo')) {
                // we optimize, resize and save the image
                $file_name = ImageManager::optimizeAndResize(
                    $photo->getRealPath(),
                    $user->imageName('photo'),
                    $photo->getClientOriginalExtension(),
                    $user->storagePath(),
                    $user->availableSizes('photo')
                );
            } else {
                // we set the una logo as the user image
                $file_name = ImageManager::optimizeAndResize(
                    database_path('seeds/files/settings/logo-una-dark.png'),
                    $user->imageName('photo'),
                    config('image.settings.logo.extension'),
                    $user->storagePath(),
                    $user->availableSizes('photo'),
                    false
                );
            }
            // we update the image name
            $user->photo = $file_name;
            $user->save();

            // we attach the new role
            $role = Sentinel::findRoleById($request->get('role'));
            $role->users()->attach($user);

            // if the order is to activate the user
            if ($request->get('active')) {
                // we activate the user
                if (!$activation = Activation::completed($user)) {
                    $activation = Activation::create($user);
                }
                Activation::complete($user, $activation->code);
            }

            // we notify the current user
            Modal::alert([
                trans('users.message.creation.success', ['name' => $user->first_name . ' ' . $user->last_name]),
            ], 'success');

            return redirect(route('users.index'));
        } catch (Exception $e) {
            // we flash the request
            $request->flashExcept('photo');

            // we log the error
            CustomLog::error($e);

            // we notify the user
            Modal::alert([
                trans('users.message.creation.failure', ['name' => $user->first_name . ' ' . $user->last_name]),
                trans('global.message.global.failure.contact.support', ['email' => config('settings.support_email'),]),
            ], 'error');

            // we delete the user if something went wrong after the user creation
            if ($user) {
                $user->delete();
            }

            return redirect()->back();
        }
    }

    public function edit($id)
    {
        // we check the current user permission
        if (!Permission::hasPermission('users.view')) {
            // we redirect the current user to the user list if he has the required permission
            if (Sentinel::getUser()->hasAccess('users.list')) {
                return redirect()->route('users.index');
            } else {
                // or we redirect the current user to the home page
                return redirect()->route('dashboard.index');
            }
        }

        // we get the user
        if (!$user = \Sentinel::findById($id)) {
            Modal::alert([
                trans('users.message.find.failure', ['id' => $id]),
                trans('global.message.global.failure.contact.support', ['email' => config('settings.support_email')]),
            ], 'error');

            // we redirect the current user to the countries list if he has the required permission
            if (Sentinel::getUser()->hasAccess('users.list')) {
                return redirect()->route('users.index');
            } else {
                // or we redirect the current user to the home page
                return redirect()->route('dashboard.index');
            }
        }

        // we check if the current user has a role position high enough to edit the user
        $edited_user_role = $user->roles->first();
        $current_user_role = \Sentinel::getUser()->roles->first();
        if ($edited_user_role && $current_user_role && $edited_user_role->position < $current_user_role->position) {
            Modal::alert([
                trans('users.message.permission.denied', ['action' => trans('users.message.permission.action.edit')]),
            ], 'error');

            return redirect()->back();
        }

        // SEO Meta settings
        $this->seoMeta['page_title'] = trans('seo.back.users.edit');

        // we convert the database date to the fr/en format
        if ($birth_date = $user->birth_date) {
            $user->birth_date = Carbon::createFromFormat('Y-m-d', $birth_date)->format('d/m/Y');
        }

        // we prepare the data for breadcrumbs
        $breadcrumbs_data = [
            'user' => $user,
        ];

        // prepare data for the view
        $data = [
            'seoMeta'          => $this->seoMeta,
            'user'             => $user,
            'statuses'         => config('user.status'),
            'boards'           => config('user.board'),
            'roles'            => \Sentinel::getRoleRepository()->orderBy('position', 'asc')->get(),
            'breadcrumbs_data' => $breadcrumbs_data,
        ];

        // return the view with data
        return view('pages.back.user-edit')->with($data);
    }

    public function profile()
    {
        // SEO Meta settings
        $this->seoMeta['page_title'] = trans('seo.back.users.profile');

        // we get the current user
        $user = \Sentinel::getUser();

        // we convert the database date to the fr/en format
        if ($birth_date = $user->birth_date) {
            $user->birth_date = Carbon::createFromFormat('Y-m-d', $birth_date)->format('d/m/Y');
        }

        // prepare data for the view
        $data = [
            'seoMeta'  => $this->seoMeta,
            'user'     => $user,
            'statuses' => config('user.status'),
            'boards'   => config('user.board'),
            'roles'    => \Sentinel::getRoleRepository()->all(),
        ];

        // return the view with data
        return view('pages.back.user-edit')->with($data);
    }

    public function update($id, Request $request)
    {
        // we get the user
        if (!$user = Sentinel::findById($id)) {
            Modal::alert([
                trans('users.message.find.failure', ['id' => $id]),
                trans('global.message.global.failure.contact.support', ['email' => config('settings.support_email')]),
            ], 'error');

            // we redirect the current user to the users list if he has the required permission
            if (Sentinel::getUser()->hasAccess('users.list')) {
                return redirect()->route('users.index');
            } else {
                // or we redirect the current user to the home page
                return redirect()->route('dashboard.index');
            }
        }

        // if the updated user is not the current user
        if ($user->id !== Sentinel::getUser()->id) {
            // we check the current user permission
            if (!Permission::hasPermission('users.update')) {
                // we redirect the current user to the user list if he has the required permission
                if (Sentinel::getUser()->hasAccess('users.view')) {
                    return redirect()->route('users.edit', ['id' => $id]);
                } else {
                    // or we redirect the current user to the home page
                    return redirect()->route('dashboard.index');
                }
            }
        }

        // we check if the current user has a role position high enough to edit the user
        $edited_user_role = $user->roles->first();
        $current_user_role = Sentinel::getUser()->roles->first();
        if ($edited_user_role && $current_user_role && $edited_user_role->position < $current_user_role->position) {
            Modal::alert([
                trans('users.message.permission.denied', ['action' => trans('users.message.permission.action.edit')]),
            ], 'error');

            return redirect()->back();
        }

        // we check if the chosen role is not higher than the role of the current user
        $new_user_role = Sentinel::findRoleById($request->get('role'));
        $current_user_role = Sentinel::getUser()->roles->first();
        if ($new_user_role && $current_user_role && $new_user_role->position < $current_user_role->position) {
            // we flash the request
            $request->flashExcept('photo');

            // we notify the user
            Modal::alert([
                trans('users.message.permission.denied', ['action' => trans('users.message.permission.action.edit')]),
            ], 'error');

            return redirect()->back();
        }

        // if the remove_photo field is not given, we set it to false
        $request->merge(['remove_photo' => $request->get('remove_photo', false)]);

        // we sanitize the entries
        $request->replace(\Entry::sanitizeAll($request->all()));

        try {
            // we convert the en/fr date to the database format
            if ($birth_date = $request->get('birth_date')) {
                $request->merge(['birth_date' => Carbon::createFromFormat('d/m/Y', $birth_date)->format('Y-m-d')]);
            }
        } catch (Exception $e) {
            // we log the error
            CustomLog::error($e);
        }

        // we set the validation rules
        $rules = [
            'photo'        => 'image|mimes:jpg,jpeg,png|image_size:>=145,>=160',
            'remove_photo' => 'required|boolean',
            'gender'       => 'in:' . implode(',', array_keys(config('user.gender'))),
            'last_name'    => 'required|string',
            'first_name'   => 'required|string',
            'birth_date'   => 'date_format:Y-m-d',
            'phone_number' => 'phone:FR',
            'email'        => 'required|email|unique:users,email,' . $id,
            'address'      => 'string',
            'zip_code'     => 'digits:5',
            'city'         => 'string',
            'country'      => 'string',
            'password'     => 'min:' . config('password.min.length') . '|confirmed',
        ];
        // according if we update the current profile account or a user profile
        if ($user->id !== Sentinel::getUser()->id) {
            $rules['role'] = 'required|numeric|exists:roles,id';
            $rules['active'] = 'boolean';
            $rules['status_id'] = 'required|numeric|in:' . implode(',', array_keys(config('user.status')));
            $rules['board_id'] = 'numeric|in:' . implode(',', array_keys(config('user.board')));
        }
        // we sort the rules by keys
        ksort($rules);
        // we check the inputs validity
        if (!Validation::check($request->all(), $rules)) {
            // we flash the request
            $request->flashExcept('photo');

            return redirect()->back();
        }

        try {
            // we update the user
            Sentinel::update($user, $request->except('password'));

            // we format the number into its international equivalent
            if ($phone_number = $request->get('phone_number')) {
                Sentinel::update($user, [
                    'phone_number' => phone_format(
                        $phone_number,
                        'FR',
                        PhoneNumberFormat::INTERNATIONAL
                    ),
                ]);
            }

            // we store the photo
            if ($photo = $request->get('photo')) {
                // we optimize, resize and save the image
                $file_name = ImageManager::optimizeAndResize(
                    $photo->getRealPath(),
                    $user->imageName('photo'),
                    $photo->getClientOriginalExtension(),
                    $user->storagePath(),
                    $user->availableSizes('photo')
                );
                // we update the user
                Sentinel::update($user, ['photo' => $file_name]);
            } elseif ((!$request->get('photo') && !$user->photo) || $request->get('remove_photo')) {
                // we remove the background image
                if (isset($user->photo)) {
                    ImageManager::remove(
                        $user->photo,
                        $user->storagePath(),
                        $user->availableSizes('photo')
                    );
                }
                // we set the una logo as the user image
                $file_name = ImageManager::optimizeAndResize(
                    database_path('seeds/files/settings/logo-una-dark.png'),
                    $user->imageName('photo'),
                    config('image.settings.logo.extension'),
                    $user->storagePath(),
                    $user->availableSizes('photo'),
                    false
                );
                // we update the user
                Sentinel::update($user, ['photo' => $file_name]);
            }

            // if we're updating the profile of another user
            if ($user->id !== \Sentinel::getUser()->id) {
                // we check is the user is attached to roles
                $current_roles = $user->roles;

                // we detach each roles found
                foreach ($current_roles as $role) {
                    $role->users()->detach($user);
                }

                // we attach the new role
                $role = \Sentinel::findRoleById($request->get('role'));
                $role->users()->attach($user);

                // if the order is to activate the user
                if ($request->get('active')) {
                    // we activate the user
                    if (!$activation = Activation::completed($user)) {
                        $activation = Activation::create($user);
                    }
                    Activation::complete($user, $activation->code);
                } else {
                    // or we deactivate him
                    Activation::remove($user);
                }

                // we notify the current user
                Modal::alert([
                    trans('users.message.update.success', ['name' => $user->first_name . ' ' . $user->last_name]),
                ], 'success');

                return redirect()->back();
            }

            // we notify the current user
            Modal::alert([
                trans('users.message.account.success'),
            ], 'success');

            return redirect()->back();
        } catch (Exception $e) {
            // we flash the request
            $request->flashExcept('photo');

            // we log the error
            CustomLog::error($e);

            // we notify the current user
            Modal::alert([
                trans('users.message.update.failure', ['name' => $user->first_name . ' ' . $user->last_name]),
                trans('global.message.global.failure.contact.support', ['email' => config('settings.support_email')]),
            ], 'error');

            return redirect()->back();
        }
    }

    public function destroy($id, Request $request)
    {
        // we check the current user permission
        if (!Permission::hasPermission('users.delete')) {
            // we redirect the current user to the user list if he has the required permission
            if (Sentinel::getUser()->hasAccess('users.list')) {
                return redirect()->route('users.index');
            } else {
                // or we redirect the current user to the home page
                return redirect()->route('dashboard.index');
            }
        }

        // we get the user
        if (!$user = \Sentinel::findById($id)) {
            Modal::alert([
                trans('users.message.find.failure', ['id' => $id]),
                trans('global.message.global.failure.contact.support', ['email' => config('settings.support_email')]),
            ], 'error');

            // we redirect the current user to the users list if he has the required permission
            if (Sentinel::getUser()->hasAccess('users.list')) {
                return redirect()->route('users.index');
            } else {
                // or we redirect the current user to the home page
                return redirect()->route('dashboard.index');
            }
        }

        // we check if the current user has a role position high enough to edit the user
        $edited_user_role = $user->roles->first();
        $current_user_role = \Sentinel::getUser()->roles->first();
        if ($edited_user_role && $current_user_role && $edited_user_role->position < $current_user_role->position) {
            Modal::alert([
                trans('users.message.permission.denied', ['action' => trans('users.message.permission.action.delete')]),
            ], 'error');

            return redirect()->back();
        }

        try {
            // we remove the users photos
            if ($user->photo) {
                ImageManager::remove(
                    $user->photo,
                    $user->storagePath(),
                    $user->availableSizes('photo')
                );
            }

            // we delete the role
            $user->delete();

            Modal::alert([
                trans('users.message.delete.success', ['name' => $user->first_name . ' ' . $user->last_name]),
            ], 'success');

            return redirect()->back();
        } catch (Exception $e) {
            // we log the error
            CustomLog::error($e);

            // we notify the current user
            Modal::alert([
                trans('users.message.delete.failure', ['name' => $user->first_name . ' ' . $user->last_name]),
                trans('global.message.global.failure.contact.support', ['email' => config('settings.support_email')]),
            ], 'error');

            return redirect()->back();
        }
    }

    public function activate($id, Request $request)
    {
        // we check the current user permission
        if ($permission_denied = Permission::hasPermissionJson('users.update')) {
            return response([
                'message' => [$permission_denied],
            ], 401);
        }

        // we get the user
        if (!$user = \Sentinel::findById($id)) {
            return response([
                'message' => [
                    trans('users.message.find.failure', ['id' => $id]),
                    trans('global.message.global.failure.contact.support', ['email' => config('settings.support_email'),]),
                ],
            ], 401);
        }

        // we check if the current user has a role position high enough to edit the user
        $edited_user_role = $user->roles->first();
        $current_user_role = \Sentinel::getUser()->roles->first();
        if ($edited_user_role && $current_user_role && $edited_user_role->position < $current_user_role->position) {
            return response([
                trans('users.message.permission.denied', ['action' => trans('users.message.permission.action.edit')]),
            ], 401);
        }

        // we sanitize the entries
        $request->replace(\Entry::sanitizeAll($request->all()));

        // we set the active value to false if we do not find it in the request params
        if (!$request->get('active')) {
            $request->merge(['active' => false]);
        }

        // we check the inputs validity
        $rules = [
            'active' => 'required|boolean',
        ];
        if (is_array($errors = Validation::check($request->all(), $rules, true))) {
            return response([
                'active'  => Activation::completed($user) ? Activation::completed($user)->completed : Activation::completed($user),
                'message' => $errors,
            ], 401);
        }

        try {
            // if the order is given to activate the user
            if ($request->get('active')) {
                // we activate the user
                if (!$activation = Activation::completed($user)) {
                    $activation = Activation::create($user);
                }
                Activation::complete($user, $activation->code);
            } else {
                Activation::remove($user);
            }

            $active = Activation::completed($user) ? Activation::completed($user)->completed : Activation::completed($user);

            return response([
                'active'  => $active,
                'message' => [
                    trans('users.message.activation.success.label', ['action' => trans_choice('users.message.activation.success.action', $active), 'name' => $user->first_name . ' ' . $user->last_name]),
                ],
            ], 200);
        } catch (Exception $e) {
            // we log the error
            CustomLog::error($e);

            return response([
                'active'  => Activation::completed($user) ? Activation::completed($user)->completed : Activation::completed($user),
                'message' => [
                    trans('users.message.activation.failure', ['name' => $user->first_name . ' ' . $user->last_name]),
                    trans('global.message.global.failure.contact.support', ['email' => config('settings.support_email'),]),
                ],
            ], 401);
        }
    }
}
