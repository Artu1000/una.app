<?php

namespace App\Http\Controllers\Registration;

use App\Http\Controllers\Controller;
use App\Repositories\RegistrationPrice\RegistrationPriceRepositoryInterface;
use CustomLog;
use Entry;
use Exception;
use Illuminate\Http\Request;
use Permission;
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
        $request->replace(Entry::sanitizeAll($request->all()));

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
