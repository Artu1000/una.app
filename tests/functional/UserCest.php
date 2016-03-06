<?php

use Carbon\Carbon;

class UserCest
{
    private $_user;
    private $_credentials;

    public function _before(FunctionalTester $I)
    {
        // we create a user
        $this->_credentials = [
            'gender'       => config('user.gender_key.male'),
            'last_name'    => 'Test',
            'first_name'   => 'test',
            'birth_date'   => '1985-03-24',
            'phone_number' => '+33 6 66 66 66 66',
            'email'        => 'test@test.fr',
            'address'      => '7 impasse du Taureau Ailé',
            'zip_code'     => 44300,
            'city'         => 'Nantes',
            'country'      => 'France',
            'status_id'    => config('user.status_key.communication_commission'),
            'board_id'     => config('user.board_key.leading_board'),
            'password'     => 'test',
        ];
        $this->_user = \Sentinel::register($this->_credentials, true);

        // we log this user
        \Sentinel::authenticate($this->_credentials);
    }

    public function _after(FunctionalTester $I)
    {
    }

    private function _createAdminRole()
    {
        // we create the admin role
        $admin_role = \Sentinel::getRoleRepository()->createModel()->create([
            'slug'     => 'admin',
            'position' => 1,
        ]);
        // we translate the translatable fields
        $admin_role->translateOrNew('fr')->name = 'Administrateur';
        $admin_role->translateOrNew('en')->name = 'Administrator';
        // we give all permissions to the admin role
        $permissions = [];
        foreach (array_dot(config('permissions')) as $permission => $value) {
            $permissions[$permission] = true;
        }
        $admin_role->permissions = $permissions;
        // we save the changes
        $admin_role->save();

        return $admin_role;
    }

    private function _createUserRole()
    {
        // we create the admin role
        $user_role = \Sentinel::getRoleRepository()->createModel()->create([
            'slug'     => 'user',
            'position' => 2,
        ]);
        // we translate the translatable fields
        $user_role->translateOrNew('fr')->name = 'Utilisateur';
        $user_role->translateOrNew('en')->name = 'User';
        // we give all permissions to the admin role
        $permissions = [];
        foreach (array_dot(config('permissions')) as $permission => $value) {
            $permissions[$permission] = true;
        }
        $user_role->permissions = $permissions;
        // we save the changes
        $user_role->save();

        return $user_role;
    }


    /**<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<
     * -----------------------------------------------------------------------------------------------------------------
     * TESTS
     * -----------------------------------------------------------------------------------------------------------------
     * >>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>*/

    public function access_to_user_list_without_permission(FunctionalTester $I)
    {
        $I->am('A user with no role');
        $I->wantTo('access to the users list without the permission');
        $I->expectTo('see an error message explaining that I do not have the permission to access this page');

        /***************************************************************************************************************
         * run test
         **************************************************************************************************************/
        $I->amOnPage('/');
        $I->amOnRoute('users.index');
        $I->seeCurrentRouteIs('dashboard.index');
        $I->see(trans('global.modal.alert.title.error'), 'h3');
        $I->see(strip_tags(trans('permissions.message.access.denied', ['permission' => trans('permissions.users.list')])));
    }

    public function access_to_users_list(FunctionalTester $I)
    {
        $I->am('Admin');
        $I->wantTo('access to the users list');
        $I->expectTo('see the users list');

        /***************************************************************************************************************
         * settings
         **************************************************************************************************************/
        // we create an the admin role
        $admin_role = $this->_createAdminRole();
        // we attach it to the logged user
        $admin_role->users()->attach($this->_user);
        // we create another user
        $credentials = [
            'last_name'  => 'Autre',
            'first_name' => 'autre',
            'email'      => 'autre@autre.fr',
            'status_id'  => config('user.status_key.communication_commission'),
            'board_id'   => config('user.board_key.leading_board'),
            'password'   => 'autre',
        ];
        $user = \Sentinel::register($credentials, true);
        // we attach the user to the admin role
        $admin_role->users()->attach($user);

        /***************************************************************************************************************
         * run test
         **************************************************************************************************************/
        $I->amOnPage('/');
        $I->seeCurrentRouteIs('home');
        $I->click(trans('template.front.header.my_account'));
        $I->seeCurrentRouteIs('dashboard.index');
        $I->click(trans('template.back.header.users'));
        $I->seeCurrentRouteIs('users.index');
        $I->see(trans('breadcrumbs.admin'), '.breadcrumb');
        $I->seeLink(trans('breadcrumbs.admin'), route('dashboard.index'));
        $I->see(trans('users.page.title.management'));
        $I->see(trans('users.page.title.list'));
        $I->dontSee($this->_user->last_name, '.table-list');
        $I->dontSee($this->_user->first_name, '.table-list');
        $I->see($user->last_name, '.table-list');
        $I->see($user->first_name, '.table-list');
        $I->see($user->roles->first()->name, '.table-list');
        $I->see(trans('users.config.status.communication_commission'), '.table-list');
        $I->see(trans('users.config.board.leading_board'), '.table-list');
        $I->seeCheckboxIsChecked('#activate_' . $user->id);
        $I->see(strip_tags(trans('global.table_list.results.status', ['start' => 1, 'stop' => 1, 'total' => 1])));
    }

    public function set_number_of_lines(FunctionalTester $I)
    {
        $I->am('Admin');
        $I->wantTo('set the table lines number');
        $I->expectTo('see the number of line I chose');

        /***************************************************************************************************************
         * settings
         **************************************************************************************************************/
        // we create the admin role and attach the logged user to it
        $admin_role = $this->_createAdminRole();
        $admin_role->users()->attach($this->_user);
        // we create a new user
        $credentials = [
            'last_name'  => 'Autre',
            'first_name' => 'autre',
            'email'      => 'autre@autre.fr',
            'status_id'  => config('user.status_key.communication_commission'),
            'board_id'   => config('user.board_key.leading_board'),
            'password'   => 'autre',
        ];
        $user1 = \Sentinel::register($credentials, true);
        // we create another user
        $credentials = [
            'last_name'  => 'Other',
            'first_name' => 'other',
            'email'      => 'other@other.fr',
            'status_id'  => config('user.status_key.leisure_commission'),
            'board_id'   => config('user.board_key.executive_committee'),
            'password'   => 'other',
        ];
        $user2 = \Sentinel::register($credentials, true);

        /***************************************************************************************************************
         * run test
         **************************************************************************************************************/
        $I->amOnRoute('users.index');
        $I->see(strip_tags(trans('global.table_list.results.status', ['start' => 1, 'stop' => 2, 'total' => 2])));
        $I->see($user1->last_name, '.table-list');
        $I->see($user1->first_name, '.table-list');
        $I->see($user2->last_name, '.table-list');
        $I->see($user2->first_name, '.table-list');
        $I->seeInField('#input_lines', config('tablelist.default.lines'));
        $I->seeInField('#input_search', '');
        $I->fillField('#input_lines', 1);
        $I->submitForm('#line_search_form', []);
        $I->see($user1->last_name, '.table-list');
        $I->see($user1->first_name, '.table-list');
        $I->cantSee($user2->last_name, '.table-list');
        $I->cantSee($user2->first_name, '.table-list');
        $I->see(strip_tags(trans('global.table_list.results.status', ['start' => 1, 'stop' => 1, 'total' => 2])));
    }

    public function search_user(FunctionalTester $I)
    {
        $I->am('Admin');
        $I->wantTo('search a user');
        $I->expectTo('see the searched user');

        /***************************************************************************************************************
         * settings
         **************************************************************************************************************/
        // we create the admin role and attach the logged user to it
        $admin_role = $this->_createAdminRole();
        $admin_role->users()->attach($this->_user);
        // we create a new user
        $credentials = [
            'last_name'  => 'Autre',
            'first_name' => 'autre',
            'email'      => 'autre@autre.fr',
            'status_id'  => config('user.status_key.communication_commission'),
            'board_id'   => config('user.board_key.leading_board'),
            'password'   => 'autre',
        ];
        $user1 = \Sentinel::register($credentials, true);
        // we create another user
        $credentials = [
            'last_name'  => 'Other',
            'first_name' => 'other',
            'email'      => 'other@other.fr',
            'status_id'  => config('user.status_key.communication_commission'),
            'board_id'   => config('user.board_key.leading_board'),
            'password'   => 'other',
        ];
        $user2 = \Sentinel::register($credentials, true);

        /***************************************************************************************************************
         * run test
         **************************************************************************************************************/
        $I->amOnRoute('users.index');
        $I->see(strip_tags(trans('global.table_list.results.status', ['start' => 1, 'stop' => 2, 'total' => 2])));
        $I->see($user1->last_name, '.table-list');
        $I->see($user1->first_name, '.table-list');
        $I->see($user2->last_name, '.table-list');
        $I->see($user2->first_name, '.table-list');
        $I->seeInField('#input_lines', config('tablelist.default.lines'));
        $I->seeInField('#input_search', '');
        $I->fillField('#input_search', 'oth');
        $I->submitForm('#line_search_form', []);
        $I->cantSee($user1->last_name, '.table-list');
        $I->cantSee($user1->first_name, '.table-list');
        $I->see($user2->last_name, '.table-list');
        $I->see($user2->first_name, '.table-list');
        $I->see(strip_tags(trans('global.table_list.results.status', ['start' => 1, 'stop' => 1, 'total' => 1])));
    }

