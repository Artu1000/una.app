<?php

use App\Repositories\Slide\SlideRepositoryInterface;
use Carbon\Carbon;

class HomeCest
{
    private $_user;
    private $_credentials;
    private $_slide_repo;
    private $_slide;

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

        $this->_slide_repo = app(SlideRepositoryInterface::class);

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

    private function _createSlide()
    {
        // we create the admin role
        $this->_slide = $this->_slide_repo->create([
            'title'            => 'Club d\'aviron à Nantes',
            'quote'            => 'Quote with at least 50 caracters and stuff and stuff',
            'picto'            => 'picto_1.png',
            'background_image' => 'bg_img_2.jpg',
            'position'         => 1,
            'active'           => true,
        ]);
    }


    /**<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<
     * -----------------------------------------------------------------------------------------------------------------
     * TESTS
     * -----------------------------------------------------------------------------------------------------------------
     * >>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>*/

    public function access_to_home_edit_page_without_permission(FunctionalTester $I)
    {
        $I->am('A user with no role');
        $I->wantTo('access to the home edit page without the permission');
        $I->expectTo('see an error message explaining that I do not have the permission to access this page');

        /***************************************************************************************************************
         * run test
         **************************************************************************************************************/
        $I->amOnPage('/');
        $I->amOnRoute('home.edit');
        $I->seeCurrentRouteIs('dashboard.index');
        $I->see(trans('global.modal.alert.title.error'), 'h3');
        $I->see(strip_tags(trans('permissions.message.access.denied', ['permission' => trans('permissions.users.list')])));
    }

    public function access_to_home_edit_page(FunctionalTester $I)
    {
        $I->am('Admin');
        $I->wantTo('access to the slides list');
        $I->expectTo('see the slides list');

        /***************************************************************************************************************
         * settings
         **************************************************************************************************************/
        // we create an the admin role
        $admin_role = $this->_createAdminRole();
        // we attach it to the logged user
        $admin_role->users()->attach($this->_user);
        // we create a slide
        $this->_createSlide();

        /***************************************************************************************************************
         * run test
         **************************************************************************************************************/
        $I->amOnPage('/');
        $I->seeCurrentRouteIs('home');
        $I->click(trans('template.front.header.my_account'));
        $I->seeCurrentRouteIs('dashboard.index');
        $I->click(trans('template.back.header.home'));
        $I->seeCurrentRouteIs('home.edit');
        $I->see(trans('breadcrumbs.admin'), '.breadcrumb');
        $I->seeLink(trans('breadcrumbs.admin'), route('dashboard.index'));
        $I->see(trans('breadcrumbs.home.edit'), '.breadcrumb');
        $I->see(trans('home.page.title.management'));
        $I->see(trans('home.page.title.content'));
        $I->seeInField('title', json_decode(file_get_contents(storage_path('app/home/content.json')))->title);
        $I->seeInField('description', json_decode(file_get_contents(storage_path('app/home/content.json')))->description);
        $I->seeInField('video_link', json_decode(file_get_contents(storage_path('app/home/content.json')))->video_link);
        $I->see($this->_slide->title, '.table-list');
        $I->see(str_limit(strip_tags($this->_slide->quote), 75), '.table-list');
        $I->see($this->_slide->position, '.table-list');
        $I->seeCheckboxIsChecked('#activate_' . $this->_slide->id);
        $I->see(strip_tags(trans('global.table_list.results.status', ['start' => 1, 'stop' => 1, 'total' => 1])));
    }

    public function update_home_page_without_permission(FunctionalTester $I)
    {
        $I->am('A user with no role');
        $I->wantTo('update the home page without the permission');
        $I->expectTo('see an error message explaining that I do not have the permission to update the home page');

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

        /***************************************************************************************************************
         * run test
         **************************************************************************************************************/
        $this->_user->addPermission('home.view', true)->save();
        $I->amOnPage('/');
        $I->amOnRoute('home.edit');
        $I->click(trans('global.action.save'));
        $I->seeCurrentRouteIs('home.edit');
        $I->see(trans('global.modal.alert.title.error'), 'h3');
        $I->see(strip_tags(trans('permissions.message.access.denied', ['permission' => trans('permissions.home.update')])));
    }

    public function update_home_page_with_no_field_filled_from_ajax(FunctionalTester $I)
    {
        $I->am('Admin');
        $I->wantTo('update the home page with no fields filled from ajax request');
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
        $I->amOnRoute('home.edit');
        $I->sendAjaxRequest('POST', route('home.update', [
            '_token'  => csrf_token(),
            '_method' => 'PUT',
        ]));
        $I->seeCurrentRouteIs('home.edit');
        $I->see(trans('global.modal.alert.title.error'));
        $I->see(strip_tags(trans('validation.filled', ['attribute' => trans('validation.attributes.title')])));
    }

    public function update_home_page_with_no_change(FunctionalTester $I)
    {
        $I->am('Admin');
        $I->wantTo('update the home page with no change on fields');
        $I->expectTo('see a success confirmation message');

        /***************************************************************************************************************
         * settings
         **************************************************************************************************************/
        // we create the admin role
        $admin_role = $this->_createAdminRole();
        // we attach it to the logged user
        $admin_role->users()->attach($this->_user);
        // we insert home page data
        $status = file_put_contents(
            storage_path('app/home/content.json'),
            json_encode([
                'title'      => 'test',
                'content'    => 'content test',
                'video_link' => 'http://www.video.com',
            ])
        );

        /***************************************************************************************************************
         * run test
         **************************************************************************************************************/
        $I->amOnPage('/');
        $I->amOnRoute('home.edit');
        $I->click(trans('global.action.save'));
        $I->seeCurrentRouteIs('home.edit');
        $I->see(trans('global.modal.alert.title.success'), 'h3');
        $I->see(strip_tags(trans('home.message.update.success')));
        $I->seeInField('title', json_decode(file_get_contents(storage_path('app/home/content.json')))->title);
        $I->seeInField('description', json_decode(file_get_contents(storage_path('app/home/content.json')))->description);
        $I->seeInField('video_link', json_decode(file_get_contents(storage_path('app/home/content.json')))->video_link);
    }


