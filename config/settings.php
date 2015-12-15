<?php

if (is_file(storage_path('app/config/settings.json'))) {
    $settings = json_decode(file_get_contents(storage_path('app/config/settings.json')));
}

return [
    'app_name_fr'      => isset($settings->app_name_fr) ? $settings->app_name_fr : null,
    'app_name_en'      => isset($settings->app_name_en) ? $settings->app_name_en : null,
    'app_slogan_fr'    => isset($settings->app_slogan_fr) ? $settings->app_slogan_fr : null,
    'app_slogan_en'    => isset($settings->app_slogan_en) ? $settings->app_slogan_en : null,
    'breadcrumbs'      => isset($settings->breadcrumbs) ? $settings->breadcrumbs : null,
    'multilingual'     => isset($settings->multilingual) ? $settings->multilingual : null,
    'phone_number'     => isset($settings->phone_number) ? $settings->phone_number : null,
    'contact_email'    => isset($settings->contact_email) ? $settings->contact_email : null,
    'support_email'    => isset($settings->support_email) ? $settings->support_email : null,
    'address'          => isset($settings->address) ? $settings->address : null,
    'zip_code'         => isset($settings->zip_code) ? $settings->zip_code : null,
    'city'             => isset($settings->city) ? $settings->city : null,
    'facebook'         => isset($settings->facebook) ? $settings->facebook : null,
    'twitter'          => isset($settings->twitter) ? $settings->twitter : null,
    'google_plus'      => isset($settings->google_plus) ? $settings->google_plus : null,
    'youtube'          => isset($settings->youtube) ? $settings->youtube : null,
    'linkedin'         => isset($settings->linkedin) ? $settings->linkedin : null,
    'pinterest'        => isset($settings->pinterest) ? $settings->pinterest : null,
    'rss'              => isset($settings->rss) ? $settings->rss : null,
    'loading_spinner'  => isset($settings->loading_spinner) ? $settings->loading_spinner : null,
    'logo_light'       => isset($settings->logo_light) ? $settings->logo_light : null,
    'logo_dark'        => isset($settings->logo_dark) ? $settings->logo_dark : null,
    'google_analytics' => isset($settings->google_analytics) ? $settings->google_analytics : null,
];