    public function access_to_user_add_page_without_permission(FunctionalTester $I)
    {
        $I->am('A user with no role');
        $I->wantTo('access to the users add page without the permission');
        $I->expectTo('see an error message explaining that I do not have the permission to access this page');

        /***************************************************************************************************************
         * run test
         **************************************************************************************************************/
        $I->amOnPage('/');
        $I->amOnRoute('users.create');
        $I->seeCurrentRouteIs('dashboard.index');
        $I->see(trans('global.modal.alert.title.error'), 'h3');
        $I->see(strip_tags(trans('permissions.message.access.denied', ['permission' => trans('permissions.users.create')])));
    }

    public function access_to_user_add_page(FunctionalTester $I)
    {
        $I->am('Admin');
        $I->wantTo('access to the users add page');
        $I->expectTo('see the users add page with blank fields');

        /***************************************************************************************************************
         * settings
         **************************************************************************************************************/
        // we create the admin role
        $admin_role = $this->_createAdminRole();
        // we attach it to the logged user
        $admin_role->users()->attach($this->_user);
        // we create the user role
        $this->_createUserRole();

        /***************************************************************************************************************
         * run test
         **************************************************************************************************************/
        $I->amOnPage('/');
        $I->seeCurrentRouteIs('home');
        $I->click(trans('template.front.header.my_account'));
        $I->seeCurrentRouteIs('dashboard.index');
        $I->click(trans('template.back.header.users'));
        $I->seeCurrentRouteIs('users.index');
        $I->click(trans('global.action.add'));
        $I->seeCurrentRouteIs('users.create');
        $I->see(trans('breadcrumbs.admin'), '.breadcrumb');
        $I->seeLink(trans('breadcrumbs.admin'), route('dashboard.index'));
        $I->see(strip_tags(trans('breadcrumbs.users.create')), '.breadcrumb');
        $I->seeLink(trans('breadcrumbs.users.index'), route('users.index'));
        $I->see(trans('users.page.title.create'), 'h2');
        $I->assertFileNotExists('photo');
        $I->dontSeeCheckboxIsChecked('gender');
        $I->seeInField('last_name', '');
        $I->seeInField('first_name', '');
        $I->seeInField('birth_date', '');
        $I->seeOptionIsSelected('#input_status_id', trans('users.config.status.user'));
        $I->seeOptionIsSelected('#input_board_id', trans('users.page.label.no_board'));
        $I->seeInField('phone_number', '');
        $I->seeInField('email', '');
        $I->seeInField('address', '');
        $I->seeInField('zip_code', '');
        $I->seeInField('city', '');
        $I->seeInField('country', '');
        $I->seeOptionIsSelected('role', \Sentinel::findRoleBySlug('user')->id);
        $I->seeCheckboxIsChecked('#input_active');
        $I->seeInField('password', '');
        $I->seeInField('password_confirmation', '');
    }

    public function access_to_user_add_page_and_cancel(FunctionalTester $I)
    {
        $I->am('Admin');
        $I->wantTo('access to the user add page and cancel');
        $I->expectTo('land on the user list page');

        /***************************************************************************************************************
         * settings
         **************************************************************************************************************/
        // we create the admin role
        $admin_role = $this->_createAdminRole();
        // we attach it to the logged user
        $admin_role->users()->attach($this->_user);
        // we create the user role
        $this->_createUserRole();

        /***************************************************************************************************************
         * run test
         **************************************************************************************************************/
        $I->amOnPage('/');
        $I->seeCurrentRouteIs('home');
        $I->click(trans('template.front.header.my_account'));
        $I->seeCurrentRouteIs('dashboard.index');
        $I->click(trans('template.back.header.users'));
        $I->seeCurrentRouteIs('users.index');
        $I->click(trans('global.action.add'));
        $I->seeCurrentRouteIs('users.create');
        $I->click(trans('global.action.cancel'));
        $I->seeCurrentRouteIs('users.index');
    }

    public function create_user_without_permission(FunctionalTester $I)
    {
        $I->am('A user with no role');
        $I->wantTo('create a new user without the permission');
        $I->expectTo('see an error message explaining that I do have the permission to create a new user');

        /***************************************************************************************************************
         * settings
         **************************************************************************************************************/
        // we create the admin role
        $admin_role = $this->_createAdminRole();
        // we remove all its permissions
        $admin_role->permissions = [];
        $admin_role->save();
        // we attach it to the logged user
        $admin_role->users()->attach($this->_user);
        // we create the user role
        $this->_createUserRole();

        /***************************************************************************************************************
         * run test
         **************************************************************************************************************/
        $this->_user->addPermission('users.create', true)->save();
        $I->amOnPage('/');
        $I->amOnRoute('users.create');
        $I->see(trans('users.page.title.create'));
        $this->_user->removePermission('users.create')->save();
        $I->click(trans('users.page.action.create'));
        $I->seeCurrentRouteIs('dashboard.index');
        $I->see(trans('global.modal.alert.title.error'), 'h3');
        $I->see(strip_tags(trans('permissions.message.access.denied', ['permission' => trans('permissions.users.create')])));
    }

    public function create_user_with_higher_role_than_mine(FunctionalTester $I)
    {
        $I->am('Admin');
        $I->wantTo('create a user which has a higher role than mine');
        $I->expectTo('see an error message explaining that I do not have the authorization');

        /***************************************************************************************************************
         * settings
         **************************************************************************************************************/
        // we create the user role
        $user_role = $this->_createUserRole();
        // we attach it to the logged user
        $user_role->users()->attach($this->_user);
        // we create another user
        $credentials = [
            'last_name'  => 'Autre',
            'first_name' => 'autre',
            'email'      => 'autre@autre.fr',
            'status_id'  => config('user.status_key.communication_commission'),
            'board_id'   => config('user.board_key.leading_board'),
            'password'   => 'autre',
        ];
        $user = \Sentinel::register($credentials, true);
        $user_role->users()->attach($user);
        // we create the admin role
        $admin_role = $this->_createAdminRole();

        /***************************************************************************************************************
         * run test
         **************************************************************************************************************/
        $I->amOnPage('/');
        $I->amOnRoute('users.create');
        $I->see(trans('users.page.title.create'));
        $I->selectOption('role', $admin_role->id);
        $I->checkOption('#input_active', true);
        $I->fillField('password', 'password');
        $I->fillField('password_confirmation', 'password');
        $I->click(trans('users.page.action.create'));

        $I->seeCurrentRouteIs('users.create');
        $I->see(trans('global.modal.alert.title.error'), 'h3');
        $I->see(trans('users.message.permission.denied', ['action' => trans('users.message.permission.action.create')]));
    }

    public function create_user_with_no_field_filled_from_form(FunctionalTester $I)
    {
        $I->am('Admin');
        $I->wantTo('create user with no fields filled from html form');
        $I->expectTo('see an error message explaining that some fields are missing');

        /***************************************************************************************************************
         * settings
         **************************************************************************************************************/
        // we create the admin role
        $admin_role = $this->_createAdminRole();
        $admin_role->users()->attach($this->_user);

        /***************************************************************************************************************
         * run test
         **************************************************************************************************************/
        $I->amOnPage('/');
        $I->amOnRoute('users.create');
        $I->see(trans('users.page.title.create'));
        $I->click(trans('users.page.action.create'));
        $I->seeCurrentRouteIs('users.create');
        $I->see(trans('global.modal.alert.title.error'), 'h3');
        $I->see(strip_tags(trans('validation.filled', ['attribute' => trans('validation.attributes.last_name')])));
        $I->see(strip_tags(trans('validation.filled', ['attribute' => trans('validation.attributes.first_name')])));
        $I->see(strip_tags(trans('validation.filled', ['attribute' => trans('validation.attributes.email')])));
        $I->see(strip_tags(trans('validation.filled', ['attribute' => trans('validation.attributes.password')])));
    }

    public function create_user_with_no_field_filled_from_ajax(FunctionalTester $I)
    {
        $I->am('Admin');
        $I->wantTo('create user with no fields filled from ajax request');
        $I->expectTo('see an error message explaining that some fields are missing');

        /***************************************************************************************************************
         * settings
         **************************************************************************************************************/
        // we create the admin role
        $admin_role = $this->_createAdminRole();
        $admin_role->users()->attach($this->_user);

        /***************************************************************************************************************
         * run test
         **************************************************************************************************************/
        $I->amOnPage('/');
        $I->amOnRoute('users.create');
        $I->sendAjaxRequest('POST', route('users.store', ['_token' => csrf_token()]));
        $I->seeCurrentRouteIs('users.create');
        $I->see(trans('global.modal.alert.title.error'));
        $I->see(strip_tags(trans('validation.filled', ['attribute' => trans('validation.attributes.last_name')])));
        $I->see(strip_tags(trans('validation.filled', ['attribute' => trans('validation.attributes.first_name')])));
        $I->see(strip_tags(trans('validation.filled', ['attribute' => trans('validation.attributes.status_id')])));
        $I->see(strip_tags(trans('validation.filled', ['attribute' => trans('validation.attributes.email')])));
        $I->see(strip_tags(trans('validation.filled', ['attribute' => trans('validation.attributes.role')])));
        $I->see(strip_tags(trans('validation.filled', ['attribute' => trans('validation.attributes.password')])));
    }

