<?php

class UserCest
{
    private $_user;
    private $_credentials;

    public function _before(FunctionalTester $I)
    {
        // we create a user
        $this->_credentials = [
            'last_name'  => 'Test',
            'first_name' => 'test',
            'email'      => 'test@test.fr',
            'status' => config('user.status_key.communication-commission'),
            'board' => config('user.board_key.leading-board'),
            'password'   => 'test',
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
        $admin = \Sentinel::getRoleRepository()->createModel()->create([
            'slug'     => 'admin',
            'position' => 1,
        ]);
        // we translate the translatable fields
        $admin->translateOrNew('fr')->name = 'Administrateur';
        $admin->translateOrNew('en')->name = 'Administrator';
        // we give all permissions to the admin role
        $permissions = [];
        foreach (array_dot(config('permissions')) as $permission => $value) {
            $permissions[$permission] = true;
        }
        $admin->permissions = $permissions;
        // we save the changes
        $admin->save();

        return $admin;
    }

    private function _createUserRole()
    {
        // we create the admin role
        $member = \Sentinel::getRoleRepository()->createModel()->create([
            'slug'     => 'user',
            'position' => 2,
        ]);
        // we translate the translatable fields
        $member->translateOrNew('fr')->name = 'Utilisateur';
        $member->translateOrNew('en')->name = 'User';
        // we give all permissions to the admin role
        $permissions = [];
        foreach (array_dot(config('permissions')) as $permission => $value) {
            $permissions[$permission] = true;
        }
        $member->permissions = $permissions;
        // we save the changes
        $member->save();

        return $member;
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
        $I->seeCurrentRouteIs('epi_warning_system.index');
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
        $admin = $this->_createAdminRole();
        // we attach it to the logged user
        $admin->users()->attach($this->_user);
        // we create another user
        $credentials = [
            'country_id' => $this->_country->id,
            'last_name'  => 'Autre',
            'first_name' => 'autre',
            'email'      => 'autre@autre.fr',
            'password'   => 'autre',
            'last_login' => Carbon\Carbon::now(),
        ];
        $user = \Sentinel::register($credentials, true);
        // we attach the user to the admin role
        $admin->users()->attach($user);

        /***************************************************************************************************************
         * run test
         **************************************************************************************************************/
        $I->amOnPage('/');
        $I->seeCurrentRouteIs('epi_warning_system.index');
        $I->click(trans('template.back.header.users'));
        $I->seeCurrentRouteIs('users.index');
        $I->see(trans('breadcrumbs.admin'), '.breadcrumb');
        $I->seeLink(trans('breadcrumbs.admin'), route('home'));
        $I->see(trans('users.page.title.management'));
        $I->see(trans('users.page.title.list'));
        $I->dontSee($this->_user->last_name, '.table-list');
        $I->dontSee($this->_user->first_name, '.table-list');
        $I->see($user->last_name, '.table-list');
        $I->see($user->first_name, '.table-list');
        $I->see($user->roles->first()->name, '.table-list');
        $I->see($this->_country->name, '.table-list');
        $I->see(Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $user->last_login)->format('d/m/Y H:i:s'), '.table-list');
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
        $admin = $this->_createAdminRole();
        $admin->users()->attach($this->_user);
        // we create a new user
        $credentials = [
            'last_name'  => 'Autre',
            'first_name' => 'autre',
            'email'      => 'autre@autre.fr',
            'password'   => 'autre',
        ];
        $user1 = \Sentinel::register($credentials, true);
        // we create another user
        $credentials = [
            'last_name'  => 'Other',
            'first_name' => 'other',
            'email'      => 'other@other.fr',
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
        $admin = $this->_createAdminRole();
        $admin->users()->attach($this->_user);
        // we create a new user
        $credentials = [
            'last_name'  => 'Autre',
            'first_name' => 'autre',
            'email'      => 'autre@autre.fr',
            'password'   => 'autre',
        ];
        $user1 = \Sentinel::register($credentials, true);
        // we create another user
        $credentials = [
            'last_name'  => 'Other',
            'first_name' => 'other',
            'email'      => 'other@other.fr',
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
        $I->seeCurrentRouteIs('epi_warning_system.index');
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
        $admin = $this->_createAdminRole();
        // we attach it to the logged user
        $admin->users()->attach($this->_user);
        // we create the operator role
        $this->_createUserRole();

        /***************************************************************************************************************
         * run test
         **************************************************************************************************************/
        $I->amOnPage('/');
        $I->seeCurrentRouteIs('epi_warning_system.index');
        $I->click(trans('template.back.header.users'));
        $I->seeCurrentRouteIs('users.index');
        $I->click(trans('global.action.add'));
        $I->seeCurrentRouteIs('users.create');
        $I->see(trans('breadcrumbs.admin'), '.breadcrumb');
        $I->seeLink(trans('breadcrumbs.admin'), route('home'));
        $I->see(strip_tags(trans('breadcrumbs.users.create')), '.breadcrumb');
        $I->seeLink(trans('breadcrumbs.users.index'), route('users.index'));
        $I->see(trans('users.page.title.create'), 'h2');
        $I->assertFileNotExists('photo');
        $I->seeInField('last_name', '');
        $I->seeInField('first_name', '');
        $I->seeInField('email', '');
        $I->seeInField('country_id', '');
        $I->dontSeeCheckboxIsChecked('activation');
        $I->seeOptionIsSelected('role', \Sentinel::findRoleBySlug('operator')->id);
        $I->dontseeCheckboxIsChecked('#input_activation');
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
        $admin = $this->_createAdminRole();
        // we attach it to the logged user
        $admin->users()->attach($this->_user);
        // we create the operator role
        $this->_createUserRole();

        /***************************************************************************************************************
         * run test
         **************************************************************************************************************/
        $I->amOnPage('/');
        $I->seeCurrentRouteIs('epi_warning_system.index');
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
        $admin = $this->_createAdminRole();
        // we remove all its permissions
        $admin->permissions = [];
        $admin->save();
        // we attach it to the logged user
        $admin->users()->attach($this->_user);
        // we create the operator role
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
        $I->seeCurrentRouteIs('epi_warning_system.index');
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
        // we create the operator role
        $member = $this->_createUserRole();
        // we attach it to the logged user
        $member->users()->attach($this->_user);
        // we create another user
        $credentials = [
            'country_id' => $this->_country->id,
            'last_name'  => 'Autre',
            'first_name' => 'autre',
            'email'      => 'autre@autre.fr',
            'password'   => 'autre',
        ];
        $user = \Sentinel::register($credentials, true);
        $member->users()->attach($user);
        // we create the admin role
        $admin = $this->_createAdminRole();

        /***************************************************************************************************************
         * run test
         **************************************************************************************************************/
        $I->amOnRoute('users.create');
        $I->see(trans('users.page.title.create'));
        $I->fillField('last_name', 'TEST');
        $I->fillField('first_name', 'test');
        $I->fillField('email', 'email@test.fr');
        $I->fillField('email', 'email@test.fr');
        $I->selectOption('role', $admin->id);
        $I->checkOption('#input_activation', true);
        $I->fillField('password', 'password');
        $I->fillField('password_confirmation', 'password');
        $I->click(trans('users.page.action.create'));

        $I->seeCurrentRouteIs('users.create');
        $I->see(trans('global.modal.alert.title.error'), 'h3');
        $I->see(trans('users.message.permission.denied', ['action' => trans('users.message.permission.action.create')]));
    }

    public function create_user_with_no_field_filled(FunctionalTester $I)
    {
        $I->am('Admin');
        $I->wantTo('create user with no fields filled');
        $I->expectTo('see an error message explaining that some fields are missing');

        /***************************************************************************************************************
         * settings
         **************************************************************************************************************/
        // we create the admin role
        $admin = $this->_createAdminRole();
        $admin->users()->attach($this->_user);
        // we create the operator role
        $member = $this->_createUserRole();

        /***************************************************************************************************************
         * run test
         **************************************************************************************************************/
        $I->amOnRoute('users.create');
        $I->see(trans('users.page.title.create'));
        $I->click(trans('users.page.action.create'));
        $I->see(trans('global.modal.alert.title.error'), 'h3');
        $I->see(strip_tags(trans('validation.filled', ['attribute' => trans('validation.attributes.last_name')])));
        $I->see(strip_tags(trans('validation.filled', ['attribute' => trans('validation.attributes.first_name')])));
        $I->see(strip_tags(trans('validation.filled', ['attribute' => trans('validation.attributes.email')])));
        $I->see(strip_tags(trans('validation.filled', ['attribute' => trans('validation.attributes.password')])));
    }

    public function create_user_with_operator_role_and_no_country_selected(FunctionalTester $I)
    {
        $I->am('Admin');
        $I->wantTo('create a user with operator role and no country selected');
        $I->expectTo('see an error message explaining that the country is required');

        /***************************************************************************************************************
         * settings
         **************************************************************************************************************/
        // we create the admin role
        $admin = $this->_createAdminRole();
        // we attach it to the logged user
        $admin->users()->attach($this->_user);
        // we create the operator role
        $member = $this->_createUserRole();

        /***************************************************************************************************************
         * run test
         **************************************************************************************************************/
        $I->amOnRoute('users.create');
        $I->see(trans('users.page.title.create'));
        $I->fillField('last_name', 'TEST');
        $I->fillField('first_name', 'test');
        $I->fillField('email', 'email@test.fr');
        $I->fillField('email', 'email@test.fr');
        $I->selectOption('country_id', null);
        $I->selectOption('role', $member->id);
        $I->checkOption('#input_activation', true);
        $I->fillField('password', 'password');
        $I->fillField('password_confirmation', 'password');
        $I->click(trans('users.page.action.create'));
        $I->seeCurrentRouteIs('users.create');
        $I->see(trans('global.modal.alert.title.error'), 'h3');
        $I->see(strip_tags(trans('validation.required', ['attribute' => trans('validation.attributes.country_id')])));
    }

    public function create_user_with_wrong_values_filled(FunctionalTester $I)
    {
        $I->am('Admin');
        $I->wantTo('create a user with wrong values filled');
        $I->expectTo('see an error message explaining that the values are incorrect');

        /***************************************************************************************************************
         * settings
         **************************************************************************************************************/
        // we create the admin role
        $admin = $this->_createAdminRole();
        // we attach it to the logged user
        $admin->users()->attach($this->_user);
        // we create the operator role
        $member = $this->_createUserRole();

        /***************************************************************************************************************
         * run test
         **************************************************************************************************************/
        $I->amOnRoute('users.create');
        $I->see(trans('users.page.title.create'));
        $I->fillField('last_name', 123);
        $I->fillField('first_name', '759');
        $I->fillField('email', 'email.com');
        $I->selectOption('country_id', 0);
        $I->fillField('password', 'test');
        $I->fillField('password_confirmation', 'test');
        $I->click(trans('users.page.action.create'));
        $I->seeCurrentRouteIs('users.create');
        $I->see(trans('global.modal.alert.title.error'), 'h3');
        $I->see(strip_tags(trans('validation.string', ['attribute' => trans('validation.attributes.last_name')])));
        $I->see(strip_tags(trans('validation.string', ['attribute' => trans('validation.attributes.first_name')])));
        $I->see(strip_tags(trans('validation.email', ['attribute' => trans('validation.attributes.email')])));
        $I->see(strip_tags(trans('validation.exists', ['attribute' => trans('validation.attributes.country_id')])));
        $I->see(strip_tags(trans('validation.min.string', ['attribute' => trans('validation.attributes.password'), 'min' => config('password.min.length')])));
    }

//    public function create_user_with_too_small_photo(FunctionalTester $I)
//    {
//        $I->am('Admin');
//        $I->wantTo('create a user with a too small photo');
//        $I->expectTo('see an error message explaining that the photo is too small');
//
//        /***************************************************************************************************************
//         * settings
//         **************************************************************************************************************/
//        // we create the admin role
//        $admin = $this->_createAdminRole();
//        // we attach it to the logged user
//        $admin->users()->attach($this->_user);
//        // we create the operator role
//        $member = $this->_createUserRole();
//
//        /***************************************************************************************************************
//         * run test
//         **************************************************************************************************************/
//        $I->amOnRoute('users.create');
//        $I->see(trans('users.page.title.create'));
//        $I->attachFile('photo', 'users/photo_small.png');
//        $I->fillField('last_name', 'TEST');
//        $I->fillField('first_name', 'test');
//        $I->fillField('email', 'email@test.fr');
//        $I->fillField('email', 'email@test.fr');
//        $I->selectOption('country_id', $this->_country->id);
//        $I->selectOption('role', $member->id);
//        $I->checkOption('#input_activation', true);
//        $I->fillField('password', 'password');
//        $I->fillField('password_confirmation', 'password');
//        $I->click(trans('users.page.action.create'));
//        $I->seeCurrentRouteIs('users.create');
//        $I->see(trans('global.modal.alert.title.error'), 'h3');
//        $I->see(strip_tags(trans('validation.image_size', ['attribute' => trans('validation.attributes.photo'), 'width' => trans('validation.greaterthanorequal', ['size' => 145]), 'height' => trans('validation.greaterthanorequal', ['size' => 160])])));
//    }

    public function create_user(FunctionalTester $I)
    {
        $I->am('Admin');
        $I->wantTo('create a user');
        $I->expectTo('see a success confirmation message');

        /***************************************************************************************************************
         * settings
         **************************************************************************************************************/
        // we create the admin role
        $admin = $this->_createAdminRole();
        // we attach it to the logged user
        $admin->users()->attach($this->_user);
        // we create the operator role
        $member = $this->_createUserRole();

        /***************************************************************************************************************
         * run test
         **************************************************************************************************************/
        $I->amOnRoute('users.create');
        $I->see(trans('users.page.title.create'));
//        $I->attachFile('photo', 'users/photo_big.png');
        $I->fillField('last_name', 'TEST');
        $I->fillField('first_name', 'test');
        $I->fillField('email', 'email@test.fr');
        $I->fillField('email', 'email@test.fr');
        $I->selectOption('country_id', $this->_country->id);
        $I->selectOption('role', $member->id);
        $I->checkOption('#input_activation', true);
        $I->fillField('password', 'password');
        $I->fillField('password_confirmation', 'password');
        $I->click(trans('users.page.action.create'));
        $I->seeCurrentRouteIs('users.index');
        $I->see(trans('global.modal.alert.title.success'), 'h3');
        $I->see(strip_tags(trans('users.message.creation.success', ['name' => 'Test TEST'])));
    }

    public function access_to_user_edit_page_without_permission(FunctionalTester $I)
    {
        $I->am('A user with no role');
        $I->wantTo('access to the users edit page');
        $I->expectTo('see an error message explaining that I do not have the authorization');

        /***************************************************************************************************************
         * settings
         **************************************************************************************************************/
        // we create the operator role
        $member = $this->_createUserRole();
        // we create another user
        $credentials = [
            'country_id' => $this->_country->id,
            'last_name'  => 'Autre',
            'first_name' => 'autre',
            'email'      => 'autre@autre.fr',
            'password'   => 'autre',
        ];
        $user = \Sentinel::register($credentials, true);
        $member->users()->attach($user);

        /***************************************************************************************************************
         * run test
         **************************************************************************************************************/
        $I->amOnPage('/');
        $I->amOnRoute('users.edit', ['id' => $user->id]);
        $I->seeCurrentRouteIs('epi_warning_system.index');
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
        $admin = $this->_createAdminRole();
        // we attach it to the logged user
        $admin->users()->attach($this->_user);

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
        $I->expectTo('see the user edit page with fields filled with the role data');

        /***************************************************************************************************************
         * settings
         **************************************************************************************************************/
        // we create the admin role
        $admin = $this->_createAdminRole();
        // we attach it to the logged user
        $admin->users()->attach($this->_user);
        // we create the operator role
        $member = $this->_createUserRole();
        // we create another user
        $credentials = [
            'country_id' => $this->_country->id,
            'last_name'  => 'Autre',
            'first_name' => 'autre',
            'email'      => 'autre@autre.fr',
            'password'   => 'autre',
        ];
        $user = \Sentinel::register($credentials, true);
        $member->users()->attach($user);

        /***************************************************************************************************************
         * run test
         **************************************************************************************************************/
        $I->amOnPage('/');
        $I->seeCurrentRouteIs('epi_warning_system.index');
        $I->click(trans('template.back.header.users'));
        $I->seeCurrentRouteIs('users.index');
        $I->click('edit_' . $user->id);
        $I->seeCurrentRouteIs('users.edit', ['id' => $user->id]);
        $I->see(trans('breadcrumbs.admin'), '.breadcrumb');
        $I->seeLink(trans('breadcrumbs.admin'), route('home'));
        $I->see(strip_tags(trans('breadcrumbs.users.edit', ['user' => $user->first_name . ' ' . $user->last_name])), '.breadcrumb');
        $I->seeLink(trans('breadcrumbs.users.index'), route('users.index'));
        $I->see(strip_tags(trans('users.page.title.edit', ['user' => $user->first_name . ' ' . $user->last_name])), 'h2');
        $I->seeInField('last_name', $user->last_name);
        $I->seeInField('first_name', $user->first_name);
        $I->seeInField('email', $user->email);
        $I->seeOptionIsSelected('country_id', $this->_country->name);
        $I->seeOptionIsSelected('role', $member->id);
        $I->seeCheckboxIsChecked('#input_activation');
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
        $admin = $this->_createAdminRole();
        // we attach it to the logged user
        $admin->users()->attach($this->_user);
        // we create the operator role
        $member = $this->_createUserRole();
        // we create another user
        $credentials = [
            'country_id' => $this->_country->id,
            'last_name'  => 'Autre',
            'first_name' => 'autre',
            'email'      => 'autre@autre.fr',
            'password'   => 'autre',
        ];
        $user = \Sentinel::register($credentials, true);
        $member->users()->attach($user);

        /***************************************************************************************************************
         * run test
         **************************************************************************************************************/
        $I->amOnPage('/');
        $I->seeCurrentRouteIs('epi_warning_system.index');
        $I->click(trans('template.back.header.users'));
        $I->seeCurrentRouteIs('users.index');
        $I->click('edit_' . $user->id);
        $I->seeCurrentRouteIs('users.edit', ['id' => $user->id]);
        $I->click(trans('global.action.back'));
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
        // we create the operator role
        $member = $this->_createUserRole();
        // we attach it the the logged user
        $member->users()->attach($this->_user);
        // we create another user
        $credentials = [
            'country_id' => $this->_country->id,
            'last_name'  => 'Autre',
            'first_name' => 'autre',
            'email'      => 'autre@autre.fr',
            'password'   => 'autre',
        ];
        $user = \Sentinel::register($credentials, true);
        // we create the admin role
        $admin = $this->_createAdminRole();
        // we attach it to the new user
        $admin->users()->attach($user);

        /***************************************************************************************************************
         * run test
         **************************************************************************************************************/
        $I->amOnPage('/');
        $I->seeCurrentRouteIs('epi_warning_system.index');
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
        $admin = $this->_createAdminRole();
        // we remove all its permissions
        $admin->permissions = [];
        $admin->save();
        // we attach it to the logged user
        $admin->users()->attach($this->_user);
        // we create the operator role
        $member = $this->_createUserRole();
        // we create another user
        $credentials = [
            'country_id' => $this->_country->id,
            'last_name'  => 'Autre',
            'first_name' => 'autre',
            'email'      => 'autre@autre.fr',
            'password'   => 'autre',
        ];
        $user = \Sentinel::register($credentials, true);
        $member->users()->attach($user);

        /***************************************************************************************************************
         * run test
         **************************************************************************************************************/
        $this->_user->addPermission('users.view', true)->save();
        $I->amOnPage('/');
        $I->amOnRoute('users.edit', ['id' => $user->id]);
        $I->see(strip_tags(trans('users.page.title.edit', ['user' => $user->first_name . ' ' . $user->last_name])));
        $I->click(trans('users.page.action.edit'));
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
        $admin = $this->_createAdminRole();
        // we attach it to the logged user
        $admin->users()->attach($this->_user);
        // we create the operator role
        $member = $this->_createUserRole();
        // we create another user
        $credentials = [
            'country_id' => $this->_country->id,
            'last_name'  => 'Autre',
            'first_name' => 'autre',
            'email'      => 'autre@autre.fr',
            'password'   => 'autre',
        ];
        $user = \Sentinel::register($credentials, true);
        $member->users()->attach($user);

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

    public function update_user_with_no_change(FunctionalTester $I)
    {
        $I->am('Admin');
        $I->wantTo('update user with no change on fields');
        $I->expectTo('see a success confirmation message');

        /***************************************************************************************************************
         * settings
         **************************************************************************************************************/
        // we create the admin role
        $admin = $this->_createAdminRole();
        // we attach it to the logged user
        $admin->users()->attach($this->_user);
        // we create the operator role
        $member = $this->_createUserRole();
        // we create another user
        $credentials = [
            'country_id' => $this->_country->id,
            'last_name'  => 'Autre',
            'first_name' => 'autre',
            'email'      => 'autre@autre.fr',
            'password'   => 'autre',
        ];
        $user = \Sentinel::register($credentials, true);
        $member->users()->attach($user);

        /***************************************************************************************************************
         * run test
         **************************************************************************************************************/
        $I->amOnPage('/');
        $I->amOnRoute('users.edit', ['id' => $user->id]);
        $I->click(trans('users.page.action.edit'));
        $I->seeCurrentRouteIs('users.edit', ['id' => $user->id]);
        $I->see(trans('global.modal.alert.title.success'), 'h3');
        $I->see(strip_tags(trans('users.message.update.success', ['name' => $user->first_name . ' ' . $user->last_name])));
        $this->_user->fresh();
        $I->seeRecord('users', [
            'country_id' => $this->_user->country_id,
            'last_name'  => $this->_user->last_name,
            'first_name' => $this->_user->first_name,
            'email'      => $this->_user->email,
        ]);
        $I->assertTrue(Hash::check($this->_credentials['password'], $this->_user->password));
    }

    public function update_user_with_wrong_values_filled(FunctionalTester $I)
    {
        $I->am('Admin');
        $I->wantTo('update a user with no fields filled');
        $I->expectTo('see an error message explaining that the data are not correct');

        /***************************************************************************************************************
         * settings
         **************************************************************************************************************/
        // we create the admin role
        $admin = $this->_createAdminRole();
        // we attach it to the logged user
        $admin->users()->attach($this->_user);
        // we create the operator role
        $member = $this->_createUserRole();
        // we create another user
        $credentials = [
            'country_id' => $this->_country->id,
            'last_name'  => 'Autre',
            'first_name' => 'autre',
            'email'      => 'autre@autre.fr',
            'password'   => 'autre',
        ];
        $user = \Sentinel::register($credentials, true);
        $member->users()->attach($user);

        /***************************************************************************************************************
         * run test
         **************************************************************************************************************/
        $I->amOnPage('/');
        $I->amOnRoute('users.edit', ['id' => $user->id]);
        $I->fillField('last_name', 123);
        $I->fillField('first_name', '759');
        $I->fillField('email', 'email.com');
        $I->selectOption('country_id', 0);
        $I->fillField('password', 'test');
        $I->fillField('password_confirmation', 'test');
        $I->click(trans('users.page.action.edit'));
        $I->seeCurrentRouteIs('users.edit', ['id' => $user->id]);
        $I->see(trans('global.modal.alert.title.error'), 'h3');
        $I->see(strip_tags(trans('validation.string', ['attribute' => trans('validation.attributes.last_name')])));
        $I->see(strip_tags(trans('validation.string', ['attribute' => trans('validation.attributes.first_name')])));
        $I->see(strip_tags(trans('validation.email', ['attribute' => trans('validation.attributes.email')])));
        $I->see(strip_tags(trans('validation.exists', ['attribute' => trans('validation.attributes.country_id')])));
        $I->see(strip_tags(trans('validation.min.string', ['attribute' => trans('validation.attributes.password'), 'min' => config('password.min.length')])));
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
        $admin = $this->_createAdminRole();
        // we attach it to the logged user
        $admin->users()->attach($this->_user);
        // we create the operator role
        $member = $this->_createUserRole();
        // we create another user
        $credentials = [
            'country_id' => $this->_country->id,
            'last_name'  => 'Autre',
            'first_name' => 'autre',
            'email'      => 'autre@autre.fr',
            'password'   => 'autre',
        ];
        $user = \Sentinel::register($credentials, true);
        $member->users()->attach($user);

        /***************************************************************************************************************
         * run test
         **************************************************************************************************************/
        $I->amOnPage('/');
        $I->amOnRoute('users.edit', ['id' => $user->id]);
        $I->fillField('last_name', 'COUCOU');
        $I->fillField('first_name', 'Coucou');
        $I->fillField('email', 'another@email.fr');
        $I->selectOption('country_id', null);
        $I->selectOption('role', $admin->id);
        $I->uncheckOption('#input_activation');
        $I->fillField('password', 'motdepasse');
        $I->fillField('password_confirmation', 'motdepasse');
        $I->click(trans('users.page.action.edit'));
        $I->seeCurrentRouteIs('users.edit', ['id' => $user->id]);
        $I->see(trans('global.modal.alert.title.success'), 'h3');
        $I->see(strip_tags(trans('users.message.update.success', ['name' => 'Coucou COUCOU'])));
        $user = Sentinel::findUserById($user->id);
        $I->seeRecord('users', [
            'country_id' => null,
            'last_name'  => 'COUCOU',
            'first_name' => 'coucou',
            'email'      => 'another@email.fr',
        ]);
        $I->assertTrue(Hash::check('motdepasse', $user->password));
        $I->seeRecord('role_users', [
            'user_id' => $user->id,
            'role_id' => $admin->id,
        ]);
        $I->dontSeeRecord('activations', [
            'user_id'   => $user->id,
            'completed' => true,
        ]);
        $I->see(trans('breadcrumbs.admin'), '.breadcrumb');
        $I->seeLink(trans('breadcrumbs.admin'), route('home'));
        $I->see(strip_tags(trans('breadcrumbs.users.edit', ['user' => 'Coucou COUCOU'])), '.breadcrumb');
        $I->seeLink(trans('breadcrumbs.users.index'), route('users.index'));
        $I->see(strip_tags(trans('users.page.title.edit', ['user' => 'Coucou COUCOU'])), 'h2');
        $I->seeInField('last_name', 'COUCOU');
        $I->seeInField('first_name', 'Coucou');
        $I->seeInField('email', 'another@email.fr');
        $I->dontSeeOptionIsSelected('country_id', $this->_country->id);
        $I->seeOptionIsSelected('role', $admin->id);
        $I->dontSeeCheckboxIsChecked('#input_activation');
        $I->seeInField('password', '');
        $I->seeInField('password_confirmation', '');
    }

    public function update_user_with_operator_role_and_no_country_selected(FunctionalTester $I)
    {
        $I->am('Admin');
        $I->wantTo('update an operator with no country selected');
        $I->expectTo('see an error message explaining that the operator must have a country');

        /***************************************************************************************************************
         * settings
         **************************************************************************************************************/
        // we create the admin role
        $admin = $this->_createAdminRole();
        // we attach it to the logged user
        $admin->users()->attach($this->_user);
        // we create the operator role
        $member = $this->_createUserRole();
        // we create another user
        $credentials = [
            'country_id' => $this->_country->id,
            'last_name'  => 'Autre',
            'first_name' => 'autre',
            'email'      => 'autre@autre.fr',
            'password'   => 'autre',
        ];
        $user = \Sentinel::register($credentials, true);
        $member->users()->attach($user);

        /***************************************************************************************************************
         * run test
         **************************************************************************************************************/
        $I->amOnPage('/');
        $I->amOnRoute('users.edit', ['id' => $user->id]);
        $I->selectOption('country_id', null);
        $I->click(trans('users.page.action.edit'));
        $I->see(trans('global.modal.alert.title.error'), 'h3');
        $I->see(strip_tags(trans('validation.required', ['attribute' => trans('validation.attributes.country_id')])));
    }

    public function update_user_with_admin_role_and_no_country_selected(FunctionalTester $I)
    {
        $I->am('Admin');
        $I->wantTo('update an admin with no country selected');
        $I->expectTo('see a success confirmation message');

        /***************************************************************************************************************
         * settings
         **************************************************************************************************************/
        // we create the admin role
        $admin = $this->_createAdminRole();
        // we attach it to the logged user
        $admin->users()->attach($this->_user);
        // we create another user
        $credentials = [
            'country_id' => $this->_country->id,
            'last_name'  => 'Autre',
            'first_name' => 'autre',
            'email'      => 'autre@autre.fr',
            'password'   => 'autre',
        ];
        $user = \Sentinel::register($credentials, true);
        $admin->users()->attach($user);

        /***************************************************************************************************************
         * run test
         **************************************************************************************************************/
        $I->amOnPage('/');
        $I->amOnRoute('users.edit', ['id' => $user->id]);
        $I->selectOption('country_id', null);
        $I->click(trans('users.page.action.edit'));
        $I->see(trans('global.modal.alert.title.success'), 'h3');
        $I->see(strip_tags(trans('users.message.update.success', ['name' => $user->first_name . ' ' . $user->last_name])));
    }

    public function update_user_with_higher_role(FunctionalTester $I)
    {
        $I->am('Admin');
        $I->wantTo('update a user with a higher role than mine');
        $I->expectTo('see an error message explaining that I do not have the authorization');

        /***************************************************************************************************************
         * settings
         **************************************************************************************************************/
        // we create the operator role
        $member = $this->_createUserRole();
        // we attach it the the logged user
        $member->users()->attach($this->_user);
        // we create another user
        $credentials = [
            'country_id' => $this->_country->id,
            'last_name'  => 'Autre',
            'first_name' => 'autre',
            'email'      => 'autre@autre.fr',
            'password'   => 'autre',
        ];
        $user = \Sentinel::register($credentials, true);
        // we attach the user to the operator role
        $member->users()->attach($user);
        // we create the admin role
        $admin = $this->_createAdminRole();

        /***************************************************************************************************************
         * run test
         **************************************************************************************************************/
        $I->amOnPage('/');
        $I->amOnRoute('users.edit', ['id' => $user->id]);
        $member->users()->detach($user);
        $admin->users()->attach($user);
        $I->click(trans('users.page.action.edit'));
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
        // we create the operator role
        $member = $this->_createUserRole();
        // we attach it to the logged user
        $member->users()->attach($this->_user);
        // we create another user
        $credentials = [
            'country_id' => $this->_country->id,
            'last_name'  => 'Autre',
            'first_name' => 'autre',
            'email'      => 'autre@autre.fr',
            'password'   => 'autre',
        ];
        $user = \Sentinel::register($credentials, true);
        $member->users()->attach($user);
        // we create the admin role
        $admin = $this->_createAdminRole();

        /***************************************************************************************************************
         * run test
         **************************************************************************************************************/
        $I->amOnPage('/');
        $I->amOnRoute('users.edit', ['id' => $user->id]);
        $I->selectOption('role', $admin->id);
        $I->click(trans('users.page.action.edit'));
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
        $I->seeCurrentRouteIs('epi_warning_system.index');
        $I->click(trans('template.back.header.my_profile'));
        $I->seeCurrentRouteIs('users.profile');
        $I->see(trans('breadcrumbs.admin'), '.breadcrumb');
        $I->seeLink(trans('breadcrumbs.admin'), route('home'));
        $I->see(strip_tags(trans('breadcrumbs.users.profile')), '.breadcrumb');
        $I->assertFileNotExists('photo');
        $I->seeInField('last_name', $this->_user->last_name);
        $I->seeInField('first_name', $this->_user->first_name);
        $I->dontSee(trans('users.page.label.country'));
        $I->seeInField('email', $this->_user->email);
        $I->dontSee(trans('users.page.label.role'));
        $I->dontSee(trans('users.page.label.activation'));
        $I->seeInField('password', '');
        $I->seeInField('password_confirmation', '');
    }

    public function update_my_personal_profile_without_permission(FunctionalTester $I)
    {
        $I->am('Admin');
        $I->wantTo('update my profile without the permission');
        $I->expectTo('see an error message explaining that I do not have the authorization');

        /***************************************************************************************************************
         * settings
         **************************************************************************************************************/
        // we create the admin role
        $admin = $this->_createAdminRole();
        // we remove all its permissions
        $admin->permissions = [];
        $admin->save();
        // we attach it to the logged user
        $admin->users()->attach($this->_user);

        /***************************************************************************************************************
         * run test
         **************************************************************************************************************/
        $I->amOnPage('/');
        $I->amOnRoute('users.profile', ['id' => $this->_user->id]);
        $I->see(trans('users.page.title.profile'), 'h2');
        $I->click(trans('global.action.save'));
        $I->seeCurrentRouteIs('epi_warning_system.index');
        $I->see(trans('global.modal.alert.title.error'), 'h3');
        $I->see(strip_tags(trans('permissions.message.access.denied', ['permission' => trans('permissions.users.update')])));
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
        $admin = $this->_createAdminRole();
        // we attach it to the logged user
        $admin->users()->attach($this->_user);

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
        $this->_user->fresh();
        $I->seeRecord('users', [
            'country_id' => $this->_user->country_id,
            'last_name'  => $this->_user->last_name,
            'first_name' => $this->_user->first_name,
            'email'      => $this->_user->email,
        ]);
        $I->assertTrue(Hash::check($this->_credentials['password'], $this->_user->password));
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
        $admin = $this->_createAdminRole();
        // we attach it to the logged user
        $admin->users()->attach($this->_user);

        /***************************************************************************************************************
         * run test
         **************************************************************************************************************/
        $I->amOnPage('/');
        $I->amOnRoute('users.profile', ['id' => $this->_user->id]);
        $I->see(trans('users.page.title.profile'), 'h2');


        $I->fillField('last_name', 'COUCOU');
        $I->fillField('first_name', 'Coucou');
        $I->fillField('email', 'another@email.fr');
        $I->fillField('password', 'motdepasse');
        $I->fillField('password_confirmation', 'motdepasse');
        $I->click(trans('global.action.save'));
        $I->seeCurrentRouteIs('users.profile', ['id' => $this->_user->id]);
        $I->see(trans('global.modal.alert.title.success'), 'h3');
        $I->see(trans('users.message.account.success'));
        $this->_user->fresh();
        $I->seeRecord('users', [
            'country_id' => $this->_user->country_id,
            'last_name'  => 'COUCOU',
            'first_name' => 'Coucou',
            'email'      => 'another@email.fr',
        ]);
        $I->assertTrue(Hash::check($this->_credentials['password'], $this->_user->password));
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
        $admin = $this->_createAdminRole();
        // we remove all its permissions
        $admin->permissions = [];
        $admin->save();
        // we attach it to the logged user
        $admin->users()->attach($this->_user);
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
        $admin = $this->_createAdminRole();
        // we attach it to the logged user
        $admin->users()->attach($this->_user);

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
        $admin = $this->_createAdminRole();
        $admin->users()->attach($this->_user);
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
        // we create the operator role
        $member = $this->_createUserRole();
        // we attach it the the logged user
        $member->users()->attach($this->_user);
        // we create another user
        $credentials = [
            'country_id' => $this->_country->id,
            'last_name'  => 'Autre',
            'first_name' => 'autre',
            'email'      => 'autre@autre.fr',
            'password'   => 'autre',
        ];
        $user = \Sentinel::register($credentials, true);
        // we create the admin role
        $admin = $this->_createAdminRole();
        // we attach it to the new user
        $admin->users()->attach($user);

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
        $admin = $this->_createAdminRole();
        // we remove all its permissions
        $admin->permissions = [];
        $admin->save();
        // we attach it to the logged user
        $admin->users()->attach($this->_user);
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
        $admin = $this->_createAdminRole();
        // we attach it to the logged user
        $admin->users()->attach($this->_user);
        // we deactivate the country
        $this->_country->active = false;
        $this->_country->save();

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
        $admin = $this->_createAdminRole();
        $admin->users()->attach($this->_user);
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
        $I->see(strip_tags(trans('users.message.activation.success.label', ['action' => trans_choice('users.message.activation.success.action', true), 'name' => $user->first_name . ' ' . $user->last_name])));
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
        // we create the operator role
        $member = $this->_createUserRole();
        // we attach it the the logged user
        $member->users()->attach($this->_user);
        // we create another user
        $credentials = [
            'country_id' => $this->_country->id,
            'last_name'  => 'Autre',
            'first_name' => 'autre',
            'email'      => 'autre@autre.fr',
            'password'   => 'autre',
        ];
        $user = \Sentinel::register($credentials);
        // we create the admin role
        $admin = $this->_createAdminRole();
        // we attach it to the new user
        $admin->users()->attach($user);

        /***************************************************************************************************************
         * run test
         **************************************************************************************************************/
        $this->_user->addPermission('users.list', true)->save();
        $I->amOnPage('/');
        $I->amOnRoute('users.index');
        $I->checkOption('#activate_' . $user->id);
        $I->submitForm('#form_activate_' . $user->id, []);
        $I->seeResponseCodeIs(401);
        $I->see(trans('users.message.permission.action.edit'));
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
        $admin = $this->_createAdminRole();
        $admin->users()->attach($this->_user);
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
        $I->see(strip_tags(trans('users.message.activation.success.label', ['action' => trans_choice('users.message.activation.success.action', false), 'name' => $user->first_name . ' ' . $user->last_name])));
        $I->dontSeeRecord('activations', [
            'user_id'   => $user->id,
            'completed' => true,
        ]);
    }
}
