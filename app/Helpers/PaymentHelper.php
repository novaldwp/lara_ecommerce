<?php

if (!function_exists('getMethodPayment'))
{
    function getMethodPayment($method)
    {
        switch($method)
        {
            case "echannel" :
                $string = "Mandiri Virtual Akun";
                break;
            case "bank_transfer" :
                $string = "Virtual Akun";
                break;
            case "cstore" :
                $string = "Mandiri Bank Transfer";
                break;
            case "echannel" :
                $string = "Mandiri Bank Transfer";
                break;
        }
    }
}
