<?php

if(is_file(storage_path('app/config/settings.json'))){
    $settings = json_decode(file_get_contents(storage_path('app/config/settings.json')));
}

return [
    'app_name' => isset($settings->app_name) ? $settings->app_name : null,
    'phone_number' => isset($settings->phone_number) ? $settings->phone_number : null,
    'contact_email' => isset($settings->contact_email) ? $settings->contact_email : null,
    'support_email' => isset($settings->support_email) ? $settings->support_email : null,
    'address' => isset($settings->address) ? $settings->address : null,
    'zip_code' => isset($settings->zip_code) ? $settings->zip_code : null,
    'city' => isset($settings->city) ? $settings->city : null,
    'facebook' => isset($settings->facebook) ? $settings->facebook : null,
    'twitter' => isset($settings->twitter) ? $settings->twitter : null,
    'google_plus' => isset($settings->google_plus) ? $settings->google_plus : null,
    'youtube' => isset($settings->youtube) ? $settings->youtube : null,
    'rss' => isset($settings->rss) ? $settings->rss : null,
    'loading_spinner' => isset($settings->loading_spinner) ? $settings->loading_spinner : null,
    'google_analytics' => isset($settings->google_analytics) ? $settings->google_analytics : null,
];