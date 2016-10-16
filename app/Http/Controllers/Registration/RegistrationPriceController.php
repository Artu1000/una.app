<?php

namespace App\Http\Controllers\Registration;

use App\Http\Controllers\Controller;
use App\Repositories\Registration\RegistrationPriceRepositoryInterface;
use CustomLog;
use Exception;
use Illuminate\Http\Request;
use InputSanitizer;
use Modal;
use Permission;
use Sentinel;
use Validation;


class RegistrationPriceController extends Controller
{

    /**
     * Create a new home controller instance.
     *
     * @return void
     */
    public function __construct(RegistrationPriceRepositoryInterface $price)
    {
        parent::__construct();
        $this->repository = $price;
    }

    /**
     * @return $this|\Illuminate\Http\RedirectResponse
     */
    public function create()
    {
        // we check the current user permission
        if (!Permission::hasPermission('registration.prices.create')) {
            // we redirect the current user to the registration edition page if he has the required permission
            if (Sentinel::getUser()->hasAccess('registration.page.view')) {
                return redirect()->route('registration.page.edit');
            } else {
                // or we redirect the current user to the dashboard
                return redirect()->route('dashboard.index');
            }
        }

        // SEO Meta settings
        $this->seo_meta['page_title'] = trans('seo.back.registration.price.create');

        // prepare data for the view
        $data = [
            'seo_meta' => $this->seo_meta,
        ];

        // return the view with data
        return view('pages.back.registration-price-edit')->with($data);
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function store(Request $request)
    {
        // we check the current user permission
        if (!Permission::hasPermission('registration.prices.create')) {
            // we redirect the current user to the registration edition page if he has the required permission
            if (Sentinel::getUser()->hasAccess('registration.page.view')) {
                return redirect()->route('registration.page.edit');
            } else {
                // or we redirect the current user to the dashboard
                return redirect()->route('dashboard.index');
            }
        }

        // if the active field is not given, we set it to false
        $request->merge(['active' => $request->get('active', false)]);

        // we sanitize the entries
        $request->replace(InputSanitizer::sanitize($request->all()));

        // we check inputs validity
        $rules = [
            'label'  => 'required|string',
            'price'  => 'required|numeric',
            'active' => 'required|boolean',
        ];
        // we check the inputs validity
        if (!Validation::check($request->all(), $rules)) {
            // we flash the request
            $request->flash();

            return redirect()->back();
        }

        try {
            // we create the schedule
            $price = $this->repository->create($request->except('_token'));

            // we notify the current user
            Modal::alert([
                trans('registration.message.price.creation.success', ['price' => $price->label]),
            ], 'success');

            return redirect(route('registration.page.edit'));
        } catch (Exception $e) {
            // we flash the request
            $request->flash();

            // we log the error
            CustomLog::error($e);

            // we notify the current user
            Modal::alert([
                trans('registration.message.price.creation.failure', ['price' => $request->get('label')]),
                trans('global.message.global.failure.contact.support', ['email' => config('settings.support_email')]),
            ], 'error');

            return redirect()->back();
        }
    }

    /**
     * @param $id
     * @return $this|\Illuminate\Http\RedirectResponse
     */
    public function edit($id)
    {
        // we check the current user permission
        if (!Permission::hasPermission('registration.prices.view')) {
            // we redirect the current user to the registration edition page if he has the required permission
            if (Sentinel::getUser()->hasAccess('registration.page.view')) {
                return redirect()->route('registration.page.edit');
            } else {
                // or we redirect the current user to the dashboard
                return redirect()->route('dashboard.index');
            }
        }

        // we get the schedule
        try {
            $price = $this->repository->find($id);
        } catch (Exception $e) {
            // we notify the current user
            Modal::alert([
                trans('registration.message.price.find.failure', ['id' => $id]),
                trans('global.message.global.failure.contact.support', ['email' => config('settings.support_email')]),
            ], 'error');

            return redirect()->back();
        }

        // SEO Meta settings
        $this->seo_meta['page_title'] = trans('seo.back.schedules.edit');

        // we prepare the data for breadcrumbs
        $breadcrumbs_data = [
            'price' => $price,
        ];

        // prepare data for the view
        $data = [
            'seo_meta'          => $this->seo_meta,
            'price'             => $price,
            'breadcrumbs_data'  => $breadcrumbs_data,
        ];

        // return the view with data
        return view('pages.back.registration-price-edit')->with($data);
    }

    /**
     * @param $id
     * @param Request $request
     * @return mixed
     */
    public function update($id, Request $request)
    {
        // we get the schedule
        try {
            $price = $this->repository->find($id);
        } catch (Exception $e) {
            // we flash the request
            $request->flash();

            // we notify the current user
            Modal::alert([
                trans('registration.message.price.find.failure', ['id' => $id]),
                trans('global.message.global.failure.contact.support', ['email' => config('settings.support_email')]),
            ], 'error');

            return redirect()->back();
        }

        // we check the current user permission
        if (!Permission::hasPermission('registration.prices.update')) {
            // we redirect the current user to the registration edition page if he has the required permission
            if (Sentinel::getUser()->hasAccess('registration.prices.view')) {
                return redirect()->route('registration.prices.edit', ['id' => $price->id]);
            } else {
                // or we redirect the current user to the dashboard
                return redirect()->route('dashboard.index');
            }
        }

        // if the active field is not given, we set it to false
        $request->merge(['active' => $request->get('active', false)]);

        // we sanitize the entries
        $request->replace(InputSanitizer::sanitize($request->all()));

        // we check inputs validity
        $rules = [
            'label'  => 'required|string',
            'price'  => 'required|numeric',
            'active' => 'required|boolean',
        ];
        // we check the inputs validity
        if (!Validation::check($request->all(), $rules)) {
            // we flash the request
            $request->flash();

            return redirect()->back();
        }

        try {
            // we update the schedule
            $price->update($request->except('_token'));

            // we notify the current user
            Modal::alert([
                trans('registration.message.price.update.success', ['price' => $price->label]),
            ], 'success');

            return redirect()->back();
        } catch (Exception $e) {
            // we flash the request
            $request->flash();

            // we log the error
            CustomLog::error($e);

            // we notify the current user
            Modal::alert([
                trans('registration.message.price.update.failure', ['price' => $price->label]),
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
    public function destroy($id, Request $request)
    {
        // we check the current user permission
        if (!Permission::hasPermission('registration.prices.delete')) {
            // we redirect the current user to the registration edition page if he has the required permission
            if (Sentinel::getUser()->hasAccess('registration.page.view')) {
                return redirect()->route('registration.page.edit');
            } else {
                // or we redirect the current user to the home page
                return redirect()->route('dashboard.index');
            }
        }

        // we get the schedule
        try {
            $price = $this->repository->find($id);
        } catch (Exception $e) {
            // we flash the request
            $request->flash();

            // we notify the current user
            Modal::alert([
                trans('registration.message.price.find.failure', ['id' => $id]),
                trans('global.message.global.failure.contact.support', ['email' => config('settings.support_email')]),
            ], 'error');

            return redirect()->back();
        }

        try {
            // we delete the role
            $price->delete();

            Modal::alert([
                trans('registration.message.price.delete.success', ['price' => $price->label]),
            ], 'success');

            return redirect()->back();
        } catch (Exception $e) {
            // we log the error
            CustomLog::error($e);

            // we notify the current user
            Modal::alert([
                trans('registration.message.price.delete.failure', ['price' => $price->label]),
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
        // we get the schedule
        try {
            $price = $this->repository->find($id);
        } catch (Exception $e) {
            // we notify the current user
            return response([
                'message' => [
                    trans('registration.message.price.find.failure', ['id' => $id]),
                    trans('global.message.global.failure.contact.support', ['email' => config('settings.support_email'),]),
                ],
            ], 401);
        }

        // we check the current user permission
        if ($permission_denied = Permission::hasPermissionJson('registration.prices.update')) {
            return response([
                'active'  => $price->active,
                'message' => [$permission_denied],
            ], 401);
        }

        // if the active field is not given, we set it to false
        $request->merge(['active' => $request->get('active', false)]);

        // we sanitize the entries
        $request->replace(InputSanitizer::sanitize($request->all()));

        // we check the inputs validity
        $rules = [
            'active' => 'required|boolean',
        ];
        if (is_array($errors = Validation::check($request->all(), $rules, true))) {
            return response([
                'active'  => $price->active,
                'message' => $errors,
            ], 401);
        }

        try {
            $price->active = $request->get('active');
            $price->save();

            return response([
                'active'  => $price->active,
                'message' => [
                    trans('registration.message.price.activation.success.label', ['action' => trans_choice('registration.message.price.activation.success.action', $price->active), 'price' => $price->label]),
                ],
            ], 200);
        } catch (Exception $e) {
            // we log the error
            CustomLog::error($e);

            return response([
                trans('registration.message.price.activation.failure', ['price' => $price->label]),
            ], 401);
        }
    }
}
