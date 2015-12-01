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

            return redirect()->back();
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

            return redirect()->back();
        }

        // we analyse the given inputs
        // we replace the "on" or "off" value from the checkbox by a boolean
        $request->merge([
            'multilingual' => filter_var($request->get('multilingual'), FILTER_VALIDATE_BOOLEAN),
            'rss'          => filter_var($request->get('rss'), FILTER_VALIDATE_BOOLEAN),
        ]);

        // we get the inputs
        $inputs = $request->except('_token', '_method');

        // we don't update not filled inputs
        $inputs = array_filter($inputs, function ($input) {
            return strlen($input);
        });

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
            'logo_light'    => 'image|mimes:png|image_size:>=300,*',
            'logo_dark'     => 'image|mimes:png|image_size:>=300,*',
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

            return redirect()->back();
        }

        // we save the settings
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

            // logo light treatment
            if ($logo_dark = $request->file('logo_light')) {
                // we optimize, resize and save the image
                $file_name = \ImageManager::optimizeAndResize(
                    $logo_dark->getRealPath(),
                    'logo_light',
                    $logo_dark->getClientOriginalExtension(),
                    storage_path('app/config'),
                    ['admin' => [40, 40], 'header' => [70, null], 'large' => [300, null]]
                );
                // we add the image name to the inputs for saving
                $inputs['logo_light'] = $file_name;
            } elseif (config('settings.logo_light')) {
                $inputs['logo_light'] = config('settings.logo_light');
            }

            // logo dark treatment
            if ($logo_dark = $request->file('logo_dark')) {
                // we optimize, resize and save the image
                $file_name = \ImageManager::optimizeAndResize(
                    $logo_dark->getRealPath(),
                    'logo_dark',
                    $logo_dark->getClientOriginalExtension(),
                    storage_path('app/config'),
                    ['admin' => [40, 40], 'header' => [70, null], 'large' => [300, null]]
                );
                // we add the image name to the inputs for saving
                $inputs['logo_dark'] = $file_name;
            } elseif (config('settings.logo_dark')) {
                $inputs['logo_dark'] = config('settings.logo_dark');
            }

            // we update the json file
            file_put_contents(storage_path('app/config/settings.json'), json_encode($inputs));

            // we notify the current user
            \Modal::alert([
                trans('settings.message.update.success'),
            ], 'success');

            return redirect()->back();
        } catch (\Exception $e) {
            // we flash the request
            $request->flash();

            // we log the error and we notify the current user
            \Log::error($e);
            \Modal::alert([
                trans('settings.message.update.failure'),
                trans('global.message.global.failure.contact.support', ['email' => config('settings.support_email')]),
            ], 'error');

            return redirect()->back();
        }
    }

}
