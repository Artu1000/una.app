<?php

namespace App\Helpers;

class StringHelper
{
    /**
     * Sanitizes a string for url, replacing whitespace and a few other characters with dashes.
     *
     * Limits the output to alphanumeric characters, underscore (_) and dash (-).
     * Whitespace becomes a dash.
     *
     * @param string $string The string to be sanitized.
     * @return string The sanitized string.
     */
    public function urlSanitize($string)
    {

        if (empty($string)) {
            throw new \InvalidArgumentException('No input string is given');
        }
        $string = strip_tags($string);
        // Preserve escaped octets.
        $string = preg_replace('|%([a-fA-F0-9][a-fA-F0-9])|', '---$1---', $string);
        // Remove percent signs that are not part of an octet.
        $string = str_replace('%', '', $string);
        // Restore octets.
        $string = preg_replace('|---([a-fA-F0-9][a-fA-F0-9])---|', '%$1', $string);
        if (function_exists('mb_strtolower')) {
            $string = mb_strtolower($string, 'UTF-8');
        } else {
            $string = strtolower($string);
        }
//        \Normalizer::normalize($string, \Normalizer::FORM_KD);
//        $string = preg_replace('/\p{Mn}/u', '', \Normalizer::normalize($string, \Normalizer::FORM_KD));
        $string = preg_replace('/[^%a-z0-9 _-]/', '', $string);
        $string = preg_replace('/\s+/', '-', $string);
        $string = preg_replace('|-+|', '-', $string);
        $string = trim($string, '-');
        return $string;
    }
}