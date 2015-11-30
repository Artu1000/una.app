<?php

namespace App\Http\Controllers\Settings;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class SettingsController extends Controller
{

    /**
     * Create a new home controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * @return $this
     */
    public function index()
    {
        // we check the current user permission
        $required = 'settings.view';
        if (!\Sentinel::getUser()->hasAccess([$required])) {
            \Modal::alert([
                trans('permissions.message.access.denied') . " : <b>" . trans('permissions.' . $required) . "</b>",
            ], 'error');

            return Redirect()->back();
        }

        // SEO Meta settings
        $this->seoMeta['page_title'] = trans('seo.settings.index');

        // prepare data for the view
        $data = [
            'seoMeta' => $this->seoMeta,
        ];

        // return the view with data
        return view('pages.back.settings-edit')->with($data);
    }

    public function update(Request $request)
    {
        // we check the current user permission
        $required = 'settings.update';
        if (!\Sentinel::getUser()->hasAccess([$required])) {
            \Modal::alert([
                trans('permissions.message.access.denied') . " : <b>" . trans('permissions.' . $required) . "</b>",
            ], 'error');

            return Redirect()->back();
        }

        // we analyse the given inputs
        // we replace the "on" or "off" value from the checkbox by a boolean
        $request->merge([
            'multilingual' => filter_var($request->get('multilingual'), FILTER_VALIDATE_BOOLEAN),
            'rss'          => filter_var($request->get('rss'), FILTER_VALIDATE_BOOLEAN),
        ]);

        // we get the inputs
        $inputs = $request->except('_token', '_method');

        // we check the inputs
        $errors = [];
        $validator = \Validator::make($inputs, [
            'phone_number'  => 'phone:FR',
            'email_contact' => 'email',
            'email_support' => 'email',
            'zip_code'      => 'digits:5',
            'facebook'      => 'url',
            'twitter'       => 'url',
            'google_plus'   => 'url',
            'youtube'       => 'url',
            'rss'           => 'boolean',
            'favicon'       => 'mimes:ico|image_size:16,16',
        ]);
        foreach ($validator->errors()->all() as $error) {
            $errors[] = $error;
        }
        // if errors are found
        if (count($errors)) {
            // we flash the data
            $request->flash();

            // we notify the current user
            \Modal::alert($errors, 'error');

            return Redirect()->back();
        }

        try {
            // we format the number into its international equivalent
            if (isset($inputs['phone_number']) && !empty($inputs['phone_number'])) {
                $inputs['phone_number'] = $formatted_phone_number = phone_format(
                    $inputs['phone_number'],
                    'FR',
                    \libphonenumber\PhoneNumberFormat::INTERNATIONAL
                );
            }

            // we put the favicon at the root of the project
            if ($favicon = $request->file('favicon')) {
                $favicon->move('./', 'favicon.ico');
            };

            // we update the json file
            file_put_contents(storage_path('app/config/settings.json'), json_encode($inputs));
            \Modal::alert([
                trans('settings.message.update.success'),
            ], 'success');

            return Redirect()->back();
        } catch (\Exception $e) {
            // we flash the request
            $request->flash();

            // we log the error and we notify the current user
            \Log::error($e);
            \Modal::alert([
                trans('settings.message.update.failure'),
                trans('global.message.global.failure.contact.support', ['email' => config('settings.support_email')]),
            ], 'error');

            return Redirect()->back();
        }
    }

}
