<?php

if (!function_exists('generateSlug')) {
    function generateSlug($string, $separator = '-')
    {
        $slug = strtolower($string);

        $slug = preg_replace('/[^a-z0-9]+/i', $separator, $slug);

        $slug = trim($slug, $separator);

        return $slug;
    }
}
