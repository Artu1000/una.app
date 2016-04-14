<?php

namespace App\Http\Controllers\Partner;

use App\Http\Controllers\Controller;
use App\Repositories\Partner\PartnerRepositoryInterface;
use CustomLog;
use Entry;
use Exception;
use Illuminate\Http\Request;
use ImageManager;
use Modal;
use Permission;
use Sentinel;
use stdClass;
use TableList;
use Validation;

class PartnersController extends Controller
{

    /**
     * PartnersController constructor.
     * @param PartnerRepositoryInterface $partner
     */
    public function __construct(PartnerRepositoryInterface $partner)
    {
        parent::__construct();
        $this->repository = $partner;
    }

    /**
     * @param Request $request
     * @return $this|\Illuminate\Http\RedirectResponse
     */
    public function index(Request $request)
    {
        // we check the current user permission
        if (!Permission::hasPermission('partners.list')) {
            return redirect()->route('dashboard.index');
        }

        // SEO Meta settings
        $this->seo_meta['page_title'] = trans('seo.back.partners.index');

        // we define the table list columns
        $columns = [
            [
                'title' => trans('partners.page.label.logo'),
                'key'   => 'logo',
                'image' => [
                    'storage_path' => $this->repository->getModel()->storagePath(),
                    'size'         => [
                        'thumbnail' => 'admin',
                        'detail'    => 'logo',
                    ],
                ],
            ],
            [
                'title'   => trans('partners.page.label.name'),
                'key'     => 'name',
                'sort_by' => 'partners.name',
            ],
            [
                'title'   => trans('partners.page.label.url'),
                'key'     => 'url',
                'sort_by' => 'partners.url',
            ],
            [
                'title'           => trans('partners.page.label.position'),
                'key'             => 'position',
                'sort_by'         => 'partners.position',
                'sort_by_default' => 'asc',
            ],
            [
                'title'    => trans('schedules.page.label.activation'),
                'key'      => 'active',
                'activate' => [
                    'route'  => 'partners.activate',
                    'params' => [],
                ],
            ],
        ];

        // we set the routes used in the table list
        $routes = [
            'index'   => [
                'route'  => 'partners.index',
                'params' => [],
            ],
            'create'  => [
                'route'  => 'partners.create',
                'params' => [],
            ],
            'edit'    => [
                'route'  => 'partners.edit',
                'params' => [],
            ],
            'destroy' => [
                'route'  => 'partners.destroy',
                'params' => [],
            ],
        ];

        // we instantiate the query
        $query = $this->repository->getModel()->query();

        // we prepare the confirm config
        $confirm_config = [
            'action'     => trans('partners.page.action.delete'),
            'attributes' => ['name'],
        ];

        // we prepare the search config
        $search_config = [
            [
                'key'      => trans('partners.page.label.name'),
                'database' => 'partners.name',
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
            'seo_meta'      => $this->seo_meta,
        ];

        // return the view with data
        return view('pages.back.partners-list')->with($data);
    }

    /**
     * @return mixed
     */
    public function create()
    {
        // we check the current user permission
        if (!Permission::hasPermission('partners.create')) {
            // we redirect the current user to the partners list if he has the required permission
            if (Sentinel::getUser()->hasAccess('partners.list')) {
                return redirect()->route('partners.index');
            } else {
                // or we redirect the current user to the dashboard
                return redirect()->route('dashboard.index');
            }
        }

        // SEO Meta settings
        $this->seo_meta['page_title'] = trans('seo.back.partners.create');

        // we get the partner list
        $partner_list = $this->repository->orderBy('position', 'asc')->get();

        // we prepare the master role status and we add at the beginning of the role list
        $first_slide = new stdClass();
        $first_slide->id = 0;
        $first_slide->name = trans('home.page.label.slide.first');
        $partner_list->prepend($first_slide);


        // prepare data for the view
        $data = [
            'seo_meta'     => $this->seo_meta,
            'partner_list' => $partner_list,
        ];

        // return the view with data
        return view('pages.back.partner-edit')->with($data);
    }

    /**
     * @param Request $request
     * @return mixed
     */
    public function store(Request $request)
    {
        // we check the current user permission
        if (!Permission::hasPermission('partners.create')) {
            // we redirect the current user to the partners list if he has the required permission
            if (Sentinel::getUser()->hasAccess('partners.list')) {
                return redirect()->route('partners.index');
            } else {
                // or we redirect the current user to the home page
                return redirect()->route('dashboard.index');
            }
        }

        // if the active field is not given, we set it to false
        $request->merge(['active' => $request->get('active', false)]);

        // we sanitize the entries
        $request->replace(Entry::sanitizeAll($request->all()));

        // we set the validation rules
        $rules = [
            'logo'   => 'required|image|mimes:png|image_size:*,>=100',
            'name'   => 'required|string',
            'url'    => 'url',
            'active' => 'required|boolean',
        ];
        if ($request->get('previous_partner_id') === 0) {
            $rules['previous_partner_id'] = 'numeric';
        } else {
            $rules['previous_partner_id'] = 'required|numeric|exists:slides,id';
        }

        // we check the inputs validity
        if (!Validation::check($request->all(), $rules)) {
            // we flash the request
            $request->flashExcept('logo');

            return redirect()->back();
        }

        // we create the partner
        $partner = $this->repository->create($request->except('_token', 'previous_partner_id', 'logo'));

        try {
            // we update the slides positions
            $partner->position = $this->repository->updatePositions($request->get('previous_slide_id'));

            // we store the photo
            if ($logo = $request->file('logo')) {
                // we optimize, resize and save the image
                $file_name = ImageManager::optimizeAndResize(
                    $logo->getRealPath(),
                    $partner->imageName('logo'),
                    $logo->getClientOriginalExtension(),
                    $partner->storagePath(),
                    $partner->availableSizes('logo')
                );

                // we update the image name
                $partner->logo = $file_name;
            }

            // we update the partner
            $partner->save();

            // we sanitize the positions
            $this->repository->sanitizePositions();

            // we notify the current user
            Modal::alert([
                trans('partners.message.create.success', ['partner' => $partner->name]),
            ], 'success');

            return redirect(route('partners.index'));
        } catch (Exception $e) {
            // we flash the request
            $request->flashExcept('logo');

            // we log the error
            CustomLog::error($e);

            // we notify the current user
            Modal::alert([
                trans('partners.message.create.failure', ['partner' => $partner->name]),
                trans('global.message.global.failure.contact.support', ['email' => config('settings.support_email'),]),
            ], 'error');

            return redirect()->back();
        }
    }

    /**
     * @param $id
     * @return mixed
     */
    public function edit($id)
    {
        // we check the current user permission
        if (!Permission::hasPermission('partners.view')) {
            // we redirect the current user to the partners list if he has the required permission
            if (Sentinel::getUser()->hasAccess('partners.list')) {
                return redirect()->route('partners.index');
            } else {
                // or we redirect the current user to the home page
                return redirect()->route('dashboard.index');
            }
        }

        // we get the partner
        try {
            $partner = $this->repository->find($id);
        } catch (Exception $e) {
            // we notify the current user
            Modal::alert([
                trans('partners.message.find.failure', ['id' => $id]),
                trans('global.message.global.failure.contact.support', ['email' => config('settings.support_email')]),
            ], 'error');

            return redirect()->back();
        }

        // SEO Meta settings
        $this->seo_meta['page_title'] = trans('seo.back.partners.edit');

        // we get the list without the current entity
        $partner_list = $this->repository->orderBy('position', 'asc')->where('id', '<>', $id)->get();

        // we prepare the first entity and we add it at the beginning of the list
        $first_slide = new stdClass();
        $first_slide->id = 0;
        $first_slide->name = trans('home.page.label.slide.first');
        $partner_list->prepend($first_slide);

        // we prepare the data for breadcrumbs
        $breadcrumbs_data = [
            'partner' => $partner,
        ];

        // prepare data for the view
        $data = [
            'seo_meta'         => $this->seo_meta,
            'partner'          => $partner,
            'partner_list'     => $partner_list,
            'breadcrumbs_data' => $breadcrumbs_data,
        ];

        // return the view with data
        return view('pages.back.partner-edit')->with($data);
    }

    /**
     * @param $id
     * @param Request $request
     * @return mixed
     */
    public function update($id, Request $request)
    {
        // we get the partner
        try {
            $partner = $this->repository->find($id);
        } catch (Exception $e) {
            // we notify the current user
            Modal::alert([
                trans('partners.message.find.failure', ['id' => $id]),
                trans('global.message.global.failure.contact.support', ['email' => config('settings.support_email')]),
            ], 'error');

            return redirect()->back();
        }

        // we check the current user permission
        if (!Permission::hasPermission('partners.update')) {
            // we redirect the current user to the permissions list if he has the required permission
            if (Sentinel::getUser()->hasAccess('partners.view')) {
                return redirect()->route('partners.edit', ['id' => $id]);
            } else {
                // or we redirect the current user to the home page
                return redirect()->route('dashboard.index');
            }
        }

        // if the active field is not given, we set it to false
        $request->merge(['active' => $request->get('active', false)]);

        // we sanitize the entries
        $request->replace(Entry::sanitizeAll($request->all()));

        // we set the validation rules
        $rules = [
            'logo'   => 'required|image|mimes:png|image_size:*,>=100',
            'name'   => 'required|string',
            'url'    => 'url',
            'active' => 'required|boolean',
        ];
        if ($request->get('previous_partner_id') === 0) {
            $rules['previous_partner_id'] = 'numeric';
        } else {
            $rules['previous_partner_id'] = 'required|numeric|exists:slides,id';
        }
        // we check the inputs validity
        if (!Validation::check($request->all(), $rules)) {
            // we flash the request
            $request->flashExcept('logo');

            return redirect()->back();
        }

        try {
            // we update the schedule
            $partner->update($request->except('_token', 'logo'));

            // we store the logo
            if ($logo = $request->file('logo')) {
                // we optimize, resize and save the image
                $file_name = ImageManager::optimizeAndResize(
                    $logo->getRealPath(),
                    $partner->imageName('logo'),
                    $logo->getClientOriginalExtension(),
                    $partner->storagePath(),
                    $partner->availableSizes('logo')
                );
                // we add the image name to the inputs for saving
                $partner->logo = $file_name;
                $partner->save();
            }

            // we notify the current user
            Modal::alert([
                trans('partners.message.update.success'),
            ], 'success');

            return redirect()->back();
        } catch (Exception $e) {
            // we flash the request
            $request->flashExcept('logo');

            // we log the error
            CustomLog::error($e);

            // we notify the current user
            Modal::alert([
                trans('partners.message.update.failure'),
                trans('global.message.global.failure.contact.support', ['email' => config('settings.support_email'),]),
            ], 'error');

            return redirect()->back();
        }
    }

    /**
     * @param $id
     * @param Request $request
     * @return mixed
     */
    public function destroy($id, Request $request)
    {
        // we check the current user permission
        if (!Permission::hasPermission('partners.delete')) {
            // we redirect the current user to the partners list if he has the required permission
            if (Sentinel::getUser()->hasAccess('partners.list')) {
                return redirect()->route('partners.index');
            } else {
                // or we redirect the current user to the home page
                return redirect()->route('dashboard.index');
            }
        }

        // we get the partner
        try {
            $partner = $this->repository->find($id);
        } catch (Exception $e) {
            // we notify the current user
            Modal::alert([
                trans('partners.message.find.failure', ['id' => $id]),
                trans('global.message.global.failure.contact.support', ['email' => config('settings.support_email')]),
            ], 'error');

            return redirect()->back();
        }

        try {
            // we remove the partner logo
            if ($partner->logo) {
                ImageManager::remove(
                    $partner->logo,
                    $partner->storagePath(),
                    $partner->availableSizes('logo')
                );
            }

            Modal::alert([
                trans('partners.message.delete.success', ['partner' => $partner->name]),
            ], 'success');

            // we delete the role
            $partner->delete();

            // we sanitize the positions
            $this->repository->sanitizePositions();

            return redirect()->back();
        } catch (Exception $e) {
            // we log the error
            CustomLog::error($e);

            // we notify the current user
            Modal::alert([
                trans('partners.message.delete.failure', ['partner' => $partner->name]),
                trans('global.message.global.failure.contact.support', ['email' => config('settings.support_email')]),
            ], 'error');

            return redirect()->back();
        }
    }

    /**
     * @param $id
     * @param Request $request
     * @return mixed
     */
    public function activate($id, Request $request)
    {
        // we get the partner
        try {
            $partner = $this->repository->find($id);
        } catch (Exception $e) {
            // we notify the current user
            return response([
                'message' => [
                    trans('partners.message.find.failure', ['id' => $id]),
                    trans('global.message.global.failure.contact.support', ['email' => config('settings.support_email'),]),
                ],
            ], 401);
        }

        // we check the current user permission
        if ($permission_denied = Permission::hasPermissionJson('partners.update')) {
            return response([
                'active'  => $partner->active,
                'message' => [$permission_denied],
            ], 401);
        }

        // if the active field is not given, we set it to false
        $request->merge(['active' => $request->get('active', false)]);

        // we sanitize the entries
        $request->replace(Entry::sanitizeAll($request->all()));

        // we check the inputs validity
        $rules = [
            'active' => 'required|boolean',
        ];
        if (is_array($errors = Validation::check($request->all(), $rules, true))) {
            return response([
                'active'  => $partner->active,
                'message' => $errors,
            ], 401);
        }

        try {
            $partner->active = $request->get('active');
            $partner->save();

            return response([
                'active'  => $partner->active,
                'message' => [
                    trans('partners.message.activation.success.label', ['action' => trans_choice('partners.message.activation.success.action', $partner->active), 'partner' => $partner->label]),
                ],
            ], 200);
        } catch (Exception $e) {
            // we log the error
            CustomLog::error($e);

            return response([
                trans('patners.message.activation.failure', ['partner' => $partner->label]),
            ], 401);
        }
    }
}