    public function create_user_with_wrong_values_filled_from_ajax(FunctionalTester $I)
    {
        $I->am('Admin');
        $I->wantTo('create a user with wrong values filled');
        $I->expectTo('see an error message explaining that the values are incorrect');

        /***************************************************************************************************************
         * settings
         **************************************************************************************************************/
        // we create the admin role
        $admin_role = $this->_createAdminRole();
        // we attach it to the logged user
        $admin_role->users()->attach($this->_user);

        /***************************************************************************************************************
         * run test
         **************************************************************************************************************/
        $I->amOnPage('/');
        $I->amOnRoute('users.create');
        $I->sendAjaxRequest('POST', route('users.store', [
            '_token'       => csrf_token(),
            'photo'        => 'image',
            'gender'       => 'test',
            'last_name'    => 123,
            'first_name'   => '456',
            'birth_date'   => 789,
            'status_id'    => 9999,
            'board_id'     => 8888,
            'phone_number' => 123456789,
            'email'        => 'email.com',
            'address'      => 987,
            'zip_code'     => 'thing',
            'city'         => 44200,
            'country'      => 654,
            'role'         => 'user',
            'active'       => 2,
            'password'     => 'short',
        ]));
        $I->seeCurrentRouteIs('users.create');
        $I->see(trans('global.modal.alert.title.error'));
        $I->see(strip_tags(trans('validation.image', ['attribute' => trans('validation.attributes.photo')])));
        $I->see(strip_tags(trans('validation.in', ['attribute' => trans('validation.attributes.gender')])));
        $I->see(strip_tags(trans('validation.string', ['attribute' => trans('validation.attributes.last_name')])));
        $I->see(strip_tags(trans('validation.string', ['attribute' => trans('validation.attributes.first_name')])));
        $I->see(strip_tags(trans('validation.date_format', ['attribute' => trans('validation.attributes.birth_date'), 'format' => 'Y-m-d'])));
        $I->see(strip_tags(trans('validation.in', ['attribute' => trans('validation.attributes.status_id')])));
        $I->see(strip_tags(trans('validation.in', ['attribute' => trans('validation.attributes.board_id')])));
        $I->see(strip_tags(trans('validation.email', ['attribute' => trans('validation.attributes.email')])));
        $I->see(strip_tags(trans('validation.string', ['attribute' => trans('validation.attributes.address')])));
        $I->see(strip_tags(trans('validation.digits', ['attribute' => trans('validation.attributes.zip_code'), 'digits' => 5])));
        $I->see(strip_tags(trans('validation.string', ['attribute' => trans('validation.attributes.city')])));
        $I->see(strip_tags(trans('validation.string', ['attribute' => trans('validation.attributes.country')])));
        $I->see(strip_tags(trans('validation.numeric', ['attribute' => trans('validation.attributes.role')])));
        $I->see(strip_tags(trans('validation.min.string', ['attribute' => trans('validation.attributes.password'), 'min' => config('password.min.length')])));
        $I->see(strip_tags(trans('validation.boolean', ['attribute' => trans('validation.attributes.active')])));
        $I->see(strip_tags(trans('validation.confirmed', ['attribute' => trans('validation.attributes.password')])));
    }

    public function create_user(FunctionalTester $I)
    {
        $I->am('Admin');
        $I->wantTo('create a user');
        $I->expectTo('see a success confirmation message');

        /***************************************************************************************************************
         * settings
         **************************************************************************************************************/
        // we create the admin role
        $admin_role = $this->_createAdminRole();
        // we attach it to the logged user
        $admin_role->users()->attach($this->_user);
        // we create the user role
        $user_role = $this->_createUserRole();

        /***************************************************************************************************************
         * run test
         **************************************************************************************************************/
        $I->amOnPage('/');
        $I->amOnRoute('users.create');
        $I->see(trans('users.page.title.create'));
        $I->selectOption('gender', config('user.gender_key.male'));
        $I->fillField('last_name', 'TEST');
        $I->fillField('first_name', 'Test');
        $I->fillField('birth_date', '24/03/1985');
        $I->selectOption('#input_status_id', trans('users.config.status.president'));
        $I->selectOption('#input_board_id', config('user.board_key.leading_board'));
        $I->fillField('phone_number', '0606070708');
        $I->fillField('email', 'test@email.fr');
        $I->fillField('address', '2 rue de la Houssinière');
        $I->fillField('zip_code', 44300);
        $I->fillField('city', 'Nantes');
        $I->fillField('country', 'France');
        $I->selectOption('role', Sentinel::findRoleBySlug('admin')->id);
        $I->uncheckOption('#input_active');
        $I->fillField('password', 'password');
        $I->fillField('password_confirmation', 'password');
        $I->click(trans('users.page.action.create'));
        $I->seeCurrentRouteIs('users.index');
        $I->see(trans('global.modal.alert.title.success'), 'h3');
        $I->see(strip_tags(trans('users.message.creation.success', ['name' => 'Test TEST'])));
        $created_user = Sentinel::getUserRepository()->findByCredentials(['email' => 'test@email.fr']);
        $I->see('TEST', '.table-list');
        $I->see('Test', '.table-list');
        $I->see(trans('users.config.status.president'), '.table-list');
        $I->see(trans('users.config.board.leading_board'), '.table-list');
        $I->see(Sentinel::findRoleBySlug('admin')->name, '.table-list');
        $I->dontSeeCheckboxIsChecked('#activate_' . $created_user->id);
        $I->seeRecord('users', [
            'last_name'    => 'TEST',
            'first_name'   => 'Test',
            'birth_date'   => '1985-03-24',
            'status_id'    => config('user.status_key.president'),
            'board_id'     => config('user.board_key.leading_board'),
            'gender'       => config('user.gender_key.male'),
            'phone_number' => '+33 6 06 07 07 08',
            'email'        => 'test@email.fr',
            'address'      => '2 rue de la Houssinière',
            "zip_code"     => 44300,
            "city"         => 'Nantes',
            "country"      => 'France',
        ]);
        $I->seeRecord('role_users', [
            'user_id' => $created_user->id,
            'role_id' => Sentinel::findRoleBySlug('admin')->id,
        ]);
        $I->dontSeeRecord('activations', [
            'user_id'   => $created_user->id,
            'completed' => true,
        ]);
        $I->assertTrue(Hash::check('password', $created_user->password));
    }

    public function access_to_user_edit_page_without_permission(FunctionalTester $I)
    {
        $I->am('A user with no role');
        $I->wantTo('access to the users edit page');
        $I->expectTo('see an error message explaining that I do not have the authorization');

        /***************************************************************************************************************
         * settings
         **************************************************************************************************************/
        // we create the user role
        $user_role = $this->_createUserRole();
        // we create another user
        $credentials = [
            'last_name'  => 'Autre',
            'first_name' => 'autre',
            'email'      => 'autre@autre.fr',
            'status_id'  => config('user.status_key.communication_commission'),
            'board_id'   => config('user.board_key.leading_board'),
            'password'   => 'autre',
        ];
        $user = \Sentinel::register($credentials, true);
        $user_role->users()->attach($user);

        /***************************************************************************************************************
         * run test
         **************************************************************************************************************/
        $I->amOnPage('/');
        $I->amOnRoute('users.edit', ['id' => $user->id]);
        $I->seeCurrentRouteIs('dashboard.index');
        $I->see(trans('global.modal.alert.title.error'), 'h3');
        $I->see(strip_tags(trans('permissions.message.access.denied', ['permission' => trans('permissions.users.view')])));
    }

    public function access_to_user_edit_page_with_wrong_id(FunctionalTester $I)
    {
        $I->am('Admin');
        $I->wantTo('access to user edit page with a wrong id');
        $I->expectTo('see an error message explaining the user doesn\'t exists and be redirected to the users list');

        /***************************************************************************************************************
         * settings
         **************************************************************************************************************/
        // we create the admin role
        $admin_role = $this->_createAdminRole();
        // we attach it to the logged user
        $admin_role->users()->attach($this->_user);

        /***************************************************************************************************************
         * run test
         **************************************************************************************************************/
        $I->amOnPage('/');
        $I->amOnRoute('users.edit', ['id' => 1000000000000]);
        $I->seeCurrentRouteIs('users.index');
        $I->see(trans('global.modal.alert.title.error'), 'h3');
        $I->see(strip_tags(trans('users.message.find.failure', ['id' => 1000000000000])));
    }

