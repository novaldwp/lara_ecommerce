<?php

if(!function_exists('convert_to_kilogram'))
{
    function convert_to_kilogram($gram)
    {
        $kilogram = (int) $gram / 1000;
        $string   = $kilogram . ' Kg';

        return $string;
    }
}
