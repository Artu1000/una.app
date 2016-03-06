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
        if(!$this->requirePermission('settings.view')){
            return redirect()->back();
        }

        // SEO Meta settings
        $this->seoMeta['page_title'] = trans('seo.back.settings.index');

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
        if(!$this->requirePermission('settings.update')){
            return redirect()->back();
        }

        // we analyse the given inputs
        // we replace the "on" or "off" value from the checkbox by a boolean
        $request->merge([
            'breadcrumbs'  => filter_var($request->get('breadcrumbs'), FILTER_VALIDATE_BOOLEAN),
            'multilingual' => filter_var($request->get('multilingual'), FILTER_VALIDATE_BOOLEAN),
            'rss'          => filter_var($request->get('rss'), FILTER_VALIDATE_BOOLEAN),
        ]);

        // we get the inputs
        $inputs = $request->except('_token', '_method');

        // we set the rules according to the multilingual config
        $rules = [
            'app_name_fr'      => 'required|string',
            'app_slogan_fr'    => 'string',
            'breadcrumbs'      => 'boolean',
            'multilingual'     => 'boolean',
            'phone_number'     => 'phone:FR',
            'contact_email'    => 'required|email',
            'support_email'    => 'required|email',
            'address'          => 'string',
            'zip_code'         => 'digits:5',
            'city'             => 'string',
            'facebook'         => 'url',
            'twitter'          => 'url',
            'google_plus'      => 'url',
            'youtube'          => 'url',
            'linkedin'         => 'url',
            'pinterest'        => 'url',
            'rss'              => 'boolean',
            'favicon'          => 'mimes:ico|image_size:16,16',
            'logo_light'       => 'image|mimes:png|image_size:>=300,*',
            'logo_dark'        => 'image|mimes:png|image_size:>=300,*',
            'loading_spinner'  => 'string',
            'success_icon'     => 'string',
            'error_icon'       => 'string',
            'info_icon'        => 'string',
            'google_analytics' => 'string',
        ];
        if (config('settings.multilingual')) {
            $rules['app_name_en'] = 'required|string';
            $rules['app_slogan_en'] = 'string';
        }

        // we check inputs validity
        if(!$this->checkInputsValidity($inputs, $rules, $request)){
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
