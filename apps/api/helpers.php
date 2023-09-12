<?php

if (!function_exists('trimString')) {
    function trimString($string, $length)
    {
        return $string ? trim(mb_substr($string, 0, 20, 'UTF-8')) : null;
    }
}
