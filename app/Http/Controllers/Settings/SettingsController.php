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
        // SEO Meta settings
        $this->seoMeta['page_title'] = 'Configuration du site';

        // prepare data for the view
        $data = [
            'seoMeta' => $this->seoMeta,
        ];

        // return the view with data
        return view('pages.back.settings')->with($data);
    }

    public function store(Request $request)
    {
        // we flash the non sensitive data
        $request->flashOnly(
            'app_name',
            'phone_number',
            'email',
            'address',
            'zip_code',
            'city',
            'facebook',
            'twitter',
            'google_plus',
            'youtube',
            'rss',
            'loading_spinner',
            'google_analytics'
        );

        // we analyse the given inputs
        // we replace the "on" or "off" value from the checkbox by a boolean
        $request->merge([
            'rss' => filter_var($request->get('rss'), FILTER_VALIDATE_BOOLEAN)
        ]);

        // we get the inputs
        $inputs = $request->except('_token');

        // we check the inputs
        $errors = [];
        $validator = \Validator::make($inputs, [
            'phone_number' => 'phone:FR',
            'email_contact' => 'email',
            'email_support' => 'email',
            'zip_code' => 'digits:5',
            'facebook' => 'url',
            'twitter' => 'url',
            'google_plus' => 'url',
            'youtube' => 'url',
            'rss' => 'boolean'
        ]);
        foreach ($validator->errors()->all() as $error) {
            $errors[] = $error;
        }
        // if errors are found
        if (count($errors)) {
            \Modal::alert($errors, 'error');
            return Redirect()->back();
        }

        // we format the number into its international equivalent
        $inputs['phone_number'] = $formatted_phone_number = phone_format(
            $inputs['phone_number'],
            'FR',
            \libphonenumber\PhoneNumberFormat::INTERNATIONAL
        );

        // we store the data into json file
        if(file_put_contents(storage_path('config/settings.json'), json_encode($inputs))){
            \Modal::alert([
                "La configuration a bien été mise à jour."
            ], 'success');
        } else {
            \Modal::alert([
                "Une erreur est survenue pendant l'enregistrement de la configuration de l'application."
            ], 'error');
        };
        return Redirect()->back();
    }

}
