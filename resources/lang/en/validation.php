<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Validation Language Lines
    |--------------------------------------------------------------------------
    |
    | The following language lines contain the default error messages used by
    | the validator class. Some of these rules have multiple versions such
    | as the size rules. Feel free to tweak each of these messages here.
    |
    */

    "accepted"             => "The <b>:attribute</b> must be accepted.",
    "active_url"           => "The <b>:attribute</b> is not a valid URL.",
    "after"                => "The <b>:attribute</b> must be a date after <b>:date</b>.",
    "alpha"                => "The <b>:attribute</b> may only contain letters.",
    "alpha_dash"           => "The <b>:attribute</b> may only contain letters, numbers, and dashes.",
    "alpha_num"            => "The <b>:attribute</b> may only contain letters and numbers.",
    "array"                => "The <b>:attribute</b> must be an array.",
    "before"               => "The <b>:attribute</b> must be a date before <b>:date</b>.",
    "between"              => [
        "numeric" => "The <b>:attribute</b> must be between <b>:min</b> and <b>:max</b>.",
        "file"    => "The <b>:attribute</b> must be between <b>:min</b> and <b>:max</b> kilobytes.",
        "string"  => "The <b>:attribute</b> must be between <b>:min</b> and <b>:max</b> characters.",
        "array"   => "The <b>:attribute</b> must have between <b>:min</b> and <b>:max</b> items.",
    ],
    "boolean"              => "The <b>:attribute</b> field must be true or false.",
    "confirmed"            => "The <b>:attribute</b> confirmation does not match.",
    "date"                 => "The <b>:attribute</b> is not a valid date.",
    "date_format"          => "The <b>:attribute</b> does not match the format :format.",
    "different"            => "The <b>:attribute</b> and <b>:other</b> must be different.",
    "digits"               => "The <b>:attribute</b> must be <b>:digits</b> digits.",
    "digits_between"       => "The <b>:attribute</b> must be between <b>:min</b> and <b>:max</b> digits.",
    "email"                => "The <b>:attribute</b> must be a valid email address.",
    "filled"               => "The <b>:attribute</b> field is required.",
    "exists"               => "The selected <b>:attribute</b> is invalid.",
    "image"                => "The <b>:attribute</b> must be an image.",
    "in"                   => "The selected <b>:attribute</b> is invalid.",
    "integer"              => "The <b>:attribute</b> must be an integer.",
    "ip"                   => "The <b>:attribute</b> must be a valid IP address.",
    "max"                  => [
        "numeric" => "The <b>:attribute</b> may not be greater than <b>:max</b>.",
        "file"    => "The <b>:attribute</b> may not be greater than <b>:max</b> kilobytes.",
        "string"  => "The <b>:attribute</b> may not be greater than <b>:max</b> characters.",
        "array"   => "The <b>:attribute</b> may not have more than <b>:max</b> items.",
    ],
    "mimes"                => "The <b>:attribute</b> must be a file of type: <b>:values</b>.",
    "min"                  => [
        "numeric" => "The <b>:attribute</b> must be at least <b>:min</b>.",
        "file"    => "The <b>:attribute</b> must be at least <b>:min</b> kilobytes.",
        "string"  => "The <b>:attribute</b> must be at least <b>:min</b> characters.",
        "array"   => "The <b>:attribute</b> must have at least <b>:min</b> items.",
    ],
    "not_in"               => "The selected <b>:attribute</b> is invalid.",
    "numeric"              => "The <b>:attribute</b> must be a number.",
    "phone"                => "The <b>:attribute</b> field is invalid.",
    "regex"                => "The <b>:attribute</b> format is invalid.",
    "required"             => "The <b>:attribute</b> field is required.",
    "required_if"          => "The <b>:attribute</b> field is required when <b>:other</b> is :value.",
    "required_with"        => "The <b>:attribute</b> field is required when <b>:values</b> is present.",
    "required_with_all"    => "The <b>:attribute</b> field is required when <b>:values</b> is present.",
    "required_without"     => "The <b>:attribute</b> field is required when <b>:values</b> is not present.",
    "required_without_all" => "The <b>:attribute</b> field is required when none of <b>:values</b> are present.",
    "same"                 => "The <b>:attribute</b> and <b>:other</b> must match.",
    "size"                 => [
        "numeric" => "The <b>:attribute</b> must be <b>:size</b>.",
        "file"    => "The <b>:attribute</b> must be <b>:size</b> kilobytes.",
        "string"  => "The <b>:attribute</b> must be <b>:size</b> characters.",
        "array"   => "The <b>:attribute</b> must contain <b>:size</b> items.",
    ],
    "timezone"             => "The <b>:attribute</b> must be a valid zone.",
    "string"               => "The <b>:attribute</b> must be a string.",
    "unique"               => "The <b>:attribute</b> has already been taken.",
    "url"                  => "The <b>:attribute</b> format is invalid.",
    "image_size"           => "The <b>:attribute</b> must be <b>:width</b> wide and <b>:height</b> tall.",
//    "between"            => "between <b>:size1</b> and <b>:size2</b> pixels",
    "lessthan"             => "less than <b>:size</b> pixels",
    "lessthanorequal"      => "less than or equal to <b>:size</b> pixels",
    "greaterthan"          => "greater than <b>:size</b> pixels",
    "greaterthanorequal"   => "greater than or equal to <b>:size</b> pixels",
    "equal"                => "<b>:size</b> pixels",
    "anysize"              => "any size",
    "image_aspect"         => "The <b>:attribute</b> aspect ratio must be <b>:aspect</b>.",

    /*
    |--------------------------------------------------------------------------
    | Custom Validation Language Lines
    |--------------------------------------------------------------------------
    |
    | Here you may specify custom validation messages for attributes using the
    | convention "attribute.rule" to name the lines. This makes it quick to
    | specify a specific custom language line for a given attribute rule.
    |
    */

    "custom" => [
        "attribute-name" => [
            "rule-name" => "custom-message",
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Custom Validation Attributes
    |--------------------------------------------------------------------------
    |
    | The following language lines are used to swap attribute place-holders
    | with something more reader friendly such as E-Mail Address instead
    | of "email". This simply helps us make messages a little cleaner.
    |
    */

    "attributes" => [
        "name"                  => "Name",
        "username"              => "Username",
        "email"                 => "E-mail",
        "remember"              => "Remember me",
        "first_name"            => "First name",
        "last_name"             => "Last name",
        "password"              => "Password",
        "password_confirmation" => "Password confirmation",
        "city"                  => "City",
        "country"               => "Country",
        "address"               => "Address",
        "gender"                => "Gender",
        "date"                  => "Date",
        "available"             => "Available",
        "birth_date"            => "Birth date",
        "phone_number"          => "Phone number",
        "zip_code"              => "Zip code",
        "role"                  => "Role",
        "favicon"               => "Favicon",
        "logo_light"            => "Logo light",
        "logo_dark"             => "Logo dark",
        "title"                 => "Title",
        "description"           => "Description",
        "video_link"            => "Video link",
        "parent_role_id"        => "Parent role",
    ],

];