    public function access_to_user_edit_page(FunctionalTester $I)
    {
        $I->am('Admin');
        $I->wantTo('access to user edit page');
        $I->expectTo('see the user edit page with fields filled with the user data');

        /***************************************************************************************************************
         * settings
         **************************************************************************************************************/
        // we create the admin role
        $admin_role = $this->_createAdminRole();
        // we attach it to the logged user
        $admin_role->users()->attach($this->_user);
        // we create the user role
        $user_role = $this->_createUserRole();
        // we create another user
        $credentials = [
            'last_name'    => 'Autre',
            'first_name'   => 'autre',
            'gender'       => config('user.gender_key.male'),
            'birth_date'   => '2015-03-24',
            'status_id'    => config('user.status_key.communication_commission'),
            'board_id'     => config('user.board_key.leading_board'),
            'phone_number' => '0102030405',
            'email'        => 'autre@autre.fr',
            'address'      => '2 rue de la Houssinière',
            'zip_code'     => 44300,
            'city'         => 'Nantes',
            'country'      => 'France',
            'password'     => 'autre',
        ];
        $user = \Sentinel::register($credentials);
        $admin_role->users()->attach($user);

        /***************************************************************************************************************
         * run test
         **************************************************************************************************************/
        $I->amOnPage('/');
        $I->seeCurrentRouteIs('home');
        $I->click(trans('template.front.header.my_account'));
        $I->seeCurrentRouteIs('dashboard.index');
        $I->click(trans('template.back.header.users'));
        $I->seeCurrentRouteIs('users.index');
        $I->click('edit_' . $user->id);
        $I->seeCurrentRouteIs('users.edit', ['id' => $user->id]);
        $I->see(trans('breadcrumbs.admin'), '.breadcrumb');
        $I->seeLink(trans('breadcrumbs.admin'), route('dashboard.index'));
        $I->see(strip_tags(trans('breadcrumbs.users.edit', ['user' => $user->first_name . ' ' . $user->last_name])), '.breadcrumb');
        $I->seeLink(trans('breadcrumbs.users.index'), route('users.index'));
        $I->see(strip_tags(trans('users.page.title.edit', ['user' => $user->first_name . ' ' . $user->last_name])), 'h2');
        $I->seeOptionIsSelected('gender', config('user.gender_key.male'));
        $I->seeInField('last_name', 'Autre');
        $I->seeInField('first_name', 'autre');
        $I->seeInField('birth_date', '24/03/2015');
        $I->seeOptionIsSelected('#input_status_id', trans('users.config.status.communication_commission'));
        $I->seeOptionIsSelected('#input_board_id', trans('users.config.board.leading_board'));
        $I->seeInField('phone_number', '0102030405');
        $I->seeInField('email', 'autre@autre.fr');
        $I->seeInField('address', '2 rue de la Houssinière');
        $I->seeInField('zip_code', 44300);
        $I->seeInField('city', 'Nantes');
        $I->seeInField('country', 'France');
        $I->seeOptionIsSelected('role', \Sentinel::findRoleBySlug('admin')->id);
        $I->dontSeeCheckboxIsChecked('#input_active');
        $I->seeInField('password', '');
        $I->seeInField('password_confirmation', '');
    }

    public function access_to_user_edit_page_and_go_back(FunctionalTester $I)
    {
        $I->am('Admin');
        $I->wantTo('access to the user edit page and go back');
        $I->expectTo('land on the user list page');

        /***************************************************************************************************************
         * settings
         **************************************************************************************************************/
        // we create the admin role
        $admin_role = $this->_createAdminRole();
        // we attach it to the logged user
        $admin_role->users()->attach($this->_user);
        // we create the user role
        $user_role = $this->_createUserRole();
        // we create another user
        $credentials = [
            'last_name'  => 'Autre',
            'first_name' => 'autre',
            'email'      => 'autre@autre.fr',
            'password'   => 'autre',
        ];
        $user = \Sentinel::register($credentials, true);
        $admin_role->users()->attach($user);

        /***************************************************************************************************************
         * run test
         **************************************************************************************************************/
        $I->amOnPage('/');
        $I->seeCurrentRouteIs('home');
        $I->click(trans('template.front.header.my_account'));
        $I->seeCurrentRouteIs('dashboard.index');
        $I->click(trans('template.back.header.users'));
        $I->seeCurrentRouteIs('users.index');
        $I->click('edit_' . $user->id);
        $I->seeCurrentRouteIs('users.edit', ['id' => $user->id]);
        $I->click(trans('global.action.back'), '#content');
        $I->seeCurrentRouteIs('users.index');
    }

    public function access_to_edit_page_of_user_with_higher_role(FunctionalTester $I)
    {
        $I->am('Admin');
        $I->wantTo('access to user edit page which has a higher role than mine');
        $I->expectTo('see an error message explaining that I do not have the authorization');

        /***************************************************************************************************************
         * settings
         **************************************************************************************************************/
        // we create the user role
        $user_role = $this->_createUserRole();
        // we attach it the the logged user
        $user_role->users()->attach($this->_user);
        // we create another user
        $credentials = [
            'last_name'  => 'Autre',
            'first_name' => 'autre',
            'email'      => 'autre@autre.fr',
            'password'   => 'autre',
        ];
        $user = \Sentinel::register($credentials, true);
        // we create the admin role
        $admin_role = $this->_createAdminRole();
        // we attach it to the new user
        $admin_role->users()->attach($user);

        /***************************************************************************************************************
         * run test
         **************************************************************************************************************/
        $I->amOnPage('/');
        $I->seeCurrentRouteIs('home');
        $I->click(trans('template.front.header.my_account'));
        $I->seeCurrentRouteIs('dashboard.index');
        $I->click(trans('template.back.header.users'));
        $I->seeCurrentRouteIs('users.index');
        $I->click('edit_' . $user->id);
        $I->seeCurrentRouteIs('users.index');
        $I->see(trans('global.modal.alert.title.error'), 'h3');
        $I->see(trans('users.message.permission.denied', ['action' => trans('users.message.permission.action.edit')]));
    }

    public function update_user_without_permission(FunctionalTester $I)
    {
        $I->am('A user with no role');
        $I->wantTo('update a user without the permission');
        $I->expectTo('see an error message explaining that I do not have the permission to update the user');

        /***************************************************************************************************************
         * settings
         **************************************************************************************************************/
        // we create the admin role
        $admin_role = $this->_createAdminRole();
        // we remove all its permissions
        $admin_role->permissions = [];
        $admin_role->save();
        // we attach it to the logged user
        $admin_role->users()->attach($this->_user);
        // we create the user role
        $user_role = $this->_createUserRole();
        // we create another user
        $credentials = [
            'last_name'  => 'Autre',
            'first_name' => 'autre',
            'email'      => 'autre@autre.fr',
            'password'   => 'autre',
        ];
        $user = \Sentinel::register($credentials, true);
        $user_role->users()->attach($user);

        /***************************************************************************************************************
         * run test
         **************************************************************************************************************/
        $this->_user->addPermission('users.view', true)->save();
        $I->amOnPage('/');
        $I->amOnRoute('users.edit', ['id' => $user->id]);
        $I->see(strip_tags(trans('users.page.title.edit', ['user' => $user->first_name . ' ' . $user->last_name])));
        $I->click(trans('users.page.action.update'));
        $I->seeCurrentRouteIs('users.edit', ['id' => $user->id]);
        $I->see(trans('global.modal.alert.title.error'), 'h3');
        $I->see(strip_tags(trans('permissions.message.access.denied', ['permission' => trans('permissions.users.update')])));
    }

    public function update_user_with_wrong_id(FunctionalTester $I)
    {
        $I->am('A user with no role');
        $I->wantTo('update a user with a wrong id');
        $I->expectTo('see an error message explaining the user doesn\'t exists and be redirected to the users list');

        /***************************************************************************************************************
         * settings
         **************************************************************************************************************/
        // we create the admin role
        $admin_role = $this->_createAdminRole();
        // we attach it to the logged user
        $admin_role->users()->attach($this->_user);
        // we create the user role
        $user_role = $this->_createUserRole();
        // we create another user
        $credentials = [
            'last_name'  => 'Autre',
            'first_name' => 'autre',
            'email'      => 'autre@autre.fr',
            'password'   => 'autre',
        ];
        $user = \Sentinel::register($credentials, true);
        $user_role->users()->attach($user);

        /***************************************************************************************************************
         * run test
         **************************************************************************************************************/
        $I->amOnPage('/');
        $I->amOnRoute('users.edit', ['id' => $user->id]);
        $I->sendAjaxRequest('POST', route('users.update', ['id' => 1000000000000, '_token' => csrf_token(), '_method' => 'PUT']));
        $I->seeCurrentRouteIs('users.index');
        $I->see(trans('global.modal.alert.title.error'));
        $I->see(strip_tags(trans('users.message.find.failure', ['id' => 1000000000000])));
    }