    public function update_home_page_with_wrong_values_filled_from_ajax(FunctionalTester $I)
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

        /***************************************************************************************************************
         * run test
         **************************************************************************************************************/
        $I->amOnPage('/');
        $I->amOnRoute('home.edit');
        $I->sendAjaxRequest('POST', route('home.update', [
            '_method'     => 'PUT',
            '_token'      => csrf_token(),
            'title'       => '765',
            'description' => 'test',
            'video_link'  => 'video',
        ]));
        $I->seeCurrentRouteIs('home.edit');
        $I->see(trans('global.modal.alert.title.error'));
        $I->see(strip_tags(trans('validation.string', ['attribute' => trans('validation.attributes.title')])));
        $I->see(strip_tags(trans('validation.min.string', ['attribute' => trans('validation.attributes.description'), 'min' => 1500])));
        $I->see(strip_tags(trans('validation.url', ['attribute' => trans('validation.attributes.video_link')])));
    }

    public function update_home_page_with_data_change(FunctionalTester $I)
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

        /***************************************************************************************************************
         * run test
         **************************************************************************************************************/
        $I->amOnPage('/');
        $I->amOnRoute('home.edit');
        $I->fillField('title', 'titre');
        $I->fillField('textarea[name=description]', 'Cum haec taliaque sollicitas eius aures everberarent expositas semper eius modi rumoribus et patentes, varia animo tum miscente consilia, tandem id ut optimum factu elegit: et Vrsicinum primum ad se venire summo cum honore mandavit ea specie ut pro rerum tunc urgentium captu disponeretur concordi consilio, quibus virium incrementis Parthicarum gentium a arma minantium impetus frangerentur. Verum ad istam omnem orationem brevis est defensio. Nam quoad aetas M. Caeli dare potuit isti suspicioni locum, fuit primum ipsius pudore, deinde etiam patris diligentia disciplinaque munita. Qui ut huic virilem togam deditšnihil dicam hoc loco de me; tantum sit, quantum vos existimatis; hoc dicam, hunc a patre continuo ad me esse deductum; nemo hunc M. Caelium in illo aetatis flore vidit nisi aut cum patre aut mecum aut in M. Crassi castissima domo, cum artibus honestissimis erudiretur. Et quia Montius inter dilancinantium manus spiritum efflaturus Epigonum et Eusebium nec professionem nec dignitatem ostendens aliquotiens increpabat, qui sint hi magna quaerebatur industria, et nequid intepesceret, Epigonus e Lycia philosophus ducitur et Eusebius ab Emissa Pittacas cognomento, concitatus orator, cum quaestor non hos sed tribunos fabricarum insimulasset promittentes armorum si novas res agitari conperissent. Hac ex causa conlaticia stipe Valerius humatur ille Publicola et subsidiis amicorum mariti inops cum liberis uxor alitur Reguli et dotatur aerario filia eius angustus et tener, quicqui njklbjbjkl bjkvivilvb');
        $I->fillField('video_link', 'http://www.video-somerwhere.fr');
        $I->click(trans('global.action.save'));
        $I->seeCurrentRouteIs('home.edit');
        $I->see(trans('global.modal.alert.title.success'), 'h3');
        $I->see(strip_tags(trans('home.message.update.success')));
    }

    public function set_number_of_lines(FunctionalTester $I)
    {
        $I->am('Admin');
        $I->wantTo('set the table lines number');
        $I->expectTo('see the number of line I chose');

        /***************************************************************************************************************
         * settings
         **************************************************************************************************************/
        // we create an the admin role
        $admin_role = $this->_createAdminRole();
        // we attach it to the logged user
        $admin_role->users()->attach($this->_user);
        // we create a slide
        $this->_createSlide();
        // we create another slide
        $slide = $this->_slide_repo->create([
            'title'            => 'Aviron universitaire',
            'quote'            => 'Other quote',
            'picto'            => 'picto_2.png',
            'background_image' => 'bg_img_2.jpg',
            'position'         => 2,
            'active'           => true,
        ]);

        /***************************************************************************************************************
         * run test
         **************************************************************************************************************/
        $I->amOnRoute('home.edit');
        $I->see(strip_tags(trans('global.table_list.results.status', ['start' => 1, 'stop' => 2, 'total' => 2])));

        $I->see($this->_slide->title, '.table-list');
        $I->see(str_limit(strip_tags($this->_slide->quote), 75), '.table-list');
        $I->see($slide->title, '.table-list');
        $I->see(str_limit(strip_tags($slide->quote), 75), '.table-list');
        $I->seeInField('#input_lines', config('tablelist.default.lines'));
        $I->seeInField('#input_search', '');
        $I->fillField('#input_lines', 1);
        $I->submitForm('#line_search_form', []);
        $I->see($this->_slide->title, '.table-list');
        $I->see(str_limit(strip_tags($this->_slide->quote), 75), '.table-list');
        $I->dontSee($slide->title, '.table-list');
        $I->dontSee(str_limit(strip_tags($slide->quote), 75), '.table-list');
        $I->see(strip_tags(trans('global.table_list.results.status', ['start' => 1, 'stop' => 1, 'total' => 2])));
    }

    public function search_slide(FunctionalTester $I)
    {
        $I->am('Admin');
        $I->wantTo('search a slide');
        $I->expectTo('see the searched slide');

        /***************************************************************************************************************
         * settings
         **************************************************************************************************************/
        // we create an the admin role
        $admin_role = $this->_createAdminRole();
        // we attach it to the logged user
        $admin_role->users()->attach($this->_user);
        // we create a slide
        $this->_createSlide();
        // we create another slide
        $slide = $this->_slide_repo->create([
            'title'            => 'Aviron universitaire',
            'quote'            => 'Other quote',
            'picto'            => 'picto_2.png',
            'background_image' => 'bg_img_2.jpg',
            'position'         => 2,
            'active'           => true,
        ]);

        /***************************************************************************************************************
         * run test
         **************************************************************************************************************/
        $I->amOnRoute('home.edit');
        $I->see($this->_slide->title, '.table-list');
        $I->see(str_limit(strip_tags($this->_slide->quote), 75), '.table-list');
        $I->see($slide->title, '.table-list');
        $I->see(str_limit(strip_tags($slide->quote), 75), '.table-list');
        $I->seeInField('#input_lines', config('tablelist.default.lines'));
        $I->seeInField('#input_search', '');
        $I->fillField('#input_search', 'univ');
        $I->submitForm('#line_search_form', []);
        $I->dontSee($this->_slide->title, '.table-list');
        $I->dontSee(str_limit(strip_tags($this->_slide->quote), 75), '.table-list');
        $I->see($slide->title, '.table-list');
        $I->see(str_limit(strip_tags($slide->quote), 75), '.table-list');
        $I->see(strip_tags(trans('global.table_list.results.status', ['start' => 1, 'stop' => 1, 'total' => 1])));
    }

    public function access_to_slide_add_page_without_permission(FunctionalTester $I)
    {
        $I->am('A user with no role');
        $I->wantTo('access to the slides add page without the permission');
        $I->expectTo('see an error message explaining that I do not have the permission to access this page');

        /***************************************************************************************************************
         * run test
         **************************************************************************************************************/
        $I->amOnPage('/');
        $I->amOnRoute('slides.create');
        $I->seeCurrentRouteIs('dashboard.index');
        $I->see(trans('global.modal.alert.title.error'), 'h3');
        $I->see(strip_tags(trans('permissions.message.access.denied', ['permission' => trans('permissions.slides.create')])));
    }

    public function access_to_slide_add_page(FunctionalTester $I)
    {
        $I->am('Admin');
        $I->wantTo('access to the slide add page');
        $I->expectTo('see the slide add page with blank fields');

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
        $I->seeCurrentRouteIs('home');
        $I->click(trans('template.front.header.my_account'));
        $I->seeCurrentRouteIs('dashboard.index');
        $I->click(trans('template.back.header.home'));
        $I->seeCurrentRouteIs('home.edit');
        $I->click(trans('global.action.add'));
        $I->seeCurrentRouteIs('slides.create');
        $I->see(trans('breadcrumbs.admin'), '.breadcrumb');
        $I->seeLink(trans('breadcrumbs.admin'), route('dashboard.index'));
        $I->see(trans('breadcrumbs.home.edit'), '.breadcrumb');
        $I->seeLink(trans('breadcrumbs.home.edit'), route('home.edit'));
        $I->see(trans('breadcrumbs.slides.create'), '.breadcrumb');
        $I->see(trans('home.page.title.slide.create'), 'h2');
        $I->seeInField('title', '');
        $I->seeInField('quote', '');
        $I->assertFileNotExists('picto');
        $I->assertFileNotExists('background_image');
        $I->seeOptionIsSelected('#input_previous_slide_id', 'X - ' . trans('home.page.label.slide.first'));
        $I->dontSeeCheckboxIsChecked('#input_active');
    }

    public function access_to_slide_add_page_and_cancel(FunctionalTester $I)
    {
        $I->am('Admin');
        $I->wantTo('access to the slide add page and cancel');
        $I->expectTo('land on the home edit page');

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
        $I->seeCurrentRouteIs('home');
        $I->click(trans('template.front.header.my_account'));
        $I->seeCurrentRouteIs('dashboard.index');
        $I->click(trans('template.back.header.home'));
        $I->seeCurrentRouteIs('home.edit');
        $I->click(trans('global.action.add'));
        $I->seeCurrentRouteIs('slides.create');
        $I->click(trans('global.action.cancel'));
        $I->seeCurrentRouteIs('home.edit');
    }

    public function create_slide_without_permission(FunctionalTester $I)
    {
        $I->am('A user with no role');
        $I->wantTo('create a new slide without the permission');
        $I->expectTo('see an error message explaining that I do have the permission to create a new slide');

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
        $I->amOnRoute('slides.create');
        $admin_role->removePermission('home.slides.create')->save();
        $I->click(trans('home.page.action.slide.create'));
        $I->seeCurrentRouteIs('home.edit');
        $I->see(trans('global.modal.alert.title.error'), 'h3');
        $I->see(strip_tags(trans('permissions.message.access.denied', ['permission' => trans('permissions.slides.create')])));
    }

    public function create_user_with_no_field_filled_from_ajax(FunctionalTester $I)
    {
        $I->am('Admin');
        $I->wantTo('create a slide with no fields filled from ajax request');
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
        $I->amOnRoute('slides.create');
        $I->sendAjaxRequest('POST', route('slides.store', ['_token' => csrf_token()]));
        $I->seeCurrentRouteIs('slides.create');
        $I->see(trans('global.modal.alert.title.error'));
        $I->see(strip_tags(trans('validation.filled', ['attribute' => trans('validation.attributes.title')])));
        $I->see(strip_tags(trans('validation.filled', ['attribute' => trans('validation.attributes.quote')])));
    }

    public function create_slide_with_wrong_values_filled_from_ajax(FunctionalTester $I)
    {
        $I->am('Admin');
        $I->wantTo('create a slide with wrong values filled');
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
        $I->amOnRoute('slides.create');
        $I->sendAjaxRequest('POST', route('slides.store', [
            '_token'           => csrf_token(),
            'title'            => '1234',
            'quote'            => 'Ceci est un exemple de texte trop court',
            'picto'            => 'picto_1',
            'background_image' => 'bg_image_1',
            'active'           => 7,
        ]));
        $I->seeCurrentRouteIs('slides.create');
        $I->see(trans('global.modal.alert.title.error'));
        $I->see(strip_tags(trans('validation.string', ['attribute' => trans('validation.attributes.title')])));
        $I->see(strip_tags(trans('validation.min.string', ['attribute' => trans('validation.attributes.quote'), 'min' => 50])));
        $I->see(strip_tags(trans('validation.image', ['attribute' => trans('validation.attributes.picto')])));
        $I->see(strip_tags(trans('validation.image', ['attribute' => trans('validation.attributes.background_image')])));
        $I->see(strip_tags(trans('validation.boolean', ['attribute' => trans('validation.attributes.active')])));
    }

    public function create_slide(FunctionalTester $I)
    {
        $I->am('Admin');
        $I->wantTo('create a slide');
        $I->expectTo('see a success confirmation message');

        /***************************************************************************************************************
         * settings
         **************************************************************************************************************/
        // we create the admin role
        $admin_role = $this->_createAdminRole();
        // we attach it to the logged user
        $admin_role->users()->attach($this->_user);
        // we create a slide
        $this->_createSlide();
        // we create another slide
        $slide = $this->_slide_repo->create([
            'title'            => 'Aviron universitaire',
            'quote'            => 'Other quote',
            'picto'            => 'picto_2.png',
            'background_image' => 'bg_img_2.jpg',
            'position'         => 2,
            'active'           => true,
        ]);

        /***************************************************************************************************************
         * run test
         **************************************************************************************************************/
        $I->amOnPage('/');
        $I->amOnRoute('slides.create');
        $I->fillField('title', 'Slide');
        $I->fillField('quote', 'Rejoignez le plus grand club d\'aviron universitaire de France. Nous proposons des tarifs avantageux pour tous les étudiants nantais !');
        $I->selectOption('#input_previous_slide_id', $this->_slide->position . ' - ' . $this->_slide->title);
        $I->checkOption('#input_active');
        $I->click(trans('home.page.action.slide.create'));
        $I->seeCurrentRouteIs('home.edit');
        $I->see(trans('global.modal.alert.title.success'), 'h3');
        $I->see(strip_tags(trans('home.message.slide.creation.success', ['slide' => 'Slide'])));
        $I->see('Slide', '.table-list');
        $I->see(str_limit(strip_tags('Rejoignez le plus grand club d\'aviron universitaire de France. Nous proposons des tarifs avantageux pour tous les étudiants nantais !'), 75), '.table-list');
        $created_slide = $this->_slide_repo->where('title', 'Slide')->first();
        $I->seeCheckboxIsChecked('#activate_' . $created_slide->id);
    }

    public function access_to_slide_edit_page_without_permission(FunctionalTester $I)
    {
        $I->am('A user with no role');
        $I->wantTo('access to the slide edit page');
        $I->expectTo('see an error message explaining that I do not have the authorization');

        /***************************************************************************************************************
         * settings
         **************************************************************************************************************/
        // we create the admin role
        $admin_role = $this->_createAdminRole();
        // we attach it to the logged user
        $admin_role->users()->attach($this->_user);
        // we create a slide
        $this->_createSlide();

        /***************************************************************************************************************
         * run test
         **************************************************************************************************************/
        $admin_role->removePermission('home.slides.view')->save();
        $I->amOnPage('/');
        $I->amOnRoute('slides.edit', ['id' => $this->_slide->id]);
        $I->seeCurrentRouteIs('home.edit');
        $I->see(trans('global.modal.alert.title.error'), 'h3');
        $I->see(strip_tags(trans('permissions.message.access.denied', ['permission' => trans('permissions.home.slides.view')])));
    }

    public function access_to_slide_edit_page_with_wrong_id(FunctionalTester $I)
    {
        $I->am('Admin');
        $I->wantTo('access to the slide edit page with a wrong id');
        $I->expectTo('see an error message explaining the user doesn\'t exists and be redirected to the home edit page');

        /***************************************************************************************************************
         * settings
         **************************************************************************************************************/
        // we create the admin role
        $admin_role = $this->_createAdminRole();
        // we attach it to the logged user
        $admin_role->users()->attach($this->_user);
        // we create a slide
        $this->_createSlide();

        /***************************************************************************************************************
         * run test
         **************************************************************************************************************/
        $I->amOnPage('/');
        $I->amOnRoute('slides.edit', ['id' => 1000000000000]);
        $I->seeCurrentRouteIs('home.edit');
        $I->see(trans('global.modal.alert.title.error'), 'h3');
        $I->see(strip_tags(trans('home.message.slide.find.failure', ['id' => 1000000000000])));
    }

    public function access_to_slide_edit_page(FunctionalTester $I)
    {
        $I->am('Admin');
        $I->wantTo('access to slide edit page');
        $I->expectTo('see the slide edit page with fields filled with the slide data');

        /***************************************************************************************************************
         * settings
         **************************************************************************************************************/
        // we create the admin role
        $admin_role = $this->_createAdminRole();
        // we attach it to the logged user
        $admin_role->users()->attach($this->_user);
        // we create a slide
        $this->_createSlide();

        /***************************************************************************************************************
         * run test
         **************************************************************************************************************/
        $I->amOnPage('/');
        $I->seeCurrentRouteIs('home');
        $I->click(trans('template.front.header.my_account'));
        $I->seeCurrentRouteIs('dashboard.index');
        $I->click(trans('template.back.header.home'));
        $I->seeCurrentRouteIs('home.edit');
        $I->click('edit_' . $this->_slide->id);
        $I->seeCurrentRouteIs('slides.edit', ['id' => $this->_slide->id]);
        $I->see(trans('breadcrumbs.admin'), '.breadcrumb');
        $I->seeLink(trans('breadcrumbs.admin'), route('dashboard.index'));
        $I->see(trans('breadcrumbs.home.edit'), '.breadcrumb');
        $I->seeLink(trans('breadcrumbs.home.edit'), route('home.edit'));
        $I->see(strip_tags(trans('breadcrumbs.slides.edit', ['slide' => $this->_slide->title])), '.breadcrumb');
        $I->see(strip_tags(trans('home.page.title.slide.edit', ['slide' => $this->_slide->title])), 'h2');
        $I->seeInField('title', $this->_slide->title);
        $I->seeInField('quote', $this->_slide->quote);
        $I->seeInField('position', $this->_slide->position);
        $I->seeOptionIsSelected('#input_previous_slide_id', 'X - ' . trans('home.page.label.slide.first'));
        $I->seeCheckboxIsChecked('#input_active');
    }

    public function access_to_slide_edit_page_and_go_back(FunctionalTester $I)
    {
        $I->am('Admin');
        $I->wantTo('access to the slide edit page and go back');
        $I->expectTo('land on the home edit page');

        /***************************************************************************************************************
         * settings
         **************************************************************************************************************/
        // we create the admin role
        $admin_role = $this->_createAdminRole();
        // we attach it to the logged user
        $admin_role->users()->attach($this->_user);
        // we create a slide
        $this->_createSlide();

        /***************************************************************************************************************
         * run test
         **************************************************************************************************************/
        $I->amOnPage('/');
        $I->seeCurrentRouteIs('home');
        $I->click(trans('template.front.header.my_account'));
        $I->seeCurrentRouteIs('dashboard.index');
        $I->click(trans('template.back.header.home'));
        $I->seeCurrentRouteIs('home.edit');
        $I->click('edit_' . $this->_slide->id);
        $I->seeCurrentRouteIs('slides.edit', ['id' => $this->_slide->id]);
        $I->click(trans('global.action.back'), '#content');
        $I->seeCurrentRouteIs('home.edit');
    }

    public function update_slide_without_permission(FunctionalTester $I)
    {
        $I->am('A user with no role');
        $I->wantTo('update a slide without the permission');
        $I->expectTo('see an error message explaining that I do not have the permission to update the slide');

        /***************************************************************************************************************
         * settings
         **************************************************************************************************************/
        // we create the admin role
        $admin_role = $this->_createAdminRole();
        // we attach it to the logged user
        $admin_role->users()->attach($this->_user);
        // we create a slide
        $this->_createSlide();

        /***************************************************************************************************************
         * run test
         **************************************************************************************************************/
        $I->amOnPage('/');
        $I->amOnRoute('slides.edit', ['id' => $this->_slide->id]);
        $admin_role->removePermission('home.slides.update')->save();
        $I->click(trans('home.page.action.slide.update'));
        $I->seeCurrentRouteIs('slides.edit', ['id' => $this->_slide->id]);
        $I->see(trans('global.modal.alert.title.error'), 'h3');
        $I->see(strip_tags(trans('permissions.message.access.denied', ['permission' => trans('permissions.home.slides.update')])));
    }

    public function update_slide_with_wrong_id(FunctionalTester $I)
    {
        $I->am('A user with no role');
        $I->wantTo('update a slide with a wrong id');
        $I->expectTo('see an error message explaining the user doesn\'t exists and be redirected to the home edit page');

        /***************************************************************************************************************
         * settings
         **************************************************************************************************************/
        // we create the admin role
        $admin_role = $this->_createAdminRole();
        // we attach it to the logged user
        $admin_role->users()->attach($this->_user);
        // we create a slide
        $this->_createSlide();

        /***************************************************************************************************************
         * run test
         **************************************************************************************************************/
        $I->amOnPage('/');
        $I->amOnRoute('slides.edit', ['id' => $this->_slide->id]);
        $I->sendAjaxRequest('POST', route('slides.update', ['id' => 1000000000000, '_token' => csrf_token(), '_method' => 'PUT']));
        $I->seeCurrentRouteIs('home.edit');
        $I->see(trans('global.modal.alert.title.error'));
        $I->see(strip_tags(trans('home.message.slide.find.failure', ['id' => 1000000000000])));
    }

    public function update_slide_with_no_field_filled_from_ajax(FunctionalTester $I)
    {
        $I->am('Admin');
        $I->wantTo('update slide with no fields filled from ajax request');
        $I->expectTo('see an error message explaining that some fields are missing');

        /***************************************************************************************************************
         * settings
         **************************************************************************************************************/
        // we create the admin role
        $admin_role = $this->_createAdminRole();
        // we attach it to the logged user
        $admin_role->users()->attach($this->_user);
        // we create a slide
        $this->_createSlide();

        /***************************************************************************************************************
         * run test
         **************************************************************************************************************/
        $I->amOnPage('/');
        $I->amOnRoute('slides.edit', ['id' => $this->_slide->id]);
        $I->sendAjaxRequest('POST', route('slides.update', [
            'id'      => $this->_slide->id,
            '_token'  => csrf_token(),
            '_method' => 'PUT',
        ]));
        $I->seeCurrentRouteIs('slides.edit', ['id' => $this->_slide->id]);
        $I->see(trans('global.modal.alert.title.error'));
        $I->see(strip_tags(trans('validation.filled', ['attribute' => trans('validation.attributes.title')])));
        $I->see(strip_tags(trans('validation.filled', ['attribute' => trans('validation.attributes.quote')])));
        $I->see(strip_tags(trans('validation.filled', ['attribute' => trans('validation.attributes.previous_slide_id')])));
    }

    public function update_slide_with_no_change(FunctionalTester $I)
    {
        $I->am('Admin');
        $I->wantTo('update a slide with no change on fields');
        $I->expectTo('see a success confirmation message');

        /***************************************************************************************************************
         * settings
         **************************************************************************************************************/
        // we create the admin role
        $admin_role = $this->_createAdminRole();
        // we attach it to the logged user
        $admin_role->users()->attach($this->_user);
        // we create a slide
        $this->_createSlide();

        /***************************************************************************************************************
         * run test
         **************************************************************************************************************/
        $I->amOnPage('/');
        $I->seeCurrentRouteIs('home');
        $I->click(trans('template.front.header.my_account'));
        $I->seeCurrentRouteIs('dashboard.index');
        $I->click(trans('template.back.header.home'));
        $I->seeCurrentRouteIs('home.edit');
        $I->click('edit_' . $this->_slide->id);
        $I->seeCurrentRouteIs('slides.edit', ['id' => $this->_slide->id]);
        $I->click(trans('home.page.action.slide.update'));
        $I->seeCurrentRouteIs('slides.edit', ['id' => $this->_slide->id]);
        $I->see(trans('global.modal.alert.title.success'), 'h3');
        $I->see(strip_tags(trans('home.message.slide.update.success', ['slide' => $this->_slide->title])));
        $I->seeInField('title', $this->_slide->title);
        $I->seeInField('quote', $this->_slide->quote);
        $I->seeOptionIsSelected('#input_previous_slide_id', 'X - ' . trans('home.page.label.slide.first'));
        $I->seeCheckboxIsChecked('#input_active');
        $I->seeRecord('slides', [
            'title'            => $this->_slide->title,
            'quote'            => $this->_slide->quote,
            'picto'            => $this->_slide->picto,
            'background_image' => $this->_slide->background_image,
            'position'         => $this->_slide->position,
            'active'           => $this->_slide->active,
        ]);
    }

    public function update_slide_with_wrong_values_filled_from_ajax(FunctionalTester $I)
    {
        $I->am('Admin');
        $I->wantTo('update a slide with no fields filled form');
        $I->expectTo('see an error message explaining that the data are not correct');

        /***************************************************************************************************************
         * settings
         **************************************************************************************************************/
        // we create the admin role
        $admin_role = $this->_createAdminRole();
        // we attach it to the logged user
        $admin_role->users()->attach($this->_user);
        // we create a slide
        $this->_createSlide();

        /***************************************************************************************************************
         * run test
         **************************************************************************************************************/
        $I->amOnPage('/');
        $I->amOnRoute('slides.edit', ['id' => $this->_slide->id]);
        $I->sendAjaxRequest('POST', route('slides.update', [
            'id'                => $this->_slide->id,
            '_token'            => csrf_token(),
            '_method'           => 'PUT',
            'title'             => '1234',
            'quote'             => 'Ceci est un exemple de texte trop court',
            'picto'             => 'picto_1',
            'background_image'  => 'bg_image_1',
            'active'            => 7,
            'previous_slide_id' => 'test',
        ]));
        $I->seeCurrentRouteIs('slides.edit', ['id' => $this->_slide->id]);
        $I->see(trans('global.modal.alert.title.error'));
        $I->see(strip_tags(trans('validation.string', ['attribute' => trans('validation.attributes.title')])));
        $I->see(strip_tags(trans('validation.min.string', ['attribute' => trans('validation.attributes.quote'), 'min' => 50])));
        $I->see(strip_tags(trans('validation.image', ['attribute' => trans('validation.attributes.picto')])));
        $I->see(strip_tags(trans('validation.image', ['attribute' => trans('validation.attributes.background_image')])));
        $I->see(strip_tags(trans('validation.boolean', ['attribute' => trans('validation.attributes.active')])));
    }

    public function update_slide_with_data_changed(FunctionalTester $I)
    {
        $I->am('Admin');
        $I->wantTo('update slide data');
        $I->expectTo('see a success confirmation message and see that the user data has changed');

        /***************************************************************************************************************
         * settings
         **************************************************************************************************************/
        // we create the admin role
        $admin_role = $this->_createAdminRole();
        // we attach it to the logged user
        $admin_role->users()->attach($this->_user);
        // we create a slide
        $this->_createSlide();
        // we create another slide
        $slide = $this->_slide_repo->create([
            'title'            => 'Aviron universitaire',
            'quote'            => 'Other quote',
            'picto'            => 'picto_2.png',
            'background_image' => 'bg_img_2.jpg',
            'position'         => 2,
            'active'           => true,
        ]);

        /***************************************************************************************************************
         * run test
         **************************************************************************************************************/
        $I->amOnPage('/');
        $I->amOnRoute('slides.edit', ['id' => $this->_slide->id]);
        $I->fillField('title', 'Test');
        $I->fillField('quote', 'Rejoignez le plus grand club d\'aviron universitaire de France. Nous proposons des tarifs avantageux pour tous les étudiants nantais !');
        $I->selectOption('#input_previous_slide_id', $slide->position . ' - ' . $slide->title);
        $I->checkOption('#input_active');
        $I->click(trans('home.page.action.slide.update'));
        $I->seeCurrentRouteIs('slides.edit', ['id' => $this->_slide->id]);
        $I->see(trans('global.modal.alert.title.success'), 'h3');
        $I->see(strip_tags(trans('home.message.slide.update.success', ['slide' => 'Test'])));
        $I->seeInField('title', 'Test');
        $I->seeInField('quote', 'Rejoignez le plus grand club d\'aviron universitaire de France. Nous proposons des tarifs avantageux pour tous les étudiants nantais !');
        $I->seeOptionIsSelected('#input_previous_slide_id', '1 - ' . $slide->title);
        $I->seeCheckboxIsChecked('#input_active');
        $I->seeRecord('slides', [
            'title'            => 'Test',
            'quote'            => 'Rejoignez le plus grand club d\'aviron universitaire de France. Nous proposons des tarifs avantageux pour tous les étudiants nantais !',
            'position'         => 2,
            'active'           => true,
        ]);
    }

    public function delete_slide_without_permission(FunctionalTester $I)
    {
        $I->am('A user with no role');
        $I->wantTo('delete a slide without the permission');
        $I->expectTo('see an error message explaining that I do have the permission to delete the slide');

        /***************************************************************************************************************
         * settings
         **************************************************************************************************************/
        // we create the admin role
        $admin_role = $this->_createAdminRole();
        // we attach it to the logged user
        $admin_role->users()->attach($this->_user);
        // we create a slide
        $this->_createSlide();

        /***************************************************************************************************************
         * run test
         **************************************************************************************************************/
        $admin_role->removePermission('home.slides.delete')->save();
        $I->amOnPage('/');
        $I->amOnRoute('home.edit');
        $I->submitForm('#delete_' . $this->_slide->id, []);
        $I->seeCurrentRouteIs('home.edit');
        $I->see(trans('global.modal.alert.title.error'), 'h3');
        $I->see(strip_tags(trans('permissions.message.access.denied', ['permission' => trans('permissions.home.slides.delete')])));
    }

    public function delete_slide_with_wrong_id(FunctionalTester $I)
    {
        $I->am('A user with no role');
        $I->wantTo('delete a slide with a wrong id');
        $I->expectTo('see an error message explaining the user doesn\'t exists and be redirected to the home edit page');

        /***************************************************************************************************************
         * settings
         **************************************************************************************************************/
        // we create the admin role
        $admin_role = $this->_createAdminRole();
        // we attach it to the logged user
        $admin_role->users()->attach($this->_user);
        // we create a slide
        $this->_createSlide();

        /***************************************************************************************************************
         * run test
         **************************************************************************************************************/
        $I->amOnPage('/');
        $I->amOnRoute('home.edit');
        $I->sendAjaxRequest('POST', route('slides.destroy', ['id' => 1000000000000, '_token' => csrf_token(), '_method' => 'DELETE']));
        $I->seeCurrentRouteIs('home.edit');
        $I->see(trans('global.modal.alert.title.error'));
        $I->see(strip_tags(trans('home.message.slide.find.failure', ['id' => 1000000000000])));
    }

    public function delete_slide(FunctionalTester $I)
    {
        $I->am('Admin');
        $I->wantTo('delete a slide');
        $I->expectTo('see a success confirmation message and see that the deleted slide is not here anymore');

        /***************************************************************************************************************
         * settings
         **************************************************************************************************************/
        // we create the admin role
        $admin_role = $this->_createAdminRole();
        // we attach it to the logged user
        $admin_role->users()->attach($this->_user);
        // we create a slide
        $this->_createSlide();

        /***************************************************************************************************************
         * run test
         **************************************************************************************************************/
        $I->amOnRoute('home.edit');
        $I->submitForm('#delete_' . $this->_slide->id, []);
        $I->seeCurrentRouteIs('home.edit');
        $I->see(trans('global.modal.alert.title.success'), 'h3');
        $I->see(strip_tags(trans('home.message.slide.delete.success', ['slide' => $this->_slide->title])));
        $I->dontSee($this->_slide->title, '.table-list');
        $I->dontSee($this->_slide->quote, '.table-list');
    }

    public function activate_slide_without_permission(FunctionalTester $I)
    {
        $I->am('A user with no role');
        $I->wantTo('activate a slide without the permission');
        $I->expectTo('see an error message explaining that I do not have the permission to activate the user');

        /***************************************************************************************************************
         * settings
         **************************************************************************************************************/
        // we create the admin role
        $admin_role = $this->_createAdminRole();
        // we attach it to the logged user
        $admin_role->users()->attach($this->_user);
        // we create a slide
        $this->_createSlide();
        $this->_slide->active = false;
        $this->_slide->save();

        /***************************************************************************************************************
         * run test
         **************************************************************************************************************/
        $admin_role->removePermission('home.slides.update')->save();
        $I->amOnPage('/');
        $I->amOnRoute('home.edit');
        $I->checkOption('#activate_' . $this->_slide->id);
        $I->submitForm('#form_activate_' . $this->_slide->id, []);
        $I->seeResponseCodeIs(401);
        $I->see(strip_tags(trans('permissions.message.access.denied', ['permission' => trans('permissions.home.slides.update')])));
        $I->seeRecord('slides', [
            'id' => $this->_slide->id,
            'active' => false
        ]);
    }

    public function activate_slide_with_wrong_id(FunctionalTester $I)
    {
        $I->am('A user with no role');
        $I->wantTo('activate a slide with the wrong id');
        $I->expectTo('see an error message explaining that the slide does not exists');

        /***************************************************************************************************************
         * settings
         **************************************************************************************************************/
        // we create the admin role
        $admin_role = $this->_createAdminRole();
        // we attach it to the logged user
        $admin_role->users()->attach($this->_user);
        // we create a slide
        $this->_createSlide();
        $this->_slide->active = false;
        $this->_slide->save();

        /***************************************************************************************************************
         * run test
         **************************************************************************************************************/
        $I->amOnPage('/');
        $I->amOnRoute('home.edit');
        $I->sendAjaxRequest('POST', route('slides.activate', ['id' => 1000000000000, '_token' => csrf_token(), 'active' => true]));
        $I->seeResponseCodeIs(401);
        $I->see(strip_tags(trans('home.message.slide.find.failure', ['id' => 1000000000000])));
    }

    public function activate_slide(FunctionalTester $I)
    {
        $I->am('Admin');
        $I->wantTo('activate a slide');
        $I->expectTo('see that the slide has been activated');

        /***************************************************************************************************************
         * settings
         **************************************************************************************************************/
        // we create the admin role
        $admin_role = $this->_createAdminRole();
        // we attach it to the logged user
        $admin_role->users()->attach($this->_user);
        // we create a slide
        $this->_createSlide();
        $this->_slide->active = false;
        $this->_slide->save();

        /***************************************************************************************************************
         * run test
         **************************************************************************************************************/
        $I->amOnPage('/');
        $I->amOnRoute('home.edit');
        $I->checkOption('#activate_' . $this->_slide->id);
        $I->submitForm('#form_activate_' . $this->_slide->id, []);
        $I->seeResponseCodeIs(200);
//        $I->see(strip_tags(trans('home.message.slide.activation.success.label', ['action' => trans_choice('users.message.activation.success.action', true), 'slide' => $this->_slide->title])));
        $I->seeRecord('slides', [
            'id' => $this->_slide->id,
            'active' => true
        ]);
    }

    public function deactivate_slide(FunctionalTester $I)
    {
        $I->am('Admin');
        $I->wantTo('deactivate a slide');
        $I->expectTo('see that the user has been deactivated');

        /***************************************************************************************************************
         * settings
         **************************************************************************************************************/
        // we create the admin role
        $admin_role = $this->_createAdminRole();
        // we attach it to the logged user
        $admin_role->users()->attach($this->_user);
        // we create a slide
        $this->_createSlide();

        /***************************************************************************************************************
         * run test
         **************************************************************************************************************/
        $I->amOnPage('/');
        $I->amOnRoute('home.edit');
        $I->uncheckOption('#activate_' . $this->_slide->id);
        $I->submitForm('#form_activate_' . $this->_slide->id, []);
        $I->seeResponseCodeIs(200);
//        $I->see(strip_tags(trans('home.message.slide.activation.success.label', ['action' => trans_choice('users.message.activation.success.action', false), 'slide' => $this->_slide->title])));
        $I->seeRecord('slides', [
            'id' => $this->_slide->id,
            'active' => false
        ]);
    }

    public function checkHomePageContainsDataSetInAdmin(FunctionalTester $I)
    {
        $I->am('Admin');
        $I->wantTo('check on the home page that the data saved from the admin panel are shown');
        $I->expectTo('see the saved data on the home page');

        /***************************************************************************************************************
         * settings
         **************************************************************************************************************/
        // we create the admin role
        $admin_role = $this->_createAdminRole();
        // we attach it to the logged user
        $admin_role->users()->attach($this->_user);
        // we create a slide
        $this->_createSlide();

        /***************************************************************************************************************
         * run test
         **************************************************************************************************************/
        $I->amOnPage('/');
        $I->seeCurrentRouteIs('home');
        $I->see($this->_slide->title, 'h2');
        $I->see($this->_slide->quote, '.quote');
        $I->seeElement('//img[@src="' . url('img/slides/' . $this->_slide->picto) . '"]');
        $I->seeElement('//div[@data-background-image="' . url('img/slides/' . $this->_slide->background_image) . '"]');
        $I->see(json_decode(file_get_contents(storage_path('app/home/content.json')))->title, 'h2');
        $I->see(json_decode(file_get_contents(storage_path('app/home/content.json')))->description);
        $I->seeElement('//a[@href="' . json_decode(file_get_contents(storage_path('app/home/content.json')))->video_link . '"]');
    }
}
