<?php

if (!function_exists('dummyRequest'))
{
    function dummyRequest()
    {
        $request = (object) ['filter' => ""];

        return $request;
    }
}