    public function update_user_with_no_field_filled_from_ajax(FunctionalTester $I)
    {
        $I->am('Admin');
        $I->wantTo('update user with no fields filled from ajax request');
        $I->expectTo('see an error message explaining that some fields are missing');

        /***************************************************************************************************************
         * settings
         **************************************************************************************************************/
        // we create the admin role
        $admin_role = $this->_createAdminRole();
        // we attach it to the logged user
        $admin_role->users()->attach($this->_user);
        // we create the user role
        $user_role = $this->_createUserRole();
        // we create another user
        $credentials = [
            'last_name'  => 'Autre',
            'first_name' => 'autre',
            'email'      => 'autre@autre.fr',
            'password'   => 'autre',
        ];
        $user = \Sentinel::register($credentials, true);
        $user_role->users()->attach($user);

        /***************************************************************************************************************
         * run test
         **************************************************************************************************************/
        $I->amOnPage('/');
        $I->amOnRoute('users.edit', ['id' => $user->id]);
        $I->sendAjaxRequest('POST', route('users.update', [
            'id'      => $user->id,
            '_token'  => csrf_token(),
            '_method' => 'PUT',
        ]));
        $I->seeCurrentRouteIs('users.edit', ['id' => $user->id]);
        $I->see(trans('global.modal.alert.title.error'));
        $I->see(strip_tags(trans('validation.filled', ['attribute' => trans('validation.attributes.last_name')])));
        $I->see(strip_tags(trans('validation.filled', ['attribute' => trans('validation.attributes.first_name')])));
        $I->see(strip_tags(trans('validation.filled', ['attribute' => trans('validation.attributes.status_id')])));
        $I->see(strip_tags(trans('validation.filled', ['attribute' => trans('validation.attributes.email')])));
        $I->see(strip_tags(trans('validation.filled', ['attribute' => trans('validation.attributes.role')])));
        $I->dontSee(strip_tags(trans('validation.filled', ['attribute' => trans('validation.attributes.password')])));
    }

    public function update_user_with_no_change(FunctionalTester $I)
    {
        $I->am('Admin');
        $I->wantTo('update user with no change on fields');
        $I->expectTo('see a success confirmation message');

        /***************************************************************************************************************
         * settings
         **************************************************************************************************************/
        // we create the admin role
        $admin_role = $this->_createAdminRole();
        // we attach it to the logged user
        $admin_role->users()->attach($this->_user);
        // we create the user role
        $user_role = $this->_createUserRole();
        // we create another user
        $credentials = [
            'last_name'    => 'Autre',
            'first_name'   => 'autre',
            'gender'       => config('user.gender_key.male'),
            'birth_date'   => '2015-03-24',
            'status_id'    => config('user.status_key.communication_commission'),
            'board_id'     => config('user.board_key.leading_board'),
            'phone_number' => '0102030405',
            'email'        => 'autre@autre.fr',
            'address'      => '2 rue de la Houssinière',
            'zip_code'     => 44300,
            'city'         => 'Nantes',
            'country'      => 'France',
            'password'     => 'autre',
        ];
        $user = \Sentinel::register($credentials);
        $admin_role->users()->attach($user);

        /***************************************************************************************************************
         * run test
         **************************************************************************************************************/
        $I->amOnPage('/');
        $I->amOnRoute('users.edit', ['id' => $user->id]);
        $I->click(trans('users.page.action.update'));
        $I->seeCurrentRouteIs('users.edit', ['id' => $user->id]);
        $I->see(trans('global.modal.alert.title.success'), 'h3');
        $I->see(strip_tags(trans('users.message.update.success', ['name' => $user->first_name . ' ' . $user->last_name])));
        $I->seeInField('last_name', 'Autre');
        $I->seeInField('first_name', 'autre');
        $I->seeOptionIsSelected('gender', config('user.gender_key.male'));
        $I->seeInField('birth_date', '24/03/2015');
        $I->seeOptionIsSelected('#input_status_id', trans('users.config.status.communication_commission'));
        $I->seeOptionIsSelected('#input_board_id', trans('users.config.board.leading_board'));
        $I->seeInField('phone_number', '+33 1 02 03 04 05');
        $I->seeInField('email', 'autre@autre.fr');
        $I->seeInField('address', '2 rue de la Houssinière');
        $I->seeInField('zip_code', 44300);
        $I->seeInField('city', 'Nantes');
        $I->seeInField('country', 'France');
        $I->seeOptionIsSelected('role', \Sentinel::findRoleBySlug('admin')->id);
        $I->dontSeeCheckboxIsChecked('#input_active');
        $I->seeRecord('users', [
            'last_name'    => 'Autre',
            'first_name'   => 'autre',
            'gender'       => config('user.gender_key.male'),
            'birth_date'   => '2015-03-24',
            'status_id'    => config('user.status_key.communication_commission'),
            'board_id'     => config('user.board_key.leading_board'),
            'phone_number' => '+33 1 02 03 04 05',
            'email'        => 'autre@autre.fr',
            'address'      => '2 rue de la Houssinière',
            'zip_code'     => 44300,
            'city'         => 'Nantes',
            'country'      => 'France',
        ]);
        $I->seeRecord('role_users', [
            'user_id' => $this->_user->id,
            'role_id' => Sentinel::findRoleBySlug('admin')->id,
        ]);
        $I->dontSeeRecord('activations', [
            'user_id'   => $user->id,
            'completed' => true,
        ]);
        $user = $user->fresh();
        $I->assertTrue(Hash::check('autre', $user->password));
    }


    public function update_user_with_wrong_values_filled_from_ajax(FunctionalTester $I)
    {
        $I->am('Admin');
        $I->wantTo('update a user with no fields filled form');
        $I->expectTo('see an error message explaining that the data are not correct');

        /***************************************************************************************************************
         * settings
         **************************************************************************************************************/
        // we create the admin role
        $admin_role = $this->_createAdminRole();
        // we attach it to the logged user
        $admin_role->users()->attach($this->_user);
        // we create another user
        $credentials = [
            'last_name'  => 'Autre',
            'first_name' => 'autre',
            'email'      => 'autre@autre.fr',
            'password'   => 'autre',
        ];
        $user = \Sentinel::register($credentials, true);
        $admin_role->users()->attach($user);

        /***************************************************************************************************************
         * run test
         **************************************************************************************************************/
        $I->amOnPage('/');
        $I->amOnRoute('users.edit', ['id' => $user->id]);
        $I->seeCurrentRouteIs('users.edit', ['id' => $user->id]);
        $I->sendAjaxRequest('POST', route('users.update', [
            'id'           => $user->id,
            '_method'      => 'PUT',
            '_token'       => csrf_token(),
            'photo'        => 'image',
            'gender'       => 'test',
            'last_name'    => 123,
            'first_name'   => '456',
            'birth_date'   => 789,
            'status_id'    => 9999,
            'board_id'     => 8888,
            'phone_number' => 123456789,
            'email'        => 'email.com',
            'address'      => 987,
            'zip_code'     => 'thing',
            'city'         => 44200,
            'country'      => 654,
            'role'         => 'user',
            'active'       => 2,
            'password'     => 'short',
        ]));
        $I->seeCurrentRouteIs('users.edit', ['id' => $user->id]);
        $I->see(trans('global.modal.alert.title.error'));
        $I->see(strip_tags(trans('validation.image', ['attribute' => trans('validation.attributes.photo')])));
        $I->see(strip_tags(trans('validation.in', ['attribute' => trans('validation.attributes.gender')])));
        $I->see(strip_tags(trans('validation.string', ['attribute' => trans('validation.attributes.last_name')])));
        $I->see(strip_tags(trans('validation.string', ['attribute' => trans('validation.attributes.first_name')])));
        $I->see(strip_tags(trans('validation.date_format', ['attribute' => trans('validation.attributes.birth_date'), 'format' => 'Y-m-d'])));
        $I->see(strip_tags(trans('validation.in', ['attribute' => trans('validation.attributes.status_id')])));
        $I->see(strip_tags(trans('validation.in', ['attribute' => trans('validation.attributes.board_id')])));
        $I->see(strip_tags(trans('validation.email', ['attribute' => trans('validation.attributes.email')])));
        $I->see(strip_tags(trans('validation.string', ['attribute' => trans('validation.attributes.address')])));
        $I->see(strip_tags(trans('validation.digits', ['attribute' => trans('validation.attributes.zip_code'), 'digits' => 5])));
        $I->see(strip_tags(trans('validation.string', ['attribute' => trans('validation.attributes.city')])));
        $I->see(strip_tags(trans('validation.string', ['attribute' => trans('validation.attributes.country')])));
        $I->see(strip_tags(trans('validation.numeric', ['attribute' => trans('validation.attributes.role')])));
        $I->see(strip_tags(trans('validation.min.string', ['attribute' => trans('validation.attributes.password'), 'min' => config('password.min.length')])));
        $I->see(strip_tags(trans('validation.boolean', ['attribute' => trans('validation.attributes.active')])));
        $I->see(strip_tags(trans('validation.confirmed', ['attribute' => trans('validation.attributes.password')])));
    }

