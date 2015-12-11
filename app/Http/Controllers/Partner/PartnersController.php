<?php

namespace App\Http\Controllers\Partner;

use App\Http\Controllers\Controller;
use App\Repositories\Partner\PartnerRepositoryInterface;
use Illuminate\Http\Request;

class PartnersController extends Controller
{

    /**
     * UsersController constructor.
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
        $required = 'partners.list';
        if (!\Sentinel::getUser()->hasAccess([$required])) {
            \Modal::alert([
                trans('permissions.message.access.denied') . " : <b>" . trans('permissions.' . $required) . "</b>",
            ], 'error');

            return redirect()->back();
        }

        // SEO Meta settings
        $this->seoMeta['page_title'] = trans('seo.partners.index');

        // we define the table list columns
        $columns = [
            [
                'title' => trans('partners.page.label.logo'),
                'key'   => 'logo',
                'image' => [
                    'storage_path' => \Sentinel::createModel()->storagePath(),
                    'size'         => [
                        'thumbnail' => 'admin',
                        'detail'    => 'large',
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
                'title'   => trans('partners.page.label.position'),
                'key'     => 'position',
                'sort_by' => 'partners.position',
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

    public function edit($id)
    {
        // we check the current user permission
        $required = 'partners.view';
        if (!\Sentinel::getUser()->hasAccess([$required])) {
            \Modal::alert([
                trans('permissions.message.access.denied') . " : <b>" . trans('permissions.' . $required) . "</b>",
            ], 'error');

            return redirect()->back();
        }

        // we get the partner
        $partner = $this->repository->find($id);

        // SEO Meta settings
        $this->seoMeta['page_title'] = trans('seo.partners.edit');


        // we check if the role exists
        if (!$partner) {
            \Modal::alert([
                trans('partners.message.find.failure'),
                trans('global.message.global.failure.contact.support', ['email' => config('settings.support_email'),]),
            ], 'error');

            return redirect()->back();
        }

        // we prepare the data for breadcrumbs
        $breadcrumbs_data = [
            $partner->name,
        ];

        // prepare data for the view
        $data = [
            'seoMeta'          => $this->seoMeta,
            'partner'          => $partner,
            'breadcrumbs_data' => $breadcrumbs_data,
        ];

        // return the view with data
        return view('pages.back.partner-edit')->with($data);
    }

    public function create()
    {
        // SEO Meta settings
        $this->seoMeta['page_title'] = trans('seo.partners.create');

        // prepare data for the view
        $data = [
            'seoMeta' => $this->seoMeta,
        ];

        // return the view with data
        return view('pages.back.partner-edit')->with($data);
    }

    public function store(Request $request)
    {
        // we check the current user permission
        $required = 'partners.create';
        if (!\Sentinel::getUser()->hasAccess([$required])) {
            \Modal::alert([
                trans('permissions.message.access.denied') . " : <b>" . trans('permissions.' . $required) . "</b>",
            ], 'error');

            return redirect()->back();
        }

        // we get the inputs
        $inputs = $request->except('_token');

        // we check the inputs
        $errors = [];
        $validator = \Validator::make($inputs, [
            'logo' => 'required|image|mimes:jpg,jpeg,png',
            'name' => 'required|string',
            'url'  => 'required|url',
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

            return redirect()->back();
        }

        // we create the user
        $partner = $this->repository->create($inputs);

        try {

            // we store the photo
            if (isset($inputs['logo']) && !empty($logo = $inputs['logo'])) {

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
                $partner->save();
            }

            // we notify the current user
            \Modal::alert([
                trans('users.message.creation.success', ['name' => $partner->name]),
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
                trans('users.message.creation.failure', ['name' => $request->get('name')]),
                trans('global.message.global.failure.contact.support', ['email' => config('settings.support_email'),]),
            ], 'error');

            return redirect()->back();
        }
    }

    public function update(Request $request)
    {
        // we check the current user permission
        $required = 'partners.update';
        if (!\Sentinel::getUser()->hasAccess([$required])) {
            \Modal::alert([
                trans('permissions.message.access.denied') . " : <b>" . trans('permissions.' . $required) . "</b>",
            ], 'error');

            return redirect()->back();
        }

        // we get the user
        if (!$partner = $this->repository->find($request->get('_id'))) {
            \Modal::alert([
                trans('partners.message.find.failure', ['id' => $request->get('_id')]),
            ], 'error');

            return redirect()->back();
        }

        // we check the inputs
        $errors = [];
        $inputs = $request->all();
        $validator = \Validator::make($inputs, [
            'logo' => 'required|image|mimes:jpg,jpeg,png',
            'name' => 'required|string',
            'url'  => 'required|url',
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

            return redirect()->back();
        }

        try {
            // we store the logo
            if (isset($inputs['logo']) && !empty($logo = $inputs['logo'])) {
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
                trans('users.message.account.success'),
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
        $required = 'partners.delete';
        if (!\Sentinel::getUser()->hasAccess([$required])) {
            \Modal::alert([
                trans('permissions.message.access.denied') . " : <b>" . trans('permissions.' . $required) . "</b>",
            ], 'error');

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
            // we remove the users photos
            if ($partner->logo) {
                \ImageManager::remove(
                    $partner->logo,
                    $partner->storagePath(),
                    $partner->availableSizes('logo')
                );
            }

            // we delete the role
            $partner->delete();

            \Modal::alert([
                trans('users.message.delete.success', ['name' => $partner->name]),
            ], 'success');

            return redirect()->back();
        } catch (\Exception $e) {
            \Log::error($e);
            \Modal::alert([
                trans('users.message.delete.failure', ['name' => $partner->name]),
                trans('global.message.global.failure.contact.support', ['email' => config('settings.support_email')]),
            ], 'error');

            return redirect()->back();
        }
    }

    public function activate(Request $request)
    {
        // we check the current user permission
        $required = 'partners.update';
        if (!\Sentinel::getUser()->hasAccess([$required])) {
            return response([
                trans('permissions.message.access.denied') . " : <b>" . trans('permissions.' . $required) . "</b>",
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
            return response($errors, 401);
        }

        // we get the partner
        $partner = $this->repository->find($request->get('id'));

        try {
            $partner->active = $request->activation_order;
            $partner->save();

            return response([
                trans('partners.message.activation.success'),
            ], 200);
        } catch (\Exception $e) {
            \Log::error($e);

            return response([
                trans('partners.message.activation.failure'),
            ], 401);
        }
    }
}
