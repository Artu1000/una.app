<?php

class AuthCest
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
            'password'   => 'test',
        ];
        $this->_user = \Sentinel::register($this->_credentials, true);
    }

    public function _after(FunctionalTester $I)
    {
    }


    /**<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<
     * -----------------------------------------------------------------------------------------------------------------
     * TESTS
     * -----------------------------------------------------------------------------------------------------------------
     * >>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>*/

    public function log_with_no_filled_credentials(FunctionalTester $I)
    {
        $I->am('Unlogged user');
        $I->wantTo('Try to log with no credentials filled');
        $I->expectTo('see an error message explaining that the e-mail and the password are required');

        /***************************************************************************************************************
         * run test
         **************************************************************************************************************/
        $I->amOnPage('/');
        $I->seeCurrentRouteIs('home');
        $I->click(trans('template.front.header.my_account'));
        $I->seeCurrentRouteIs('login.index');
        $I->click(trans('auth.login.action.login'));
        $I->seeCurrentRouteIs('login.index');
        $I->see(trans('global.modal.alert.title.error'), 'h3');
        $I->see(strip_tags(trans('validation.filled', ['attribute' => trans('validation.attributes.email')])));
        $I->see(strip_tags(trans('validation.filled', ['attribute' => trans('validation.attributes.password')])));
    }

    public function log_with_incorrect_credentials(FunctionalTester $I)
    {
        $I->am('Unlogged user');
        $I->wantTo('log with incorrect credentials');
        $I->expectTo('see an error message explaining that the e-mail or the password is incorrect');

        /***************************************************************************************************************
         * run test
         **************************************************************************************************************/
        $I->amOnPage('/');
        $I->seeCurrentRouteIs('home');
        $I->click(trans('template.front.header.my_account'));
        $I->seeCurrentRouteIs('login.index');
        $I->fillField('email', 'wrong@test.fr');
        $I->fillField('password', 'truc');
        $I->click(trans('auth.login.action.login'));
        $I->seeCurrentRouteIs('login.index');
        $I->see(trans('global.modal.alert.title.error'), 'h3');
        $I->see(strip_tags(trans('auth.message.login.failure')));
    }

    public function log_with_correct_credentials_with_remember_not_checked(FunctionalTester $I)
    {
        $I->am('Unlogged user');
        $I->wantTo('log with correct credentials filled and the "Remember me" option not checked');
        $I->expectTo('have no remember cookie recorded');

        /***************************************************************************************************************
         * run test
         **************************************************************************************************************/
        $I->amOnPage('/');
        $I->seeCurrentRouteIs('home');
        $I->click(trans('template.front.header.my_account'));
        $I->seeCurrentRouteIs('login.index');
        $I->fillField('email', $this->_user->email);
        $I->fillField('password', 'test');
        $I->dontSeeCheckboxIsChecked('form input[name="remember"]');
        $I->click(trans('auth.login.action.login'));
        $I->seeCurrentRouteIs('dashboard.index');
        $I->see(trans('global.modal.alert.title.success'), 'h3');
        $I->see(strip_tags(trans('auth.message.login.success', ['name' => $this->_user->first_name . ' ' . $this->_user->last_name])));
        $I->dontSeeCookie('cartalyst_sentinel');
    }


    public function log_with_correct_credentials_with_remember_checked(FunctionalTester $I)
    {
        $I->am('Unlogged user');
        $I->wantTo('log with correct credentials filled and the "Remember me" checked');
        $I->expectTo('have a remember cookie recorded');

        $I->amOnPage('/');
        $I->seeCurrentRouteIs('home');
        $I->click(trans('template.front.header.my_account'));
        $I->seeCurrentRouteIs('login.index');
        $I->fillField('email', $this->_user->email);
        $I->fillField('password', 'test');
        $I->dontSeeCheckboxIsChecked('form input[name="remember"]');
        $I->checkOption('form input[name="remember"]');
        $I->canSeeCheckboxIsChecked('form input[name="remember"]');
        $I->click(trans('auth.login.action.login'));
        $I->seeCurrentRouteIs('dashboard.index');
        $I->see(trans('global.modal.alert.title.success'), 'h3');
        $I->see(strip_tags(trans('auth.message.login.success', ['name' => $this->_user->first_name . ' ' . $this->_user->last_name])));
        $I->seeCookie('cartalyst_sentinel');
    }

    public function unlog(FunctionalTester $I)
    {
        $I->am('Logged user');
        $I->wantTo('Unlog the current logged user by clicking on the unlog button.');
        $I->expectTo('be unlogged and redirected to the home page');

        /***************************************************************************************************************
         * run test
         **************************************************************************************************************/
        \Sentinel::authenticate($this->_credentials);
        $I->amOnPage('/');
        $I->seeCurrentRouteIs('home');
        $I->click(trans('template.front.header.my_account'));
        $I->seeCurrentRouteIs('dashboard.index');
        $I->click(trans('template.back.header.logout'));
        $I->seeCurrentRouteIs('home');
    }

    public function access_to_forgotten_password_page_and_go_back_to_parent_page(FunctionalTester $I)
    {
        $I->am('Unlogged user');
        $I->wantTo('access to the forgotten password page click to the cancel button to go back to the login page');
        $I->expectTo('land on the login page');

        /***************************************************************************************************************
         * run test
         **************************************************************************************************************/
        $I->amOnPage('/');
        $I->seeCurrentRouteIs('home');
        $I->click(trans('template.front.header.my_account'));
        $I->seeCurrentRouteIs('login.index');
        $I->click(trans('auth.login.label.forgotten_password'));
        $I->seeCurrentRouteIs('password.index');
        $I->click(trans('global.action.cancel'));
        $I->seeCurrentRouteIs('login.index');
    }

    public function get_forgotten_password_email_with_not_recorded_email(FunctionalTester $I)
    {
        $I->am('Unlogged user');
        $I->wantTo('receive my password reset email by using the forgotten password feature');
        $I->expectTo('see an error message saying that the e-mail does not exists');

        /***************************************************************************************************************
         * run test
         **************************************************************************************************************/
        $I->amOnPage('/');
        $I->seeCurrentRouteIs('home');
        $I->click(trans('template.front.header.my_account'));
        $I->seeCurrentRouteIs('login.index');
        $I->fillField('email', 'wrong@test.fr');
        $I->fillField('password', 'test');
        $I->click(trans('auth.login.action.login'));
        $I->seeCurrentRouteIs('login.index');
        $I->see(trans('global.modal.alert.title.error'));
        $I->see(strip_tags(trans('auth.message.login.failure')));
        $I->click(trans('auth.login.label.forgotten_password'));
        $I->seeCurrentRouteIs('password.index');
        $I->seeInField('email', 'wrong@test.fr');
        $I->click(trans('auth.password.forgotten.action.send'));
        $I->seeCurrentRouteIs('password.index');
        $I->see(trans('global.modal.alert.title.error'), 'h3');
        $I->see(strip_tags(trans('auth.message.find.failure', ['email' => 'wrong@test.fr'])));
    }

    public function get_forgotten_password_email_with_recorded_email(FunctionalTester $I)
    {
        $I->am('Unlogged user');
        $I->wantTo('receive my password reset email by using the forgotten password feature');
        $I->expectTo('see a success confirmation message explaining that the e-mail has been sent');

        /***************************************************************************************************************
         * settings
         **************************************************************************************************************/
        $I->resetEmails();

        /***************************************************************************************************************
         * run test
         **************************************************************************************************************/
        $I->amOnPage('/');
        $I->seeCurrentRouteIs('home');
        $I->click(trans('template.front.header.my_account'));
        $I->seeCurrentRouteIs('login.index');
        $I->fillField('email', $this->_user->email);
        $I->fillField('password', 'truc');
        $I->click(trans('auth.login.action.login'));
        $I->seeCurrentRouteIs('login.index');
        $I->see(trans('global.modal.alert.title.error'));
        $I->click(trans('auth.login.label.forgotten_password'));
        $I->seeCurrentRouteIs('password.index');
        $I->seeInField('email', $this->_user->email);
        $I->click(trans('auth.password.forgotten.action.send'));
        $I->see(trans('global.modal.alert.title.success'), 'h3');
        $I->see(strip_tags(trans('auth.message.password_reset.email.success', ['email' => 'test@test.fr'])));
        $I->seeCurrentRouteIs('login.index');
        $I->seeRecord('reminders', [
            'user_id'   => $this->_user->id,
            'completed' => false,
        ]);

        $I->seeInLastEmailTo($this->_user->email, htmlentities(trans('emails.password_reset.title')), 'h3');
        $I->seeInLastEmailTo($this->_user->email, htmlentities(trans('emails.password_reset.hello', ['name' => $this->_user->first_name . ' ' . $this->_user->last_name])), 'p');
        $I->seeInLastEmailTo($this->_user->email, trans('emails.password_reset.content'), 'p');
        $I->seeInLastEmailTo($this->_user->email, htmlentities(trans('emails.password_reset.button')), 'button');
    }

    public function access_to_password_reset_page_with_no_email_and_no_token(FunctionalTester $I)
    {
        $I->am('Unlogged user');
        $I->wantTo('access to the password reset page with no email and no reminder token');
        $I->expectTo('be redirected to the forgotten password page and see an error message explaining that the email is required');

        /***************************************************************************************************************
         * run test
         **************************************************************************************************************/
        $I->amOnRoute('password.reset');
        $I->seeCurrentRouteIs('password.index');
        $I->see(trans('global.modal.alert.title.error'), 'h3');
        $I->see(strip_tags(trans('validation.filled', ['attribute' => trans('validation.attributes.email')])));
    }

    public function access_to_password_reset_page_with_email_not_found(FunctionalTester $I)
    {
        $I->am('Unlogged user');
        $I->wantTo('access to the password reset page with an email not recorded in database');
        $I->expectTo('be redirected to the forgotten password page and see an error message explaining that the email does not exists');

        /***************************************************************************************************************
         * run test
         **************************************************************************************************************/
        $I->amOnRoute('password.reset', ['email' => 'test@fail.fr']);
        $I->seeCurrentRouteIs('password.index');
        $I->see(trans('global.modal.alert.title.error'), 'h3');
        $I->see(strip_tags(trans('auth.message.find.failure', ['email' => 'test@fail.fr'])));
    }

    public function access_to_password_reset_page_with_correct_email_and_no_token(FunctionalTester $I)
    {
        $I->am('Unlogged user');
        $I->wantTo('access to the password reset page with a correct email and no reminder token');
        $I->expectTo('be redirected to the forgotten password page and see an error message explaining that the token is invalid or has expired');

        /***************************************************************************************************************
         * run test
         **************************************************************************************************************/
        $I->amOnPage('/');
        $I->amOnRoute('password.reset', ['email' => $this->_user->email]);
        $I->seeCurrentRouteIs('password.index');
        $I->see(trans('global.modal.alert.title.error'), 'h3');
        $I->see(trans('auth.message.password_reset.token.expired'));
    }

    public function access_to_password_reset_page_and_cancel(FunctionalTester $I)
    {
        $I->am('Unlogged user');
        $I->wantTo('access to the password reset page and go back to the login page by clicking on the cancel button');
        $I->expectTo('land on the login page');

        /***************************************************************************************************************
         * run test
         **************************************************************************************************************/
        $reminder = Reminder::create($this->_user);
        $I->amOnPage('/');
        $I->amOnRoute('password.reset', ['email' => $this->_user->email, 'token' => $reminder->code]);
        $I->seeCurrentRouteIs('password.reset', ['email' => $this->_user->email, 'token' => $reminder->code]);
        $I->see(trans('auth.password.reset.title'), 'h1');
        $I->click(trans('global.action.cancel'));
        $I->seeCurrentRouteIs('login.index');
    }

    public function reset_password_from_password_reset_page_with_wrong_password_confirmation(FunctionalTester $I)
    {
        $I->am('Unlogged user');
        $I->wantTo('reset my password with a password confirmation different from the password');
        $I->expectTo('be redirected to the password reset page and see an error message explaining that the password confirmation must match the password field entry');

        /***************************************************************************************************************
         * run test
         **************************************************************************************************************/
        $reminder = Reminder::create($this->_user);
        $I->amOnPage('/');
        $I->amOnRoute('password.reset', ['email' => $this->_user->email, 'token' => $reminder->code]);
        $I->seeCurrentRouteIs('password.reset', ['email' => $this->_user->email, 'token' => $reminder->code]);
        $I->see(trans('auth.password.reset.title'), 'h1');
        $I->fillField('password', 'password');
        $I->fillField('password_confirmation', 'test');
        $I->click(trans('auth.password.reset.action.save'));
        $I->seeCurrentRouteIs('password.reset');
        $I->see(trans('global.modal.alert.title.error'), 'h3');
        $I->see(strip_tags(trans('validation.confirmed', ['attribute' => trans('validation.attributes.password')])));
        $I->seeInField('password', '');
        $I->seeInField('password_confirmation', '');
    }

    public function reset_password_from_password_reset_page_with_a_too_short_password(FunctionalTester $I)
    {
        $I->am('Unlogged user');
        $I->wantTo('reset my password with a too short password');
        $I->expectTo('be redirected to the password reset page and see an error message explaining that the password is too short');

        /***************************************************************************************************************
         * settings
         **************************************************************************************************************/
        $reminder = Reminder::create($this->_user);
        $password = str_random(config('password.min.length') - 1);

        /***************************************************************************************************************
         * run test
         **************************************************************************************************************/
        $I->amOnPage('/');
        $I->amOnRoute('password.reset', ['email' => $this->_user->email, 'token' => $reminder->code]);
        $I->seeCurrentRouteIs('password.reset', ['email' => $this->_user->email, 'token' => $reminder->code]);
        $I->see(trans('auth.password.reset.title'), 'h1');
        $I->fillField('password', $password);
        $I->fillField('password_confirmation', $password);
        $I->click(trans('auth.password.reset.action.save'));
        $I->seeCurrentRouteIs('password.reset');
        $I->see(trans('global.modal.alert.title.error'), 'h3');
        $I->see(strip_tags(trans('validation.min.string', ['attribute' => trans('validation.attributes.password'), 'min' => config('password.min.length')])));
        $I->seeInField('password', '');
        $I->seeInField('password_confirmation', '');
    }

    public function reset_password_from_password_reset_page(FunctionalTester $I)
    {
        $I->am('Unlogged user');
        $I->wantTo('reset my password with a correct password');
        $I->expectTo('be redirected to the login page and see a success confirmation');

        /***************************************************************************************************************
         * run test
         **************************************************************************************************************/
        $reminder = Reminder::create($this->_user);
        $I->amOnPage('/');
        $I->amOnRoute('password.reset', ['email' => $this->_user->email, 'token' => $reminder->code]);
        $I->seeCurrentRouteIs('password.reset', ['email' => $this->_user->email, 'token' => $reminder->code]);
        $I->see(trans('auth.password.reset.title'), 'h1');
        $I->fillField('password', 'password');
        $I->fillField('password_confirmation', 'password');
        $I->click(trans('auth.password.reset.action.save'));
        $I->seeCurrentRouteIs('login.index');
        $I->see(trans('global.modal.alert.title.success'), 'h3');
        $I->see(trans('auth.message.password_reset.success'));
    }

    public function access_to_password_reset_page_while_logged_in(FunctionalTester $I)
    {
        $I->am('Unlogged user');
        $I->wantTo('access to the password reset page while logged in');
        $I->expectTo('be redirected to the home page');

        /***************************************************************************************************************
         * run test
         **************************************************************************************************************/
        $user = \Sentinel::authenticate($this->_credentials);
        $reminder = Reminder::create($user);
        $I->amOnPage('/');
        $I->amOnPage(config('app.url') . '/' . config('app.locale') . '/' . trans('routes.password.reset') . '?email=' . $user->email . '&token=' . $reminder->code);
        $I->amOnRoute('home');
    }

    public function access_to_login_page_while_logged_in(FunctionalTester $I)
    {
        $I->am('Unlogged user');
        $I->wantTo('access to the login reset page while logged in');
        $I->expectTo('be redirected to the home page');

        /***************************************************************************************************************
         * run test
         **************************************************************************************************************/
        \Sentinel::authenticate($this->_credentials);
        $I->amOnPage('/');
        $I->amOnRoute('login.index');
        $I->amOnRoute('home');
    }

    public function access_to_forgotten_password_page_while_logged_in(FunctionalTester $I)
    {
        $I->am('Unlogged user');
        $I->wantTo('access to the forgotten password page while logged in');
        $I->expectTo('be redirected to the home page');

        /***************************************************************************************************************
         * run test
         **************************************************************************************************************/
        \Sentinel::authenticate($this->_credentials);
        $I->amOnPage('/');
        $I->amOnRoute('password.index');
        $I->amOnRoute('home');
    }

    public function access_to_account_create_page(FunctionalTester $I)
    {
        $I->am('Unlogged user');
        $I->wantTo('access to the account create page after a fail attempt to login');
        $I->expectTo('see the account create page with only the email filled');

        /***************************************************************************************************************
         * run test
         **************************************************************************************************************/
        $I->amOnPage('/');
        $I->amOnRoute('home');
        $I->click(trans('template.front.header.my_account'));
        $I->seeCurrentRouteIs('login.index');
        $I->fillField('email', 'test@test.machin');
        $I->click(trans('auth.login.action.login'));
        $I->seeInField('email', 'test@test.machin');
        $I->click(trans('auth.login.label.create_account'));
        $I->see(trans('auth.login.label.create_account'), 'h1');
        $I->seeInField('last_name', '');
        $I->seeInField('first_name', '');
        $I->seeInField('email', 'test@test.machin');
        $I->seeInField('password', '');
        $I->seeInField('password_confirmation', '');
    }

    public function create_account_with_no_field_filled(FunctionalTester $I)
    {
        $I->am('Unlogged user');
        $I->wantTo('create an account with no field filled');
        $I->expectTo('see that some fields are required');

        /***************************************************************************************************************
         * run test
         **************************************************************************************************************/
        $I->amOnRoute('account.create');
        $I->fillField('last_name', '');
        $I->fillField('first_name', '');
        $I->fillField('email', '');
        $I->fillField('password', '');
        $I->fillField('password_confirmation', '');
        $I->click(trans('auth.account_creation.action.create'));
        $I->see(trans('global.modal.alert.title.error'), 'h3');
        $I->see(strip_tags(trans('validation.required', ['attribute' => trans('validation.attributes.last_name')])));
        $I->see(strip_tags(trans('validation.required', ['attribute' => trans('validation.attributes.first_name')])));
        $I->see(strip_tags(trans('validation.required', ['attribute' => trans('validation.attributes.email')])));
        $I->see(strip_tags(trans('validation.required', ['attribute' => trans('validation.attributes.password')])));
    }

    public function create_account_with_too_short_password(FunctionalTester $I)
    {
        $I->am('Unlogged user');
        $I->wantTo('create an account with a too short password');
        $I->expectTo('see an error message explaining that the password is too short');

        /***************************************************************************************************************
         * run test
         **************************************************************************************************************/
        $I->amOnRoute('account.create');
        $I->fillField('last_name', 'COUCOU');
        $I->fillField('first_name', 'Test');
        $I->fillField('email', 'test@test.machin');
        $I->fillField('password', 'test');
        $I->fillField('password_confirmation', 'test');
        $I->click(trans('auth.account_creation.action.create'));
        $I->see(trans('global.modal.alert.title.error'), 'h3');
        $I->see(strip_tags(trans('validation.min.string', ['attribute' => trans('validation.attributes.password'), 'min' => config('password.min.length')])));
    }

    public function create_account_with_wrong_password_confirmation(FunctionalTester $I)
    {
        $I->am('Unlogged user');
        $I->wantTo('create an account with a wrong password confirmation');
        $I->expectTo('see an error message explaining that the password confirmation is incorrect');

        /***************************************************************************************************************
         * run test
         **************************************************************************************************************/
        $I->amOnRoute('account.create');
        $I->fillField('last_name', 'COUCOU');
        $I->fillField('first_name', 'Test');
        $I->fillField('email', 'test@test.machin');
        $I->fillField('password', 'password');
        $I->fillField('password_confirmation', 'test');
        $I->click(trans('auth.account_creation.action.create'));
        $I->see(trans('global.modal.alert.title.error'), 'h3');
        $I->see(strip_tags(trans('validation.confirmed', ['attribute' => trans('validation.attributes.password')])));
    }

    public function create_account_with_email_that_already_exists(FunctionalTester $I)
    {
        $I->am('Unlogged user');
        $I->wantTo('create an account');
        $I->expectTo('see a success confirmation message asking to validate the account email');

        /***************************************************************************************************************
         * run test
         **************************************************************************************************************/
        $I->amOnRoute('account.create');
        $I->fillField('last_name', 'COUCOU');
        $I->fillField('first_name', 'Test');
        $I->fillField('email', 'test@test.fr');
        $I->fillField('password', 'password');
        $I->fillField('password_confirmation', 'password');
        $I->click(trans('auth.account_creation.action.create'));
        $I->see(trans('global.modal.alert.title.error'), 'h3');
        $I->see(strip_tags(trans('validation.unique', ['attribute' => trans('validation.attributes.email')])));
    }

    public function create_account(FunctionalTester $I)
    {
        $I->am('Unlogged user');
        $I->wantTo('create an account');
        $I->expectTo('see a success confirmation message asking to validate the account email');

        /***************************************************************************************************************
         * run test
         **************************************************************************************************************/
        $I->amOnRoute('account.create');
        $I->fillField('last_name', 'COUCOU');
        $I->fillField('first_name', 'Test');
        $I->fillField('email', 'test@test.machin');
        $I->fillField('password', 'password');
        $I->fillField('password_confirmation', 'password');
        $I->click(trans('auth.account_creation.action.create'));
        $I->see(trans('global.modal.alert.title.success'), 'h3');
        $I->see(trans('auth.message.account_creation.success'));
        $I->seeInLastEmailTo('test@test.machin', htmlentities(trans('emails.account_activation.title')), 'h3');
        $I->seeInLastEmailTo('test@test.machin', trans('emails.account_activation.hello', ['name' => 'Test COUCOU']), 'p');
        $I->seeInLastEmailTo('test@test.machin', trans('emails.account_activation.content'), 'p');
        $I->seeInLastEmailTo('test@test.machin', htmlentities(trans('emails.account_activation.button'), ENT_QUOTES), 'button');

    }

    public function login_with_not_activated_account(FunctionalTester $I)
    {
        $I->am('Unlogged user with a not activated account');
        $I->wantTo('Login');
        $I->expectTo('see an error message explaining that my account is not activated, with a link to resend the activation email');

        /***************************************************************************************************************
         * settings
         **************************************************************************************************************/
        // we create a user
        $this->_credentials = [
            'last_name'  => 'NOM',
            'first_name' => 'Prénom',
            'email'      => 'autre@test.fr',
            'password'   => 'password',
        ];
        $user = \Sentinel::register($this->_credentials);

        /***************************************************************************************************************
         * run test
         **************************************************************************************************************/
        $I->amOnPage('/');
        $I->seeCurrentRouteIs('home');
        $I->click(trans('template.front.header.my_account'));
        $I->seeCurrentRouteIs('login.index');
        $I->fillField('email', 'autre@test.fr');
        $I->fillField('password', 'password');
        $I->click(trans('auth.login.action.login'));
        $I->seeCurrentRouteIs('login.index');
        $I->see(trans('global.modal.alert.title.error'), 'h3');
        $I->see(trans('auth.message.activation.failure'));
        $I->see(strip_tags(trans('auth.message.activation.email.resend', ['url' => route('account.activation_email', ['email' => $user->email]), 'email' => $user->email])));
    }

    public function resend_activation_email(FunctionalTester $I)
    {
        $I->am('Unlogged user with a not activated account');
        $I->wantTo('resend the activation email');
        $I->expectTo('see the activation email');

        /***************************************************************************************************************
         * settings
         **************************************************************************************************************/
        // we create a user
        $this->_credentials = [
            'last_name'  => 'NOM',
            'first_name' => 'Prénom',
            'email'      => 'autre@test.fr',
            'password'   => 'password',
        ];
        $user = \Sentinel::register($this->_credentials);

        /***************************************************************************************************************
         * run test
         **************************************************************************************************************/
        $I->sendAjaxRequest('GET', route('account.activation_email', ['email' => $user->email]));
        $I->seeInLastEmailTo($user->email, htmlentities(trans('emails.account_activation.title')), 'h3');
        $I->seeInLastEmailTo($user->email, trans('emails.account_activation.hello', ['name' => $user->first_name . ' ' . $user->last_name]), 'p');
        $I->seeInLastEmailTo($user->email, trans('emails.account_activation.content'), 'p');
        $I->seeInLastEmailTo($user->email, htmlentities(trans('emails.account_activation.button'), ENT_QUOTES), 'button');
    }

    public function activate_account_from_wrong_email(FunctionalTester $I)
    {
        $I->am('Unlogged user with a not activated account');
        $I->wantTo('activation my account with a wrong email');
        $I->expectTo('see an error message explaining that the email is incorrect');

        /***************************************************************************************************************
         * settings
         **************************************************************************************************************/
        // we create a user
        $this->_credentials = [
            'last_name'  => 'NOM',
            'first_name' => 'Prénom',
            'email'      => 'autre@test.fr',
            'password'   => 'password',
        ];
        $user = \Sentinel::register($this->_credentials);
        // we create an activation
        $activation = \Activation::create($user);

        /***************************************************************************************************************
         * run test
         **************************************************************************************************************/
        $I->sendAjaxRequest('GET', route('account.activate', ['email' => 'wrong@email.fr', 'token' => $activation->code]));
        $I->see(trans('global.modal.alert.title.error'));
        $I->see(strip_tags(trans('auth.message.find.failure', ['email' => 'wrong@email.fr'])));
    }

    public function activate_account_with_wrong_token(FunctionalTester $I)
    {
        $I->am('Unlogged user with a not activated account');
        $I->wantTo('activation my account with a wrong token');
        $I->expectTo('see an error message explaining that the token is incorrect');

        /***************************************************************************************************************
         * settings
         **************************************************************************************************************/
        // we create a user
        $this->_credentials = [
            'last_name'  => 'NOM',
            'first_name' => 'Prénom',
            'email'      => 'autre@test.fr',
            'password'   => 'password',
        ];
        $user = \Sentinel::register($this->_credentials);
        // we create an activation
        $activation = \Activation::create($user);

        /***************************************************************************************************************
         * run test
         **************************************************************************************************************/
        $I->sendAjaxRequest('GET', route('account.activate', ['email' => $user->email, 'token' => 'wrong_token']));
        $I->see(trans('global.modal.alert.title.error'));
        $I->see(strip_tags(trans('auth.message.activation.token.expired')));
    }

    public function activate_account_from_email(FunctionalTester $I)
    {
        $I->am('Unlogged user with a not activated account');
        $I->wantTo('activate my account');
        $I->expectTo('see a success confirmation message explaining that my account is activated');

        /***************************************************************************************************************
         * settings
         **************************************************************************************************************/
        // we create a user
        $this->_credentials = [
            'last_name'  => 'NOM',
            'first_name' => 'Prénom',
            'email'      => 'autre@test.fr',
            'password'   => 'password',
        ];
        $user = \Sentinel::register($this->_credentials);
        // we create an activation
        $activation = \Activation::create($user);

        /***************************************************************************************************************
         * run test
         **************************************************************************************************************/
        $I->sendAjaxRequest('GET', route('account.activate', ['email' => $user->email, 'token' => $activation->code]));
        $I->see(trans('global.modal.alert.title.success'));
        $I->see(strip_tags(trans('auth.message.activation.success', ['name' => $user->first_name . ' ' . $user->last_name])));
    }
}