    public function update_user_with_data_changed(FunctionalTester $I)
    {
        $I->am('Admin');
        $I->wantTo('update user data');
        $I->expectTo('see a success confirmation message and see that the user data has changed');

        /***************************************************************************************************************
         * settings
         **************************************************************************************************************/
        // we create the admin role
        $admin_role = $this->_createAdminRole();
        // we attach it to the logged user
        $admin_role->users()->attach($this->_user);
        // we create the user role
        $user_role = $this->_createUserRole();
        // we create another user
        $credentials = [
            'last_name'    => 'TEST',
            'first_name'   => 'Test',
            'birth_date'   => '1985-03-24',
            'status_id'    => config('user.status_key.president'),
            'board_id'     => config('user.board_key.leading_board'),
            'gender'       => config('user.gender_key.male'),
            'phone_number' => '0102030405',
            'email'        => 'test@email.fr',
            'address'      => '2 rue de la Houssinière',
            'zip_code'     => 44300,
            'city'         => 'Nantes',
            'country'      => 'France',
            'password'     => 'test',
        ];
        $user = \Sentinel::register($credentials);
        $user_role->users()->attach($user);

        /***************************************************************************************************************
         * run test
         **************************************************************************************************************/
        $I->amOnPage('/');
        $I->amOnRoute('users.edit', ['id' => $user->id]);
        $I->selectOption('gender', config('user.gender_key.male'));
        $I->fillField('last_name', 'OTHER');
        $I->fillField('first_name', 'Other');
        $I->fillField('birth_date', '01/01/1999');
        $I->selectOption('#input_status_id', trans('users.config.status.student_president'));
        $I->selectOption('#input_board_id', config('user.board_key.executive_committee'));
        $I->fillField('phone_number', '0101010101');
        $I->fillField('email', 'other@other.fr');
        $I->fillField('address', '1 impasse Commandant Cousteau');
        $I->fillField('zip_code', 99456);
        $I->fillField('city', 'Toulon');
        $I->fillField('country', 'Maroc');
        $I->selectOption('role', Sentinel::findRoleBySlug('admin')->id);
        $I->checkOption('#input_active');
        $I->fillField('password', 'password');
        $I->fillField('password_confirmation', 'password');
        $I->click(trans('users.page.action.update'));
        $I->seeCurrentRouteIs('users.edit', ['id' => $user->id]);
        $I->see(trans('global.modal.alert.title.success'), 'h3');
        $I->see(strip_tags(trans('users.message.update.success', ['name' => 'Other OTHER'])));
        $I->seeInField('last_name', 'OTHER');
        $I->seeInField('first_name', 'Other');
        $I->seeOptionIsSelected('gender', config('user.gender_key.male'));
        $I->seeInField('birth_date', '01/01/1999');
        $I->seeOptionIsSelected('#input_status_id', trans('users.config.status.student_president'));
        $I->seeOptionIsSelected('#input_board_id', trans('users.config.board.executive_committee'));
        $I->seeInField('phone_number', '+33 1 01 01 01 01');
        $I->seeInField('email', 'other@other.fr');
        $I->seeInField('address', '1 impasse Commandant Cousteau');
        $I->seeInField('zip_code', 99456);
        $I->seeInField('city', 'Toulon');
        $I->seeInField('country', 'Maroc');
        $I->seeOptionIsSelected('role', \Sentinel::findRoleBySlug('admin')->id);
        $I->SeeCheckboxIsChecked('#input_active');
        $I->seeRecord('users', [
            'last_name'    => 'OTHER',
            'first_name'   => 'Other',
            'gender'       => config('user.gender_key.male'),
            'birth_date'   => '1999-01-01',
            'status_id'    => config('user.status_key.student_president'),
            'board_id'     => config('user.board_key.executive_committee'),
            'phone_number' => '+33 1 01 01 01 01',
            'email'        => 'other@other.fr',
            'address'      => '1 impasse Commandant Cousteau',
            'zip_code'     => 99456,
            'city'         => 'Toulon',
            'country'      => 'Maroc',
        ]);
        $I->seeRecord('role_users', [
            'user_id' => $user->id,
            'role_id' => Sentinel::findRoleBySlug('admin')->id,
        ]);
        $I->seeRecord('activations', [
            'user_id'   => $user->id,
            'completed' => true,
        ]);
        $user = Sentinel::getUserRepository()->findByCredentials(['email' => 'other@other.fr']);
        $I->assertTrue(Hash::check('test', $user->password));
    }

    public function update_user_with_higher_role(FunctionalTester $I)
    {
        $I->am('Admin');
        $I->wantTo('update a user with a higher role than mine');
        $I->expectTo('see an error message explaining that I do not have the authorization');

        /***************************************************************************************************************
         * settings
         **************************************************************************************************************/
        // we create the user role
        $user_role = $this->_createUserRole();
        // we attach it the the logged user
        $user_role->users()->attach($this->_user);
        // we create another user
        $credentials = [
            'last_name'  => 'Autre',
            'first_name' => 'autre',
            'email'      => 'autre@autre.fr',
            'password'   => 'autre',
        ];
        $user = \Sentinel::register($credentials, true);
        // we attach the user to the user role
        $user_role->users()->attach($user);
        // we create the admin role
        $admin_role = $this->_createAdminRole();

        /***************************************************************************************************************
         * run test
         **************************************************************************************************************/
        $I->amOnPage('/');
        $I->amOnRoute('users.edit', ['id' => $user->id]);
        $user_role->users()->detach($user);
        $admin_role->users()->attach($user);
        $I->click(trans('users.page.action.update'));
        $I->see(trans('global.modal.alert.title.error'), 'h3');
        $I->see(trans('users.message.permission.denied', ['action' => trans('users.message.permission.action.edit')]));
    }

    public function update_user_role_for_a_role_higher_than_mine(FunctionalTester $I)
    {
        $I->am('Admin');
        $I->wantTo('update a user role with a role higher than mine');
        $I->expectTo('see an error message explaining that I can\'t give a role superior than mine to a user');

        /***************************************************************************************************************
         * settings
         **************************************************************************************************************/
        // we create the user role
        $user_role = $this->_createUserRole();
        // we attach it to the logged user
        $user_role->users()->attach($this->_user);
        // we create another user
        $credentials = [
            'last_name'  => 'Autre',
            'first_name' => 'autre',
            'email'      => 'autre@autre.fr',
            'password'   => 'autre',
        ];
        $user = \Sentinel::register($credentials, true);
        $user_role->users()->attach($user);
        // we create the admin role
        $admin_role = $this->_createAdminRole();

        /***************************************************************************************************************
         * run test
         **************************************************************************************************************/
        $I->amOnPage('/');
        $I->amOnRoute('users.edit', ['id' => $user->id]);
        $I->selectOption('role', $admin_role->id);
        $I->click(trans('users.page.action.update'));
        $I->see(trans('global.modal.alert.title.error'), 'h3');
        $I->see(trans('users.message.permission.denied', ['action' => trans('users.message.permission.action.edit')]));
    }

    public function access_to_my_profile(FunctionalTester $I)
    {
        $I->am('Admin');
        $I->wantTo('access to my profile page');
        $I->expectTo('see my profile page');

        /***************************************************************************************************************
         * run test
         **************************************************************************************************************/
        $I->amOnPage('/');
        $I->seeCurrentRouteIs('home');
        $I->click(trans('template.front.header.my_account'));
        $I->seeCurrentRouteIs('dashboard.index');
        $I->click(trans('template.back.header.my_profile'));
        $I->seeCurrentRouteIs('users.profile');
        $I->see(trans('breadcrumbs.admin'), '.breadcrumb');
        $I->seeLink(trans('breadcrumbs.admin'), route('dashboard.index'));
        $I->see(strip_tags(trans('breadcrumbs.users.profile')), '.breadcrumb');
        $I->assertFileNotExists('photo');
        $I->seeOptionIsSelected('gender', config('user.gender_key.male'));
        $I->seeInField('last_name', $this->_user->last_name);
        $I->seeInField('first_name', $this->_user->first_name);
        $I->seeInField('birth_date', Carbon::createFromFormat('Y-m-d', $this->_user->birth_date)->format('d/m/Y'));
        $I->seeInField('phone_number', $this->_user->phone_number);
        $I->seeInField('email', $this->_user->email);
        $I->seeInField('address', $this->_user->address);
        $I->seeInField('zip_code', $this->_user->zip_code);
        $I->seeInField('city', $this->_user->city);
        $I->seeInField('country', $this->_user->country);
        $I->seeInField('password', '');
        $I->seeInField('password_confirmation', '');
        $I->dontSee(trans('users.page.label.status_id'));
        $I->dontSee(trans('users.page.label.board_id'));
        $I->dontSee(trans('users.page.label.role'));
        $I->dontSee(trans('users.page.label.activation'));
    }

    public function update_my_profile_with_no_field_filled_from_ajax(FunctionalTester $I)
    {
        $I->am('Admin');
        $I->wantTo('update my profile with no fields filled from ajax request');
        $I->expectTo('see an error message explaining that some fields are missing');

        /***************************************************************************************************************
         * settings
         **************************************************************************************************************/
        // we create the admin role
        $admin_role = $this->_createAdminRole();
        // we attach it to the logged user
        $admin_role->users()->attach($this->_user);

        /***************************************************************************************************************
         * run test
         **************************************************************************************************************/
        $I->amOnPage('/');
        $I->amOnRoute('users.profile');
        $I->sendAjaxRequest('POST', route('users.update', [
            'id'      => $this->_user->id,
            '_token'  => csrf_token(),
            '_method' => 'PUT',
        ]));
        $I->seeCurrentRouteIs('users.profile');
        $I->see(trans('global.modal.alert.title.error'));
        $I->see(strip_tags(trans('validation.filled', ['attribute' => trans('validation.attributes.last_name')])));
        $I->see(strip_tags(trans('validation.filled', ['attribute' => trans('validation.attributes.first_name')])));
        $I->dontSee(strip_tags(trans('validation.filled', ['attribute' => trans('validation.attributes.status_id')])));
        $I->see(strip_tags(trans('validation.filled', ['attribute' => trans('validation.attributes.email')])));
        $I->dontSee(strip_tags(trans('validation.filled', ['attribute' => trans('validation.attributes.role')])));
        $I->dontSee(strip_tags(trans('validation.filled', ['attribute' => trans('validation.attributes.password')])));
    }

