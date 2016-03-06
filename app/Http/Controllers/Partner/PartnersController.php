<?php

namespace App\Http\Controllers\Partner;

use App\Http\Controllers\Controller;
use App\Repositories\Partner\PartnerRepositoryInterface;
use Illuminate\Http\Request;

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
        if(!$this->requirePermission('partners.list')){
            return redirect()->back();
        }

        // SEO Meta settings
        $this->seoMeta['page_title'] = trans('seo.back.partners.index');

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
                'sort_by_default' => true,
            ],
            [
                'title'    => trans('partners.page.label.activation'),
                'key'      => 'active',
                'activate' => 'partners.activate',
            ],
        ];

        // we set the routes used in the table list
        $routes = [
            'index'   => 'partners.index',
            'create'  => 'partners.create',
            'edit'    => 'partners.edit',
            'destroy' => 'partners.destroy',
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
            'name',
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
        return view('pages.back.partners-list')->with($data);
    }

    public function create()
    {
        // we check the current user permission
        if(!$this->requirePermission('partners.create')){
            return redirect()->back();
        }

        // SEO Meta settings
        $this->seoMeta['page_title'] = trans('seo.back.partners.create');

        // we get the partner list
        $partner_list = $this->repository->orderBy('position', 'asc')->get();

        // we prepare the master role status and we add at the beginning of the role list
        $first_slide = new \stdClass();
        $first_slide->id = 0;
        $first_slide->name = trans('home.page.label.slide.first');
        $partner_list->prepend($first_slide);


        // prepare data for the view
        $data = [
            'seoMeta'      => $this->seoMeta,
            'partner_list' => $partner_list,
        ];

        // return the view with data
        return view('pages.back.partner-edit')->with($data);
    }

    public function store(Request $request)
    {
        // we check the current user permission
        if(!$this->requirePermission('partners.create')){
            return redirect()->back();
        }

        // we convert the "on" value to a boolean value
        $request->merge([
            'active' => filter_var($request->get('active'), FILTER_VALIDATE_BOOLEAN),
        ]);

        // we set the validation rules
        $rules = [
            'logo'   => 'image|mimes:png|image_size:*,>=100',
            'name'   => 'required|string',
            'url'    => 'url',
            'active' => 'required|boolean',
        ];
        if ($request->get('previous_partner_id') === '0') {
            $rules['previous_partner_id'] = 'numeric';
        } else {
            $rules['previous_partner_id'] = 'required|numeric|exists:slides,id';
        }

        // we check inputs validity
        if(!$this->checkInputsValidity($request->all(), $rules, $request)){
            return redirect()->back();
        }

        // we create the user
        $partner = $this->repository->create($request->except('_token', 'previous_partner_id', 'logo'));

        try {
            // we update the slides positions
            $partner->position = $this->repository->updatePositions($request->get('previous_slide_id'));

            // we store the photo
            if ($logo = $request->file('logo')) {

                // we optimize, resize and save the image
                $file_name = \ImageManager::optimizeAndResize(
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
            \Modal::alert([
                trans('partners.message.create.success', ['name' => $partner->name]),
            ], 'success');

            return redirect(route('partners.index'));
        } catch (\Exception $e) {
            // we flash the request
            $request->flash();

            // we delete the user if something went wrong after the user creation
            if ($partner) {
                $partner->delete();
            }

            // we log the error and we notify the current user
            \Log::error($e);
            \Modal::alert([
                trans('partners.message.create.failure', ['name' => $partner->name]),
                trans('global.message.global.failure.contact.support', ['email' => config('settings.support_email'),]),
            ], 'error');

            return redirect()->back();
        }
    }

    public function edit($id)
    {
        // we check the current user permission
        if(!$this->requirePermission('partners.view')){
            return redirect()->back();
        }

        // we get the partner
        $partner = $this->repository->find($id);

        // we check if the role exists
        if (!$partner) {
            \Modal::alert([
                trans('partners.message.find.failure'),
                trans('global.message.global.failure.contact.support', ['email' => config('settings.support_email'),]),
            ], 'error');

            return redirect()->back();
        }

        // SEO Meta settings
        $this->seoMeta['page_title'] = trans('seo.back.partners.edit');

        // we get the list without the current entity
        $partner_list = $this->repository->orderBy('position', 'asc')->where('id', '<>', $id)->get();

        // we prepare the first entity and we add it at the beginning of the list
        $first_slide = new \stdClass();
        $first_slide->id = 0;
        $first_slide->name = trans('home.page.label.slide.first');
        $partner_list->prepend($first_slide);

        // we prepare the data for breadcrumbs
        $breadcrumbs_data = [
            $partner->name,
        ];

        // prepare data for the view
        $data = [
            'seoMeta'          => $this->seoMeta,
            'partner'          => $partner,
            'partner_list'     => $partner_list,
            'breadcrumbs_data' => $breadcrumbs_data,
        ];

        // return the view with data
        return view('pages.back.partner-edit')->with($data);
    }

    public function update(Request $request)
    {
        // we check the current user permission
        if(!$this->requirePermission('partners.update')){
            return redirect()->back();
        }

        // we convert the "on" value to a boolean value
        $request->merge([
            'active' => filter_var($request->get('active'), FILTER_VALIDATE_BOOLEAN),
        ]);

        // we set the validation rules
        $rules = [
            '_id'    => 'numeric|exists:partners,id',
            'logo'   => 'image|mimes:png|image_size:*,>=100',
            'name'   => 'required|string',
            'url'    => 'url',
            'active' => 'required|boolean',
        ];
        if ($request->get('previous_partner_id') === '0') {
            $rules['previous_partner_id'] = 'numeric';
        } else {
            $rules['previous_partner_id'] = 'required|numeric|exists:slides,id';
        }

        // we check inputs validity
        if(!$this->checkInputsValidity($request->all(), $rules, $request)){
            return redirect()->back();
        }

        try {
            $partner = $this->repository->find($request->get('_id'));

            // we get the inputs
            $inputs = $request->except('_token', '_id');

            // we store the logo
            if ($logo = $request->file('logo')) {
                // we optimize, resize and save the image
                $file_name = \ImageManager::optimizeAndResize(
                    $logo->getRealPath(),
                    $partner->imageName('logo'),
                    $logo->getClientOriginalExtension(),
                    $partner->storagePath(),
                    $partner->availableSizes('logo')
                );
                // we add the image name to the inputs for saving
                $inputs['logo'] = $file_name;
            }

            // we update the partner
            $partner->update($inputs);

            // we notify the current user
            \Modal::alert([
                trans('partners.message.update.success'),
            ], 'success');

            return redirect()->back();
        } catch (\Exception $e) {
            // we flash the request
            $request->flash();

            // we log the error and we notify the current user
            \Log::error($e);
            \Modal::alert([
                trans('partners.message.update.failure'),
                trans('global.message.global.failure.contact.support', ['email' => config('settings.support_email'),]),
            ], 'error');

            return redirect()->back();
        }
    }

    public function destroy(Request $request)
    {
        // we check the current user permission
        if(!$this->requirePermission('partners.delete')){
            return redirect()->back();
        }

        // we get the partner
        if (!$partner = $this->repository->find($request->get('_id'))) {
            \Modal::alert([
                trans('partners.message.find.failure'),
            ], 'error');

            return redirect()->back();
        }

        try {
            // we remove the partner logo
            if ($partner->logo) {
                \ImageManager::remove(
                    $partner->logo,
                    $partner->storagePath(),
                    $partner->availableSizes('logo')
                );
            }

            // we delete the role
            $partner->delete();

            // we sanitize the positions
            $this->repository->sanitizePositions();

            \Modal::alert([
                trans('partners.message.delete.success', ['name' => $partner->name]),
            ], 'success');

            return redirect()->back();
        } catch (\Exception $e) {
            \Log::error($e);
            \Modal::alert([
                trans('partners.message.delete.failure', ['name' => $partner->name]),
                trans('global.message.global.failure.contact.support', ['email' => config('settings.support_email')]),
            ], 'error');

            return redirect()->back();
        }
    }

    public function activate(Request $request)
    {
        // we check the current user permission
        $permission = 'partners.update';
        if (!\Sentinel::getUser()->hasAccess([$permission])) {
            return response([
                trans('permissions.message.access.denied') . " : <b>" .
                trans('permissions.' . $permission) . "</b>",
            ], 400);
        }

        // we get the model
        if (!$partner = $this->repository->find($request->get('id'))) {
            return response([
                trans('partners.message.find.failure', ['id' => $request->get('id')]),
            ], 401);
        }

        // we convert the "on" value to the activation order to a boolean value
        $request->merge([
            'activation_order' => filter_var($request->get('activation_order'), FILTER_VALIDATE_BOOLEAN),
        ]);

        // we check the inputs
        $errors = [];
        $validator = \Validator::make($request->all(), [
            'id'               => 'required|exists:partners,id',
            'activation_order' => 'required|boolean',
        ]);
        foreach ($validator->errors()->all() as $error) {
            $errors[] = $error;
        }
        // if errors are found
        if (count($errors)) {
            return response([
                $errors,
            ], 400);
        }

        try {
            $partner->active = $request->activation_order;
            $partner->save();

            return response([
                trans('partners.message.activation.success', ['name' => $partner->name]),
            ], 200);
        } catch (\Exception $e) {
            \Log::error($e);

            return response([
                trans('partners.message.activation.failure', ['name' => $partner->name]),
            ], 401);
        }
    }
}
