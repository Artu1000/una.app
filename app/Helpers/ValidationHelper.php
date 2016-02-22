<?php

namespace App\Helpers;

use Modal;
use Validator;

class ValidationHelper
{
    /**
     * @param array $inputs
     * @param array $rules
     * @param bool $ajax
     * @return array|bool
     */
    public function check(array $inputs, array $rules, bool $ajax = false)
    {
        // we check the inputs
        $errors = [];
        $validator = Validator::make($inputs, $rules);
        foreach ($validator->errors()->all() as $error) {
            $errors[] = $error;
        }
        // if errors are found
        if (count($errors)) {

            // if this is an ajax request
            if (!$ajax) {
                // we notify the current user
                Modal::alert($errors, 'error');

                return false;
            } else {

                // we return the errors
                return $errors;
            }
        }

        return true;
    }
}