    public function update_my_personal_profile_with_no_change(FunctionalTester $I)
    {
        $I->am('Admin');
        $I->wantTo('update my profile without changing any information');
        $I->expectTo('see a success confirmation message');

        /***************************************************************************************************************
         * settings
         **************************************************************************************************************/
        // we create the admin role
        $admin_role = $this->_createAdminRole();
        // we attach it to the logged user
        $admin_role->users()->attach($this->_user);

        /***************************************************************************************************************
         * run test
         **************************************************************************************************************/
        $I->amOnPage('/');
        $I->amOnRoute('users.profile', ['id' => $this->_user->id]);
        $I->see(trans('users.page.title.profile'), 'h2');
        $I->click(trans('global.action.save'));
        $I->seeCurrentRouteIs('users.profile', ['id' => $this->_user->id]);
        $I->see(trans('global.modal.alert.title.success'), 'h3');
        $I->see(trans('users.message.account.success'));
        $user = Sentinel::getUserRepository()->findByCredentials(['email' => $this->_user->email]);
        $I->seeRecord('users', [
            'last_name'    => $user->last_name,
            'first_name'   => $user->first_name,
            'birth_date'   => $user->birth_date,
            'phone_number' => $user->phone_number,
            'email'        => $user->email,
            'address'      => $user->address,
            'zip_code'     => $user->zip_code,
            'city'         => $user->city,
            'country'      => $user->country,
        ]);
        $I->seeRecord('role_users', [
            'user_id' => $user->id,
            'role_id' => Sentinel::findRoleBySlug('admin')->id,
        ]);
        $I->seeRecord('activations', [
            'user_id'   => $user->id,
            'completed' => true,
        ]);
        $I->assertTrue(Hash::check($this->_credentials['password'], $user->password));
    }

    public function update_my_personal_profile_with_changes(FunctionalTester $I)
    {
        $I->am('Admin');
        $I->wantTo('update my profile and change some informations');
        $I->expectTo('see a success confirmation message and see that my data have changed');

        /***************************************************************************************************************
         * settings
         **************************************************************************************************************/
        // we create the admin role
        $admin_role = $this->_createAdminRole();
        // we attach it to the logged user
        $admin_role->users()->attach($this->_user);

        /***************************************************************************************************************
         * run test
         **************************************************************************************************************/
        $I->amOnPage('/');
        $I->amOnRoute('users.profile');
        $I->see(trans('users.page.title.profile'), 'h2');
        $I->selectOption('gender', config('user.gender_key.male'));
        $I->fillField('last_name', 'OTHER');
        $I->fillField('first_name', 'Other');
        $I->fillField('birth_date', '01/01/1999');
        $I->fillField('phone_number', '0101010101');
        $I->fillField('email', 'other@other.fr');
        $I->fillField('address', '1 impasse Commandant Cousteau');
        $I->fillField('zip_code', 99456);
        $I->fillField('city', 'Toulon');
        $I->fillField('country', 'Maroc');
        $I->fillField('password', 'password');
        $I->fillField('password_confirmation', 'password');
        $I->click(trans('global.action.save'));
        $I->seeCurrentRouteIs('users.profile');
        $I->see(trans('global.modal.alert.title.success'), 'h3');
        $I->see(trans('users.message.account.success'));
        $this->_user->fresh();
        $I->seeRecord('users', [
            'last_name'    => 'OTHER',
            'first_name'   => 'Other',
            'gender'       => config('user.gender_key.male'),
            'birth_date'   => '1999-01-01',
            'status_id'    => $this->_user->status_id,
            'board_id'     => $this->_user->board_id,
            'phone_number' => '+33 1 01 01 01 01',
            'email'        => 'other@other.fr',
            'address'      => '1 impasse Commandant Cousteau',
            'zip_code'     => 99456,
            'city'         => 'Toulon',
            'country'      => 'Maroc',
        ]);
        $I->seeRecord('role_users', [
            'user_id' => $this->_user->id,
            'role_id' => Sentinel::findRoleBySlug('admin')->id,
        ]);
        $I->seeRecord('activations', [
            'user_id'   => $this->_user->id,
            'completed' => true,
        ]);
        $user = Sentinel::getUserRepository()->findByCredentials(['email' => 'other@other.fr']);
        $I->assertTrue(Hash::check('test', $user->password));
    }

    public function delete_user_without_permission(FunctionalTester $I)
    {
        $I->am('A user with no role');
        $I->wantTo('delete a user without the permission');
        $I->expectTo('see an error message explaining that I do have the permission to delete the user');

        /***************************************************************************************************************
         * settings
         **************************************************************************************************************/
        // we create the admin role
        $admin_role = $this->_createAdminRole();
        // we remove all its permissions
        $admin_role->permissions = [];
        $admin_role->save();
        // we attach it to the logged user
        $admin_role->users()->attach($this->_user);
        // we create another user
        $credentials = [
            'last_name'  => 'Autre',
            'first_name' => 'autre',
            'email'      => 'autre@autre.fr',
            'password'   => 'autre',
        ];
        $user = \Sentinel::register($credentials, true);

        /***************************************************************************************************************
         * run test
         **************************************************************************************************************/
        $this->_user->addPermission('users.list', true)->save();
        $I->amOnPage('/');
        $I->amOnRoute('users.index');
        $I->submitForm('#delete_' . $user->id, []);
        $I->seeCurrentRouteIs('users.index');
        $I->see(trans('global.modal.alert.title.error'), 'h3');
        $I->see(strip_tags(trans('permissions.message.access.denied', ['permission' => trans('permissions.users.delete')])));
    }

    public function delete_user_with_wrong_id(FunctionalTester $I)
    {
        $I->am('A user with no role');
        $I->wantTo('delete a user with a wrong id');
        $I->expectTo('see an error message explaining the user doesn\'t exists and be redirected to the users list');

        /***************************************************************************************************************
         * settings
         **************************************************************************************************************/
        // we create the admin role
        $admin_role = $this->_createAdminRole();
        // we attach it to the logged user
        $admin_role->users()->attach($this->_user);

        /***************************************************************************************************************
         * run test
         **************************************************************************************************************/
        $I->amOnPage('/');
        $I->amOnRoute('users.index');
        $I->sendAjaxRequest('POST', route('users.destroy', ['id' => 1000000000000, '_token' => csrf_token(), '_method' => 'DELETE']));
        $I->seeCurrentRouteIs('users.index');
        $I->see(trans('global.modal.alert.title.error'));
        $I->see(strip_tags(trans('users.message.find.failure', ['id' => 1000000000000])));
    }

    public function delete_user(FunctionalTester $I)
    {
        $I->am('Admin');
        $I->wantTo('delete a user (with his photo)');
        $I->expectTo('see a success confirmation message and see that the deleted user is not here anymore');

        /***************************************************************************************************************
         * settings
         **************************************************************************************************************/
        $admin_role = $this->_createAdminRole();
        $admin_role->users()->attach($this->_user);
        $credentials = [
            'last_name'  => 'Autre',
            'first_name' => 'autre',
            'email'      => 'autre@autre.fr',
            'password'   => 'autre',
        ];
        $user = \Sentinel::register($credentials, true);

        /***************************************************************************************************************
         * run test
         **************************************************************************************************************/
        $I->amOnRoute('users.index');
        $I->submitForm('#delete_' . $user->id, []);
        $I->seeCurrentRouteIs('users.index');
        $I->see(trans('global.modal.alert.title.success'), 'h3');
        $I->see(strip_tags(trans('users.message.delete.success', ['name' => $user->first_name . ' ' . $user->last_name])));
        $I->dontSee($user->first_name . ' ' . $user->last_name, '.table-list');
    }

    public function delete_user_with_higher_role(FunctionalTester $I)
    {
        $I->am('Admin');
        $I->wantTo('delete a user with a higher role than mine');
        $I->expectTo('see an error message explaining that I do not have the authorization');

        /***************************************************************************************************************
         * settings
         **************************************************************************************************************/
        // we create the user role
        $user_role = $this->_createUserRole();
        // we attach it the the logged user
        $user_role->users()->attach($this->_user);
        // we create another user
        $credentials = [
            'last_name'  => 'Autre',
            'first_name' => 'autre',
            'email'      => 'autre@autre.fr',
            'password'   => 'autre',
        ];
        $user = \Sentinel::register($credentials, true);
        // we create the admin role
        $admin_role = $this->_createAdminRole();
        // we attach it to the new user
        $admin_role->users()->attach($user);

        /***************************************************************************************************************
         * run test
         **************************************************************************************************************/
        $I->amOnRoute('users.index');
        $I->submitForm('#delete_' . $user->id, []);
        $I->seeCurrentRouteIs('users.index');
        $I->see(trans('global.modal.alert.title.error'), 'h3');
        $I->see(trans('users.message.permission.denied', ['action' => trans('users.message.permission.action.delete')]));
    }

