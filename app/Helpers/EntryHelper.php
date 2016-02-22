<?php

namespace App\Helpers;

class EntryHelper
{
    public function sanitizeAll(array $entries)
    {
        $return = [];
        // for each entry contained into the array
        foreach ($entries as $key => $entry) {
            // we sanitize it
            $return[$key] = $this->sanitize($entry);
        }

        // we return the sanitized data
        return $return;
    }

    public function sanitize($entry, $default = null)
    {
        // we prepare the return variable
        $return = null;

        // we sanitize the value
        switch (true) {
            case $entry === '':
            case $entry === 'null':
                $return = null;
                break;
            case $entry === 'false':
                $return = false;
                break;
            case $entry === 'true':
            case $entry === 'on':
                $return = true;
                break;
            case is_numeric($entry):
                if(((int)$entry != $entry)){
                    $return = doubleval($entry);
                } else {
                    $return = intval($entry);
                }
                break;
            default:
                $return = $entry;
                break;
        };

        // if the value is null or false, return the default value
        if (isset($default) && !$return) {
            return $default;
        }

        // we return the sanitized value
        return $return;
    }
}