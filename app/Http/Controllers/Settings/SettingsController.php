<?php

namespace App\Http\Controllers\Settings;

use App\Http\Controllers\Controller;
use Artisan;
use CustomLog;
use Exception;
use FileManager;
use Illuminate\Http\Request;
use ImageManager;
use InputSanitizer;
use libphonenumber\PhoneNumberFormat;
use Modal;
use Permission;
use Sentinel;
use Validation;

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
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index()
    {
        // we check the current user permission
        if (!Permission::hasPermission('settings.view')) {
            return redirect()->route('dashboard.index');
        }
        
        // SEO Meta settings
        $this->seo_meta['page_title'] = trans('seo.back.settings.index');
        
        // prepare data for the view
        $data = [
            'seo_meta' => $this->seo_meta,
        ];
        
        // return the view with data
        return view('pages.back.settings-edit')->with($data);
    }
    
    public function update(Request $request)
    {
        // we check the current user permission
        if (!Permission::hasPermission('settings.update')) {
            // we redirect the current user to the settings view if he has the required permission
            if (Sentinel::getUser()->hasAccess('settings.view')) {
                return redirect()->route('settings.view');
            } else {
                // or we redirect the current user to the home page
                return redirect()->route('dashboard.index');
            }
        }
    
        // we clear the cache
        Artisan::call('config:clear');
        
        // we sanitize the entries
        $request->replace(InputSanitizer::sanitize($request->except('phone_number')));
        
        // if the boolean field is not given, we set it to false
        $request->merge(['breadcrumb' => $request->get('breadcrumb', false)]);
        $request->merge(['multilingual' => $request->get('multilingual', false)]);
        $request->merge(['rss' => $request->get('rss', false)]);
        $request->merge(['image_optimization' => $request->get('image_optimization', false)]);
        
        // we get the inputs
        $inputs = $request->except('_token', '_method');
    
        // we get the phone accepted formats
        $accepted_phone_formats = [];
        foreach (config('laravellocalization.supportedLocales') as $locale) {
            $accepted_phone_formats[] = array_last(explode('_', $locale['regional']));
        }
        
        // we set the rules according to the multilingual config
        $rules = [
            'app_name_fr'                       => 'required|string',
            'app_slogan_fr'                     => 'string',
            'breadcrumbs'                       => 'required|boolean',
            'multilingual'                      => 'required|boolean',
            'phone_number'                      => 'phone:' . implode(',', $accepted_phone_formats),
            'contact_email'                     => 'required|email',
            'support_email'                     => 'required|email',
            'address'                           => 'string',
            'zip_code'                          => 'digits:5',
            'city'                              => 'string',
            'facebook'                          => 'url',
            'twitter'                           => 'url',
            'google_plus'                       => 'url',
            'youtube'                           => 'url',
            'linkedin'                          => 'url',
            'pinterest'                         => 'url',
            'rss'                               => 'required|boolean',
            'favicon'                           => 'mimes:ico|image_size:16,16',
            'logo_light'                        => 'image|mimes:png|image_size:>=300,*',
            'logo_dark'                         => 'image|mimes:png|image_size:>=300,*',
            'loading_spinner'                   => 'string',
            'success_icon'                      => 'string',
            'error_icon'                        => 'string',
            'info_icon'                         => 'string',
            'image_optimization'                => 'required|boolean',
            'google_analytics'                  => 'string',
            'google_analytics_view_id'          => 'numeric',
            'google_analytics_credentials_json' => 'mimetypes:text/plain,application/json',
        ];
        if (config('settings.multilingual')) {
            $rules['app_name_en'] = 'required|string';
            $rules['app_slogan_en'] = 'string';
        }
        
        // we check the inputs validity
        if (!Validation::check($inputs, $rules)) {
            // we flash the request
            $request->flash();
            
            return redirect()->back();
        }
        
        // we save the settings
        try {
            // we format the number into its international equivalent
            if (isset($inputs['phone_number']) && !empty($inputs['phone_number'])) {
                $inputs['phone_number'] = $formatted_phone_number = phone_format(
                    $inputs['phone_number'],
                    'FR',
                    PhoneNumberFormat::INTERNATIONAL
                );
            }
            
            // we put the favicon at the root of the project
            if ($favicon = $request->file('favicon')) {
                $favicon->move('./', 'favicon.ico');
            };
            
            // logo light treatment
            if ($logo_light = $request->file('logo_light')) {
                // if a previous version of the image is found, we delete it
                if ($current_logo_light = config('settings.logo_light')) {
                    ImageManager::remove(
                        $current_logo_light,
                        config('image.settings.storage_path'),
                        config('image.settings.logo.sizes')
                    );
                }
                // we optimize, resize and save the image
                $file_name = ImageManager::storeResizeAndRename(
                    $logo_light->getRealPath(),
                    config('image.settings.logo.name.light'),
                    $logo_light->getClientOriginalExtension(),
                    config('image.settings.storage_path'),
                    config('image.settings.logo.sizes')
                );
                // we add the image name to the inputs for saving
                $inputs['logo_light'] = $file_name;
            } elseif (config('settings.logo_light')) {
                $inputs['logo_light'] = config('settings.logo_light');
            }
            
            // logo dark treatment
            if ($logo_dark = $request->file('logo_dark')) {
                // if a previous version of the image is found, we delete it
                if ($current_logo_dark = config('settings.logo_dark')) {
                    ImageManager::remove(
                        $current_logo_dark,
                        config('image.settings.storage_path'),
                        config('image.settings.logo.sizes')
                    );
                }
                // we optimize, resize and save the image
                $file_name = ImageManager::storeResizeAndRename(
                    $logo_dark->getRealPath(),
                    config('image.settings.logo.name.dark'),
                    $logo_dark->getClientOriginalExtension(),
                    config('image.settings.storage_path'),
                    config('image.settings.logo.sizes')
                );
                // we add the image name to the inputs for saving
                $inputs['logo_dark'] = $file_name;
            } elseif (config('settings.logo_dark')) {
                $inputs['logo_dark'] = config('settings.logo_dark');
            }
    
            // google analytics credential json treatment
            if ($google_analytics_credentials_json = $request->file('google_analytics_credentials_json')) {
        
                // if a previous version of the file is found, we delete it
                if ($current_google_analytics_credentials_json = config('settings.google_analytics_credentials_json')) {
                    FileManager::remove(
                        $current_google_analytics_credentials_json,
                        config('file.google_analytics.storage_path')
                    );
                }
        
                // we save the file
                $file_name = FileManager::storeAndRename(
                    $google_analytics_credentials_json->getRealPath(),
                    config('file.google_analytics.credentials_json.name'),
                    $google_analytics_credentials_json->getClientOriginalExtension(),
                    config('file.google_analytics.storage_path')
                );
                // we set the analytics credentials json file
                $inputs['google_analytics_credentials_json'] = $file_name;
            }
            
            // we update the json file
            file_put_contents(storage_path('app/settings/settings.json'), json_encode($inputs));
            
            // we notify the current user
            Modal::alert([
                trans('settings.message.update.success'),
            ], 'success');
    
            // we renew the config cache (if the app is not in a local mode)
            if (app()->environment() !== 'local') {
                Artisan::call('config:cache');
            }
            
            return redirect()->back();
        } catch (Exception $e) {
            
            // we flash the request
            $request->flashExcept(
                'favicon',
                'logo_light',
                'logo_dark',
                'google_analytics_credentials_json'
            );
            
            // we log the error
            CustomLog::error($e);
            
            // we notify the current user
            Modal::alert([
                trans('settings.message.update.failure'),
                trans('global.message.global.failure.contact.support', ['email' => config('settings.support_email')]),
            ], 'error');
            
            // we renew the config cache
            Artisan::call('config:cache');
            
            return redirect()->back();
        }
    }
    
    /**
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse|\Symfony\Component\HttpFoundation\BinaryFileResponse
     */
    public function downloadGoogleAnalyticsCredentialsJsonFile(Request $request)
    {
        // we check the current user permission
        if (!Permission::hasPermission('settings.update')) {
            // we redirect the current user to the settings view if he has the required permission
            if (Sentinel::getUser()->hasAccess('settings.view')) {
                return redirect()->route('settings.view');
            } else {
                // or we redirect the current user to the home page
                return redirect()->route('dashboard.index');
            }
        }
    
        // we sanitize the entries
        $request->replace(InputSanitizer::sanitize($request->all()));
        
        try {
            return response()->download(
                config('laravel-analytics.service_account_credentials_json')
            );
        } catch (Exception $e) {
            // we log the error
            CustomLog::error($e);
            
            // we notify the current user
            Modal::alert([
                trans('settings.message.service_account_credentials_json.downlad.failure'),
                trans('global.message.global.failure.contact.support', ['email' => config('settings.support_email')]),
            ], 'error');
            
            return redirect()->back();
        }
    }
    
}
