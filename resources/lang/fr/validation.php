<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Validation Language Lines
    |--------------------------------------------------------------------------
    |
    | The following language lines contain the default error messages used by
    | the validator class. Some of these rules have multiple versions such
    | such as the size rules. Feel free to tweak each of these messages.
    |
    */

    "accepted"             => "Le champ <b>:attribute</b> doit être accepté.",
    "active_url"           => "Le champ <b>:attribute</b> n'est pas une URL valide.",
    "after"                => "Le champ <b>:attribute</b> doit être une date postérieure au <b>:date</b>.",
    "alpha"                => "Le champ <b>:attribute</b> doit seulement contenir des lettres.",
    "alpha_dash"           => "Le champ <b>:attribute</b> doit seulement contenir des lettres, des chiffres et des tirets.",
    "alpha_num"            => "Le champ <b>:attribute</b> doit seulement contenir des chiffres et des lettres.",
    "array"                => "Le champ <b>:attribute</b> doit être un tableau.",
    "before"               => "Le champ <b>:attribute</b> doit être une date antérieure au <b>:date</b>.",
    "between"              => [
        "numeric" => "La valeur de <b>:attribute</b> doit être comprise entre <b>:min</b> et <b>:max</b>.",
        "file"    => "Le fichier <b>:attribute</b> doit avoir une taille entre <b>:min</b> et <b>:max</b> kilo-octets.",
        "string"  => "Le texte <b>:attribute</b> doit avoir entre <b>:min</b> et <b>:max</b> caractères.",
        "array"   => "Le tableau <b>:attribute</b> doit avoir entre <b>:min</b> et <b>:max</b> éléments.",
    ],
    "boolean"              => "Le champ <b>:attribute</b> doit être vrai ou faux.",
    "confirmed"            => "La confirmation du champ <b>:attribute</b> ne correspond pas.",
    "date"                 => "Le champ <b>:attribute</b> n'est pas une date valide.",
    "date_format"          => "Le champ <b>:attribute</b> ne correspond pas au format :format.",
    "different"            => "Les champs <b>:attribute</b> et <b>:other</b> doivent être différents.",
    "digits"               => "Le champ <b>:attribute</b> doit avoir <b>:digits</b> chiffres.",
    "digits_between"       => "Le champ <b>:attribute</b> doit avoir entre <b>:min</b> and <b>:max</b> chiffres.",
    "email"                => "Le champ <b>:attribute</b> doit être une adresse email valide.",
    "exists"               => "Le champ <b>:attribute</b> n'existe pas.",
    "filled"               => "Le champ <b>:attribute</b> est obligatoire.",
    "image"                => "Le champ <b>:attribute</b> doit être une image.",
    "in"                   => "Le champ <b>:attribute</b> est invalide.",
    "integer"              => "Le champ <b>:attribute</b> doit être un entier.",
    "ip"                   => "Le champ <b>:attribute</b> doit être une adresse IP valide.",
    "json"                 => "Le champ <b>:attribute</b> doit être un document JSON valide.",
    "max"                  => [
        "numeric" => "La valeur de <b>:attribute</b> ne peut être supérieure à <b>:max</b>.",
        "file"    => "Le fichier <b>:attribute</b> ne peut être plus gros que <b>:max</b> kilo-octets.",
        "string"  => "Le texte de <b>:attribute</b> ne peut contenir plus de <b>:max</b> caractères.",
        "array"   => "Le tableau <b>:attribute</b> ne peut avoir plus de <b>:max</b> éléments.",
    ],
    "mimes"                => "Le champ <b>:attribute</b> doit être un fichier de type : <b>:values</b>.",
    "min"                  => [
        "numeric" => "La valeur de <b>:attribute</b> doit être supérieure à <b>:min</b>.",
        "file"    => "Le fichier <b>:attribute</b> doit être plus gros que <b>:min</b> kilo-octets.",
        "string"  => "Le texte <b>:attribute</b> doit contenir au moins <b>:min</b> caractères.",
        "array"   => "Le tableau <b>:attribute</b> doit avoir au moins <b>:min</b> éléments.",
    ],
    "not_in"               => "Le champ <b>:attribute</b> sélectionné n'est pas valide.",
    "numeric"              => "Le champ <b>:attribute</b> doit contenir un nombre.",
    "phone"                => "Le champ <b>:attribute</b> n'est pas valide.",
    "regex"                => "Le format du champ <b>:attribute</b> est invalide.",
    "required"             => "Le champ <b>:attribute</b> est obligatoire.",
    "required_if"          => "Le champ <b>:attribute</b> est obligatoire quand la valeur de <b>:other</b> est :value.",
    "required_with"        => "Le champ <b>:attribute</b> est obligatoire quand <b>:values</b> est présent.",
    "required_with_all"    => "Le champ <b>:attribute</b> est obligatoire quand <b>:values</b> est présent.",
    "required_without"     => "Le champ <b>:attribute</b> est obligatoire quand <b>:values</b> n'est pas présent.",
    "required_without_all" => "Le champ <b>:attribute</b> est requis quand aucun de <b>:values</b> n'est présent.",
    "same"                 => "Les champs <b>:attribute</b> et <b>:other</b> doivent être identiques.",
    "size"                 => [
        "numeric" => "La valeur de <b>:attribute</b> doit être <b>:size</b>.",
        "file"    => "La taille du fichier de <b>:attribute</b> doit être de <b>:size</b> kilo-octets.",
        "string"  => "Le texte de <b>:attribute</b> doit contenir <b>:size</b> caractères.",
        "array"   => "Le tableau <b>:attribute</b> doit contenir <b>:size</b> éléments.",
    ],
    "string"               => "Le champ <b>:attribute</b> doit être une chaîne de caractères.",
    "timezone"             => "Le champ <b>:attribute</b> doit être un fuseau horaire valide.",
    "unique"               => "La valeur du champ <b>:attribute</b> est déjà utilisée.",
    "url"                  => "Le format de l'URL de <b>:attribute</b> n'est pas valide.",

    "image_size"         => "<b>:attribute</b> doit être de <b>:width</b> de large et <b>:height</b> de haut",
//    "between"            => "entre <b>:size1</b> et <b>:size2</b> pixels",
    "lessthan"           => "moins de <b>:size</b> pixels",
    "lessthanorequal"    => "plus petit ou égal à <b>:size</b> pixels",
    "greaterthan"        => "plus grand que <b>:size</b> pixels",
    "greaterthanorequal" => "plus grand ou égal à <b>:size</b> pixels",
    "equal"              => "<b>:size</b> pixels",
    "anysize"            => "n'importe quelle taille",
    "image_aspect"       => "Le ratio de </b>:attribute</b> doit être de :aspect",

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
        "name"                  => "Nom",
        "username"              => "Pseudo",
        "email"                 => "E-mail",
        "remember"              => "Se souvenir de moi",
        "first_name"            => "Prénom",
        "last_name"             => "Nom",
        "password"              => "Mot de passe",
        "password_confirmation" => "Confirmation du mot de passe",
        "city"                  => "Ville",
        "country"               => "Pays",
        "address"               => "Adresse",
        "gender"                => "Genre",
        "date"                  => "Date",
        "available"             => "Disponible",
        "birth_date"            => "Date de naissance",
        "phone_number"          => "Numéro de téléphone",
        "zip_code"              => "Code postal",
        "role"                  => "Rôle",
        "favicon"               => "Favicon",
        "logo_light"            => "Logo clair",
        "logo_dark"             => "Logo foncé",
    ],

];