    public function activate_user_without_permission(FunctionalTester $I)
    {
        $I->am('A user with no role');
        $I->wantTo('activate a user without the permission');
        $I->expectTo('see an error message explaining that I do not have the permission to activate the user');

        /***************************************************************************************************************
         * settings
         **************************************************************************************************************/
        // we create the admin role
        $admin_role = $this->_createAdminRole();
        // we remove all its permissions
        $admin_role->permissions = [];
        $admin_role->save();
        // we attach it to the logged user
        $admin_role->users()->attach($this->_user);
        // we create another user
        $credentials = [
            'last_name'  => 'Autre',
            'first_name' => 'autre',
            'email'      => 'autre@autre.fr',
            'password'   => 'autre',
        ];
        $user = \Sentinel::register($credentials);

        /***************************************************************************************************************
         * run test
         **************************************************************************************************************/
        $this->_user->addPermission('users.list', true)->save();
        $I->amOnPage('/');
        $I->amOnRoute('users.index');
        $I->checkOption('#activate_' . $user->id);
        $I->submitForm('#form_activate_' . $user->id, []);
        $I->seeResponseCodeIs(401);
        $I->see(strip_tags(trans('permissions.message.access.denied', ['permission' => trans('permissions.users.update')])));
        $I->dontSeeRecord('activations', [
            'user_id'   => $user->id,
            'completed' => true,
        ]);
    }

    public function activate_user_with_wrong_id(FunctionalTester $I)
    {
        $I->am('A user with no role');
        $I->wantTo('activate a user with the wrong id');
        $I->expectTo('see an error message explaining that the user does not exists');

        /***************************************************************************************************************
         * settings
         **************************************************************************************************************/
        // we create the admin role
        $admin_role = $this->_createAdminRole();
        // we attach it to the logged user
        $admin_role->users()->attach($this->_user);
        // we create another user
        $credentials = [
            'last_name'  => 'Autre',
            'first_name' => 'autre',
            'email'      => 'autre@autre.fr',
            'password'   => 'autre',
        ];
        $user = \Sentinel::register($credentials);

        /***************************************************************************************************************
         * run test
         **************************************************************************************************************/
        $I->amOnRoute('users.index');
        $I->sendAjaxRequest('POST', route('users.activate', ['id' => 1000000000000, '_token' => csrf_token(), 'active' => true]));
        $I->seeResponseCodeIs(401);
        $I->see(strip_tags(trans('users.message.find.failure', ['id' => 1000000000000])));
    }

    public function activate_user(FunctionalTester $I)
    {
        $I->am('Admin');
        $I->wantTo('activate a user');
        $I->expectTo('see that the user has been activated');

        /***************************************************************************************************************
         * settings
         **************************************************************************************************************/
        // we create the admin role
        $admin_role = $this->_createAdminRole();
        $admin_role->users()->attach($this->_user);
        // we create another user
        $credentials = [
            'last_name'  => 'Autre',
            'first_name' => 'autre',
            'email'      => 'autre@autre.fr',
            'password'   => 'autre',
        ];
        $user = \Sentinel::register($credentials);

        /***************************************************************************************************************
         * run test
         **************************************************************************************************************/
        $I->amOnPage('/');
        $I->amOnRoute('users.index');
        $I->checkOption('#activate_' . $user->id);
        $I->submitForm('#form_activate_' . $user->id, []);
        $I->seeResponseCodeIs(200);
//        $I->see(strip_tags(trans('users.message.activation.success.label', ['action' => trans_choice('users.message.activation.success.action', true), 'name' => $user->first_name . ' ' . $user->last_name])));
        $I->seeRecord('activations', [
            'user_id'   => $user->id,
            'completed' => true,
        ]);
    }

    public function activate_user_with_higher_role(FunctionalTester $I)
    {
        $I->am('Admin');
        $I->wantTo('activate a user which have a higher role than mine');
        $I->expectTo('see an error message explaining that I do not have the authorization');

        /***************************************************************************************************************
         * settings
         **************************************************************************************************************/
        // we create the user role
        $user_role = $this->_createUserRole();
        // we attach it the the logged user
        $user_role->users()->attach($this->_user);
        // we create another user
        $credentials = [
            'last_name'  => 'Autre',
            'first_name' => 'autre',
            'email'      => 'autre@autre.fr',
            'password'   => 'autre',
        ];
        $user = \Sentinel::register($credentials);
        // we create the admin role
        $admin_role = $this->_createAdminRole();
        // we attach it to the new user
        $admin_role->users()->attach($user);

        /***************************************************************************************************************
         * run test
         **************************************************************************************************************/
        $this->_user->addPermission('users.list', true)->save();
        $I->amOnPage('/');
        $I->amOnRoute('users.index');
        $I->checkOption('#activate_' . $user->id);
        $I->submitForm('#form_activate_' . $user->id, []);
        $I->seeResponseCodeIs(401);
//        $I->see(strip_tags(trans('users.message.permission.denied', ['action' => trans('users.message.permission.action.edit')])));
        $I->dontSeeRecord('activations', [
            'user_id'   => $user->id,
            'completed' => true,
        ]);
    }

    public function deactivate_user(FunctionalTester $I)
    {
        $I->am('Admin');
        $I->wantTo('deactivate a user');
        $I->expectTo('see that the user has been deactivated');

        /***************************************************************************************************************
         * settings
         **************************************************************************************************************/
        // we create the admin role
        $admin_role = $this->_createAdminRole();
        $admin_role->users()->attach($this->_user);
        // we create another user
        $credentials = [
            'last_name'  => 'Autre',
            'first_name' => 'autre',
            'email'      => 'autre@autre.fr',
            'password'   => 'autre',
        ];
        $user = \Sentinel::register($credentials, true);

        /***************************************************************************************************************
         * run test
         **************************************************************************************************************/
        $I->amOnPage('/');
        $I->amOnRoute('users.index');
        $I->uncheckOption('#activate_' . $user->id);
        $I->submitForm('#form_activate_' . $user->id, []);
        $I->seeResponseCodeIs(200);
//        $I->see(strip_tags(trans('users.message.activation.success.label', ['action' => trans_choice('users.message.activation.success.action', false), 'name' => $user->first_name . ' ' . $user->last_name])));
        $I->dontSeeRecord('activations', [
            'user_id'   => $user->id,
            'completed' => true,
        ]);
    }

    public function access_to_the_leading_team_page(FunctionalTester $I)
    {
        $I->am('Anybody');
        $I->wantTo('access to the leading team page');
        $I->expectTo('see that the user shown in the category they belong');

        /***************************************************************************************************************
         * settings
         **************************************************************************************************************/
        // we create a member of the student leading board
        $credentials = [
            'last_name'  => 'Student',
            'first_name' => 'President',
            'email'      => 'student@president.fr',
            'status_id'    => config('user.status_key.student_president'),
            'board_id'     => config('user.board_key.student_leading_board'),
            'password' => 'test'

        ];
        Sentinel::register($credentials, true);
        // we create a member of the student leading board
        $credentials = [
            'last_name'  => 'Deactivated',
            'first_name' => 'Student vice-president',
            'email'      => 'vice-student@president.fr',
            'status_id'    => config('user.status_key.user'),
            'board_id'     => config('user.board_key.student_leading_board'),
            'password' => 'test'
        ];
        Sentinel::register($credentials);
        // we create a member of the leading board
        $credentials = [
            'last_name'  => 'Regular',
            'first_name' => 'President',
            'email'      => 'regular@president.fr',
            'status_id'    => config('user.status_key.president'),
            'board_id'     => config('user.board_key.leading_board'),
            'password' => 'test'
        ];
        Sentinel::register($credentials, true);
        // we create a member of the executive committee
        $credentials = [
            'last_name'  => 'Communication',
            'first_name' => 'Responsible',
            'email'      => 'communication@una-club.fr',
            'status_id'    => config('user.status_key.communication_commission'),
            'board_id'     => config('user.board_key.executive_committee'),
            'password' => 'test'
        ];
        Sentinel::register($credentials, true);
        // we create an employee
        $credentials = [
            'last_name'  => 'Coach',
            'first_name' => 'Employee',
            'email'      => 'coach@una-club.fr',
            'status_id'    => config('user.status_key.employee'),
            'password' => 'test'
        ];
        Sentinel::register($credentials, true);

        /***************************************************************************************************************
         * run test
         **************************************************************************************************************/
        $I->amOnPage('/');
        $I->seeCurrentRouteIs('home');
        $I->click(trans('template.front.header.leading_team'));

        $I->see('Student', '.student_leading_board');
        $I->see('President', '.student_leading_board');
        $I->see(trans('users.config.status.student_president'), '.student_leading_board');

        $I->dontSee('Deactivated', '.student_leading_board');
        $I->dontSee('Student vice-president', '.student_leading_board');
        $I->dontSee(trans('users.config.status.user'), '.student_leading_board');

        $I->see('Regular', '.leading_board');
        $I->see('President', '.leading_board');
        $I->see(trans('users.config.status.president'), '.leading_board');

        $I->see('Communication', '.executive_committee');
        $I->see('Responsible', '.executive_committee');
        $I->see(trans('users.config.status.communication_commission'), '.executive_committee');

        $I->see('Coach', '.employee');
        $I->see('Employee', '.employee');
        $I->see(trans('users.config.status.employee'), '.employee');
    }
}
