<?php

class PermissionCest
{
    private $_user;
    private $_credentials;

    public function _before(FunctionalTester $I)
    {
        // we set the credentials
        $this->_credentials = [
            'last_name'  => 'Test',
            'first_name' => 'test',
            'email'      => 'test@test.fr',
            'status'     => config('user.status_key.communication_commission'),
            'board'      => config('user.board_key.leading_board'),
            'password'   => 'test',
        ];

        // we create the user
        $this->_user = \Sentinel::register($this->_credentials, true);

        // we log the user
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
        $operator = \Sentinel::getRoleRepository()->createModel()->create([
            'slug'     => 'user',
            'position' => 2,
        ]);
        // we translate the translatable fields
        $operator->translateOrNew('fr')->name = 'Utilisateur';
        $operator->translateOrNew('en')->name = 'User';
        // we save the changes
        $operator->save();

        return $operator;
    }


    /**<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<
     * -----------------------------------------------------------------------------------------------------------------
     * TESTS
     * -----------------------------------------------------------------------------------------------------------------
     * >>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>*/

    public function access_to_role_list_without_permission(FunctionalTester $I)
    {
        $I->am('A user with no role');
        $I->wantTo('access to the roles list without the permission');
        $I->expectTo('see an error message explaining that I do not have the permission to access this page');

        /***************************************************************************************************************
         * run test
         **************************************************************************************************************/
        $I->amOnPage('/');
        $I->amOnRoute('permissions.index');
        $I->seeCurrentRouteIs('dashboard.index');
        $I->see(trans('global.modal.alert.title.error'), 'h3');
        $I->see(strip_tags(trans('permissions.message.access.denied', ['permission' => trans('permissions.permissions.list')])));
    }

    public function access_to_role_list(FunctionalTester $I)
    {
        $I->am('Admin');
        $I->wantTo('access to the permissions list');
        $I->expectTo('see the permissions list');

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
        $I->seeCurrentRouteIs('home');
        $I->click(trans('template.front.header.my_account'));
        $I->seeCurrentRouteIs('dashboard.index');
        $I->click(trans('template.back.header.permissions'));
        $I->seeCurrentRouteIs('permissions.index');
        $I->see(trans('breadcrumbs.admin'), '.breadcrumb');
        $I->seeLink(trans('breadcrumbs.admin'), route('dashboard.index'));
        $I->see(trans('breadcrumbs.permissions.index'), '.breadcrumb');
        $I->see(trans('permissions.page.title.management'));
        $I->see(trans('permissions.page.title.list'));
        $I->see($admin->name, '.table-list');
        $I->see($admin->slug, '.table-list');
        $I->see($admin->position, '.table-list');
        $I->see(Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $admin->created_at)->format('d/m/Y H:i:s'), '.table-list');
        $I->see(Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $admin->updated_at)->format('d/m/Y H:i:s'), '.table-list');
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
        // we create the admin role
        $admin = $this->_createAdminRole();
        // we attach it to the logged user
        $admin->users()->attach($this->_user);
        // we create another role
        $operator = $this->_createUserRole();

        /***************************************************************************************************************
         * run test
         **************************************************************************************************************/
        $I->amOnRoute('permissions.index');
        $I->see(strip_tags(trans('global.table_list.results.status', ['start' => 1, 'stop' => 2, 'total' => 2])));
        $I->see($admin->name, '.table-list');
        $I->see($admin->slug, '.table-list');
        $I->see($operator->name, '.table-list');
        $I->see($operator->slug, '.table-list');
        $I->seeInField('#input_lines', config('tablelist.default.lines'));
        $I->seeInField('#input_search', '');
        $I->fillField('#input_lines', 1);
        $I->submitForm('#line_search_form', []);
        $I->see($admin->name, '.table-list');
        $I->see($admin->slug, '.table-list');
        $I->dontSee($operator->name, '.table-list');
        $I->dontSee($operator->slug, '.table-list');
        $I->see(strip_tags(trans('global.table_list.results.status', ['start' => 1, 'stop' => 1, 'total' => 2])));
    }

    public function search_role(FunctionalTester $I)
    {
        $I->am('Admin');
        $I->wantTo('search a role');
        $I->expectTo('see the searched role');

        /***************************************************************************************************************
         * settings
         **************************************************************************************************************/
        // we create the admin role
        $admin = $this->_createAdminRole();
        // we attach it to the logged user
        $admin->users()->attach($this->_user);
        // we create another role
        $operator = $this->_createUserRole();

        /***************************************************************************************************************
         * run test
         **************************************************************************************************************/
        $I->amOnRoute('permissions.index');
        $I->see(strip_tags(trans('global.table_list.results.status', ['start' => 1, 'stop' => 2, 'total' => 2])));
        $I->see($admin->name, '.table-list');
        $I->see($admin->slug, '.table-list');
        $I->see($operator->name, '.table-list');
        $I->see($operator->slug, '.table-list');
        $I->seeInField('#input_lines', config('tablelist.default.lines'));
        $I->seeInField('#input_search', '');
        $I->fillField('#input_search', 'Uti');
        $I->submitForm('#line_search_form', []);
        $I->dontSee($admin->name, '.table-list');
        $I->dontSee($admin->slug, '.table-list');
        $I->see($operator->name, '.table-list');
        $I->see($operator->slug, '.table-list');
        $I->see(strip_tags(trans('global.table_list.results.status', ['start' => 1, 'stop' => 1, 'total' => 1])));
    }

    public function access_to_role_add_page_without_permission(FunctionalTester $I)
    {
        $I->am('A user with no role');
        $I->wantTo('access to the role add page without the permission');
        $I->expectTo('see an error message explaining that I do not have the permission to access this page');

        /***************************************************************************************************************
         * run test
         **************************************************************************************************************/
        $I->amOnPage('/');
        $I->amOnRoute('permissions.create');
        $I->seeCurrentRouteIs('dashboard.index');
        $I->see(trans('global.modal.alert.title.error'), 'h3');
        $I->see(strip_tags(trans('permissions.message.access.denied', ['permission' => trans('permissions.permissions.create')])));
    }

    public function access_to_role_add_page(FunctionalTester $I)
    {
        $I->am('Admin');
        $I->wantTo('access to the permissions add page');
        $I->expectTo('see the permissions add page with blank fields');

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
        $I->seeCurrentRouteIs('home');
        $I->click(trans('template.front.header.my_account'));
        $I->seeCurrentRouteIs('dashboard.index');
        $I->click(trans('template.back.header.permissions'));
        $I->seeCurrentRouteIs('permissions.index');
        $I->click(trans('global.action.add'));
        $I->seeCurrentRouteIs('permissions.create');
        $I->see(trans('breadcrumbs.admin'), '.breadcrumb');
        $I->seeLink(trans('breadcrumbs.admin'), route('home'));
        $I->see(strip_tags(trans('breadcrumbs.permissions.create')), '.breadcrumb');
        $I->seeLink(trans('breadcrumbs.permissions.index'), route('permissions.index'));
        $I->see(trans('permissions.page.title.create'), 'h2');
        $I->seeInField('name_fr', '');
        $I->seeInField('name_en', '');
        $I->seeInField('slug', '');
        $I->seeInField('position', '');
        $I->seeInField('parent_role_id', 0);
    }

    public function access_to_role_add_page_and_cancel(FunctionalTester $I)
    {
        $I->am('Admin');
        $I->wantTo('access to the role add page and cancel');
        $I->expectTo('land on the role list page');

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
        $I->seeCurrentRouteIs('home');
        $I->click(trans('template.front.header.my_account'));
        $I->seeCurrentRouteIs('dashboard.index');
        $I->click(trans('template.back.header.permissions'));
        $I->seeCurrentRouteIs('permissions.index');
        $I->click(trans('global.action.add'));
        $I->seeCurrentRouteIs('permissions.create');
        $I->click(trans('global.action.cancel'));
        $I->seeCurrentRouteIs('permissions.index');
    }

    public function create_role_without_permission(FunctionalTester $I)
    {
        $I->am('A user with no role');
        $I->wantTo('create a new role without the permission');
        $I->expectTo('see an error message explaining that I do have the permission to create a new role');

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
        $this->_user->addPermission('permissions.create', true)->save();
        $I->amOnPage('/');
        $I->amOnRoute('permissions.create');
        $I->see(trans('permissions.page.title.create'), 'h2');
        $this->_user->removePermission('permissions.create')->save();
        $I->click(trans('permissions.page.action.create'));
        $I->seeCurrentRouteIs('dashboard.index');
        $I->see(trans('global.modal.alert.title.error'), 'h3');
        $I->see(strip_tags(trans('permissions.message.access.denied', ['permission' => trans('permissions.permissions.create')])));
    }

    public function create_role_with_no_field_filled(FunctionalTester $I)
    {
        $I->am('Admin');
        $I->wantTo('create role with no fields filled');
        $I->expectTo('see an error message explaining that some fields are missing');

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
        $I->amOnRoute('permissions.create');
        $I->see(trans('permissions.page.title.create'));
        $I->click(trans('permissions.page.action.create'));
        $I->seeCurrentRouteIs('permissions.create');
        $I->see(trans('global.modal.alert.title.error'), 'h3');
        $I->see(strip_tags(trans('validation.filled', ['attribute' => trans('validation.attributes.name_fr')])));
        $I->see(strip_tags(trans('validation.filled', ['attribute' => trans('validation.attributes.slug')])));
    }

    public function create_role(FunctionalTester $I)
    {
        $I->am('Admin');
        $I->wantTo('create role with fields correctly filled');
        $I->expectTo('see success confirmation message');

        /***************************************************************************************************************
         * settings
         **************************************************************************************************************/
        // we create the admin role
        $admin = $this->_createAdminRole();
        // we attach it to the logged user
        $admin->users()->attach($this->_user);
        // we create another role
        $operator = $this->_createUserRole();

        /***************************************************************************************************************
         * run test
         **************************************************************************************************************/
        $I->amOnRoute('permissions.create');
        $I->see(trans('permissions.page.title.create'));
        $I->fillField('name_fr', 'Modérateur');
        $I->fillField('slug', 'moderator');
        $I->selectOption('parent_role_id', $admin->id);
        $I->checkOption('#settings');
        $I->checkOption('#settings' . config('permissions.separator') . 'view');
        $I->checkOption('#settings' . config('permissions.separator') . 'update');
        $I->click(trans('permissions.page.action.create'));
        $I->see(trans('global.modal.alert.title.success'), 'h3');
        $I->see(strip_tags(trans('permissions.message.creation.success', ['name' => 'Modérateur'])));
        $I->seeCurrentRouteIs('permissions.index');
        $I->see('Moderator', '.table-list');
        $I->see('moderator', '.table-list');
        $I->see('Moderator');
        $I->seeRecord('roles', [
            'slug'        => 'moderator',
            'position'    => 2,
            'permissions' => '{"settings":true,"settings.view":true,"settings.update":true}',
        ]);
    }

    public function access_to_role_edit_page_without_permission(FunctionalTester $I)
    {
        $I->am('A user with no role');
        $I->wantTo('access to the country edit page');
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
        $I->amOnRoute('permissions.edit', ['id' => $admin->id]);
        $I->seeCurrentRouteIs('dashboard.index');
        $I->see(trans('global.modal.alert.title.error'), 'h3');
        $I->see(strip_tags(trans('permissions.message.access.denied', ['permission' => trans('permissions.permissions.view')])));
    }

    public function access_to_role_edit_page_with_wrong_id(FunctionalTester $I)
    {
        $I->am('Admin');
        $I->wantTo('access to role edit page with a wrong id');
        $I->expectTo('see an error message explaining the role doesn\'t exists and be redirected to the roles list');

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
        $I->amOnRoute('permissions.edit', ['id' => 1000000000000]);
        $I->seeCurrentRouteIs('permissions.index');
        $I->see(trans('global.modal.alert.title.error'), 'h3');
        $I->see(strip_tags(trans('permissions.message.find.failure', ['id' => 1000000000000])));
    }

    public function access_to_role_edit_page(FunctionalTester $I)
    {
        $I->am('Admin');
        $I->wantTo('access to permission edit page');
        $I->expectTo('see the permission edit page with fields filled with the role data');

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
        $I->seeCurrentRouteIs('home');
        $I->click(trans('template.front.header.my_account'));
        $I->seeCurrentRouteIs('dashboard.index');
        $I->click(trans('template.back.header.permissions'));
        $I->seeCurrentRouteIs('permissions.index');
        $I->click('edit_' . $admin->id);
        $I->seeCurrentRouteIs('permissions.edit', ['id' => $admin->id]);
        $I->see(trans('breadcrumbs.admin'), '.breadcrumb');
        $I->seeLink(trans('breadcrumbs.admin'), route('home'));
        $I->see(strip_tags(trans('breadcrumbs.permissions.edit', ['role' => $admin->name])), '.breadcrumb');
        $I->seeLink(trans('breadcrumbs.permissions.index'), route('permissions.index'));
        $I->see(strip_tags(trans('permissions.page.title.edit', ['role' => $admin->name])), 'h2');
        $I->seeInField('name_fr', $admin->translate('fr')->name);
        $I->seeInField('name_en', $admin->translate('en')->name);
        $I->seeInField('slug', $admin->slug);
        $I->seeInField('position', $admin->position);
        $I->seeInField('parent_role_id', 0);
    }

    public function access_to_role_edit_page_and_go_back(FunctionalTester $I)
    {
        $I->am('Admin');
        $I->wantTo('access to the role edit page and go back');
        $I->expectTo('land on the role list page');

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
        $I->seeCurrentRouteIs('home');
        $I->click(trans('template.front.header.my_account'));
        $I->seeCurrentRouteIs('dashboard.index');
        $I->click(trans('template.back.header.permissions'));
        $I->seeCurrentRouteIs('permissions.index');
        $I->click('edit_' . $admin->id);
        $I->seeCurrentRouteIs('permissions.edit', ['id' => $admin->id]);
        $I->click(trans('global.action.back'), '#content');
        $I->seeCurrentRouteIs('permissions.index');
    }

    public function update_role_without_permission(FunctionalTester $I)
    {
        $I->am('A user with no role');
        $I->wantTo('update a role without the permission');
        $I->expectTo('see an error message explaining that I do not have the permission to update the role');

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
        $this->_user->addPermission('permissions.view', true)->save();
        $I->amOnPage('/');
        $I->amOnRoute('permissions.edit', ['id' => $admin->id]);
        $I->click(trans('permissions.page.action.edit'));
        $I->seeCurrentRouteIs('permissions.edit', ['id' => $admin->id]);
        $I->see(trans('global.modal.alert.title.error'), 'h3');
        $I->see(strip_tags(trans('permissions.message.access.denied', ['permission' => trans('permissions.permissions.update')])));
    }

    public function update_role_with_wrong_id(FunctionalTester $I)
    {
        $I->am('A user with no role');
        $I->wantTo('update a role with a wrong id');
        $I->expectTo('see an error message explaining the role doesn\'t exists and be redirected to the roles list');

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
        $I->amOnRoute('permissions.edit', ['id' => 1000000000000]);
        $I->sendAjaxRequest('POST', route('permissions.update', ['id' => 1000000000000, '_token' => csrf_token(), '_method' => 'PUT']));
        $I->seeCurrentRouteIs('permissions.index');
        $I->see(trans('global.modal.alert.title.error'));
        $I->see(strip_tags(trans('permissions.message.find.failure', ['id' => 1000000000000])));
    }

    public function update_permission_with_no_change(FunctionalTester $I)
    {
        $I->am('Admin');
        $I->wantTo('update a role without changing any role data');
        $I->expectTo('see a success confirmation message and see that the role data are the same');

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
        $I->amOnRoute('permissions.edit', ['id' => $admin->id]);
        $I->seeInField('name_fr', $admin->translate('fr')->name);
        $I->seeInField('slug', $admin->slug);
        $I->seeInField('position', $admin->position);
        $I->seeInField('parent_role_id', 0);
        $I->click(trans('permissions.page.action.edit'));
        $I->seeCurrentRouteIs('permissions.edit', ['id' => $admin->id]);
        $I->see(trans('global.modal.alert.title.success'), 'h3');
        $I->see(strip_tags(trans('permissions.message.update.success', ['name' => $admin->name])));
    }

    public function update_permission_with_no_data(FunctionalTester $I)
    {
        $I->am('Admin');
        $I->wantTo('update a role with no data');
        $I->expectTo('see an error message explaining that the fields are required');

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
        $I->amOnRoute('permissions.edit', $admin->id);
        $I->fillField('name_fr', null);
        $I->fillField('slug', null);
        $I->click(trans('permissions.page.action.edit'));
        $I->seeCurrentRouteIs('permissions.edit', ['id' => $admin->id]);
        $I->see(trans('global.modal.alert.title.error'), 'h3');
        $I->see(strip_tags(trans('validation.required', ['attribute' => trans('validation.attributes.name_fr')])));
        $I->see(strip_tags(trans('validation.required', ['attribute' => trans('validation.attributes.slug')])));
    }

    public function update_permission_with_wrong_data(FunctionalTester $I)
    {
        $I->am('Admin');
        $I->wantTo('update a role with wrong data');
        $I->expectTo('see an error message explaining that the data are not correct');

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
        $I->amOnRoute('permissions.edit', $admin->id);
        $I->fillField('name_fr', 123);
        $I->fillField('name_en', 456);
        $I->fillField('slug', 'test slug');
        $I->click(trans('permissions.page.action.edit'));
        $I->seeCurrentRouteIs('permissions.edit', ['id' => $admin->id]);
        $I->see(trans('global.modal.alert.title.error'), 'h3');
        $I->see(strip_tags(trans('validation.string', ['attribute' => trans('validation.attributes.name_fr')])));
        $I->see(strip_tags(trans('validation.alpha_dash', ['attribute' => trans('validation.attributes.slug')])));
    }

    public function update_role_with_existing_slug(FunctionalTester $I)
    {
        $I->am('Admin');
        $I->wantTo('update a role with an existing slug');
        $I->expectTo('see an error message explaining that the slug already exists');

        /***************************************************************************************************************
         * settings
         **************************************************************************************************************/
        // we create the admin role
        $admin = $this->_createAdminRole();
        // we attach it to the logged user
        $admin->users()->attach($this->_user);
        // we create another role
        $operator = $this->_createUserRole();

        /***************************************************************************************************************
         * run test
         **************************************************************************************************************/
        $I->amOnPage('/');
        $I->amOnRoute('permissions.edit', $admin->id);
        $I->fillField('slug', $operator->slug);
        $I->click(trans('permissions.page.action.edit'));
        $I->seeCurrentRouteIs('permissions.edit', ['id' => $admin->id]);
        $I->see(trans('global.modal.alert.title.error'), 'h3');
        $I->see(strip_tags(trans('validation.unique', ['attribute' => trans('validation.attributes.slug')])));
    }

    public function update_role_with_data_changes(FunctionalTester $I)
    {
        $I->am('Admin');
        $I->wantTo('update a role with no data');
        $I->expectTo('see an error message explaining that the fields are required');

        /***************************************************************************************************************
         * settings
         **************************************************************************************************************/
        // we create the admin role
        $admin = $this->_createAdminRole();
        // we attach it to the logged user
        $admin->users()->attach($this->_user);
        // we create another role
        $operator = $this->_createUserRole();

        /***************************************************************************************************************
         * run test
         **************************************************************************************************************/
        $I->amOnPage('/');
        $I->amOnRoute('permissions.edit', ['id' => $operator->id]);
        $I->fillField('name_fr', 'Test FR');
        $I->fillField('slug', 'new_slug');
        $I->selectOption('parent_role_id', $admin->id);
        $I->checkOption('#settings' . config('permissions.separator') . 'view');
        $I->checkOption('#permissions');
        $I->checkOption('#permissions' . config('permissions.separator') . 'list');
        $I->checkOption('#permissions' . config('permissions.separator') . 'create');
        $I->checkOption('#permissions' . config('permissions.separator') . 'view');
        $I->checkOption('#permissions' . config('permissions.separator') . 'update');
        $I->checkOption('#permissions' . config('permissions.separator') . 'delete');
        $I->checkOption('#users');
        $I->checkOption('#users' . config('permissions.separator') . 'list');
        $I->checkOption('#users' . config('permissions.separator') . 'create');
        $I->checkOption('#users' . config('permissions.separator') . 'view');
        $I->checkOption('#users' . config('permissions.separator') . 'update');
        $I->checkOption('#users' . config('permissions.separator') . 'delete');
        $I->click(trans('permissions.page.action.edit'));
        $I->seeCurrentRouteIs('permissions.edit', ['id' => $operator->id]);
        $I->see(trans('global.modal.alert.title.success'), 'h3');
        $I->see(strip_tags(trans('permissions.message.update.success', ['name' => 'Test FR'])));
        $I->seeInField('name_fr', 'Test FR');
        $I->seeInField('slug', 'new-slug');
        $I->seeInField('position', 2);
        $I->seeOptionIsSelected('#input_parent_role', '1 - ' . $admin->name);
        $I->seeCheckboxIsChecked('#settings' . config('permissions.separator') . 'view');
        $I->seeCheckboxIsChecked('#permissions');
        $I->seeCheckboxIsChecked('#permissions' . config('permissions.separator') . 'list');
        $I->seeCheckboxIsChecked('#permissions' . config('permissions.separator') . 'create');
        $I->seeCheckboxIsChecked('#permissions' . config('permissions.separator') . 'view');
        $I->seeCheckboxIsChecked('#permissions' . config('permissions.separator') . 'update');
        $I->seeCheckboxIsChecked('#permissions' . config('permissions.separator') . 'delete');
        $I->seeCheckboxIsChecked('#users');
        $I->seeCheckboxIsChecked('#users' . config('permissions.separator') . 'list');
        $I->seeCheckboxIsChecked('#users' . config('permissions.separator') . 'create');
        $I->seeCheckboxIsChecked('#users' . config('permissions.separator') . 'view');
        $I->seeCheckboxIsChecked('#users' . config('permissions.separator') . 'update');
        $I->seeCheckboxIsChecked('#users' . config('permissions.separator') . 'delete');
        $I->seeRecord('roles', [
            'id'          => $operator->id,
            'slug'        => 'new-slug',
            'position'    => 2,
            'permissions' => '{"permissions":true,"users":true,"settings.view":true,"permissions.list":true,"permissions.create":true,"permissions.view":true,"permissions.update":true,"permissions.delete":true,"users.list":true,"users.create":true,"users.view":true,"users.update":true,"users.delete":true}',
        ]);
    }

    public function update_current_user_and_remove_forbidden_permissions(FunctionalTester $I)
    {
        $I->am('Admin');
        $I->wantTo('update the current user and remove the permissions list / view / update authorizations');
        $I->expectTo('see an error message explaining that we can\'t remove our own permissions list / view / update authorizations');

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
        $I->amOnRoute('permissions.edit', ['id' => $admin->id]);
        $I->uncheckOption('#permissions');
        $I->uncheckOption('#permissions' . config('permissions.separator') . 'list');
        $I->uncheckOption('#permissions' . config('permissions.separator') . 'create');
        $I->uncheckOption('#permissions' . config('permissions.separator') . 'view');
        $I->uncheckOption('#permissions' . config('permissions.separator') . 'update');
        $I->uncheckOption('#permissions' . config('permissions.separator') . 'delete');
        $I->click(trans('permissions.page.action.edit'));
        $I->seeCurrentRouteIs('permissions.edit', ['id' => $admin->id]);
        $I->see(trans('global.modal.alert.title.error'), 'h3');
        $I->see(trans('permissions.message.update.denied'));
        $I->see(trans('permissions.permissions.list'));
        $I->see(trans('permissions.permissions.view'));
        $I->see(trans('permissions.permissions.update'));
    }

    public function delete_role_without_permission(FunctionalTester $I)
    {
        $I->am('A user with no role');
        $I->wantTo('delete a role without the permission');
        $I->expectTo('see an error message explaining that I do have the permission to delete the role');

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
        $this->_user->addPermission('permissions.list', true)->save();
        $I->amOnPage('/');
        $I->amOnRoute('permissions.index');
        $I->submitForm('#delete_' . $admin->id, []);
        $I->seeCurrentRouteIs('permissions.index');
        $I->see(trans('global.modal.alert.title.error'), 'h3');
        $I->see(strip_tags(trans('permissions.message.access.denied', ['permission' => trans('permissions.permissions.delete')])));
    }

    public function delete_role_with_wrong_id(FunctionalTester $I)
    {
        $I->am('A user with no role');
        $I->wantTo('delete a role with a wrong id');
        $I->expectTo('see an error message explaining the role doesn\'t exists and be redirected to the roles list');

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
        $I->amOnRoute('permissions.index');
        $I->sendAjaxRequest('POST', route('permissions.destroy', ['id' => 1000000000000, '_token' => csrf_token(), '_method' => 'DELETE']));
        $I->seeCurrentRouteIs('permissions.index');
        $I->see(trans('global.modal.alert.title.error'));
        $I->see(strip_tags(trans('permissions.message.find.failure', ['id' => 1000000000000])));
    }

    public function delete_role(FunctionalTester $I)
    {
        $I->am('Admin');
        $I->wantTo('delete a role');
        $I->expectTo('see a success confirmation message and see that the deleted role is not here anymore');

        /***************************************************************************************************************
         * settings
         **************************************************************************************************************/
        // we create the admin role
        $admin = $this->_createAdminRole();
        // we attach it to the logged user
        $admin->users()->attach($this->_user);
        // we create another role
        $operator = $this->_createUserRole();

        $I->amOnRoute('permissions.index');
        $I->submitForm('#delete_' . $operator->id, []);
        $I->seeCurrentRouteIs('permissions.index');
        $I->see(trans('global.modal.alert.title.success'), 'h3');
        $I->see(strip_tags(trans('permissions.message.delete.success', ['name' => $operator->name])));
        $I->dontSee($operator->name, '.table-list');
    }

    public function delete_current_role_denied(FunctionalTester $I)
    {
        $I->am('Admin');
        $I->wantTo('delete the Admin role');
        $I->expectTo('see an error message explaining that deleting the role we use is not possible');

        $admin = $this->_createAdminRole();
        $admin->users()->attach($this->_user);

        $I->amOnRoute('permissions.index');
        $I->submitForm('#delete_' . $admin->id, []);
        $I->seeCurrentRouteIs('permissions.index');
        $I->see(trans('global.modal.alert.title.error'), 'h3');
        $I->see(strip_tags(trans('permissions.message.delete.denied', ['name' => $admin->name])));
        $I->see($admin->name, '.table-list');
    }

}
