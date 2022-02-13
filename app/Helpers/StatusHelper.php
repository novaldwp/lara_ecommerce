<?php

if (!function_exists('getStatus'))
{
    function getStatus($value)
    {
        $string = "";

        switch($value)
        {
            case 0 :
                $string = "Non-Aktif";
                break;
            case 1 :
                $string = "Aktif";
                break;
            case 2 :
                $string = "Semua";
                break;
            case 3 :
                $string = "Unggulan";
                break;
            case 4 :
                $string = "Non-Unggulan";
                break;
            case 5 :
                $string = "Positif";
                break;
            case 6 :
                $string = "Negatif";
                break;
            default :
                $string = "Error value";
                break;
        }

        return $string;
    }
}

if (!function_exists('getOrderStatusMember'))
{
    function getOrderStatusMember($value)
    {
        switch($value)
        {
            case 0 :
                $status = '<span class="badge badge-danger">Dibatalkan</span>';
                break;
            case 1 :
                $status = '<span class="badge badge-success">Selesai</span>';
                break;
            case 2 :
                $status = '<span class="badge badge-primary">Dikirim</span>';
                break;
            case 3 :
                $status = '<span class="badge badge-info">Diterima</span>';
                break;
            case 4 :
                $status = '<span class="badge badge-light">Pending</span>';
                break;
            default :
                $status = '<span class="badge badge-secondary">Menunggu Pembayaran</span>';
        }

        return $status;
    }
}

if (!function_exists('getOrderStatusMember'))
{
    function getStatusTextByNumber($value)
    {
        switch($value)
        {
            case "cancel" :
                $status = 0;
                break;
            case "completed" :
                $status = 1;
                break;
            case "delivery" :
                $status = 2;
                break;
            case "receive" :
                $status = 3;
                break;
            case "pending" :
                $status = 4;
                break;
            default :
                $status = 0;
                break;
        }

        return $status;
    }
}

if (!function_exists('getProductStatus'))
{
    function getProductStatus($value)
    {
        switch($value)
        {
            case 1 :
                $string = "Aktif";
                break;
            case 0 :
                $string = "Non-Aktif";
                break;
        }

        return $string;
    }
}

if (!function_exists('getProductStatusUnggulan'))
{
    function getProductStatusUnggulan($value)
    {
        switch($value)
        {
            case 1 :
                $string = "Unggulan";
                break;
            case 0 :
                $string = "Non-Unggulan";
                break;
        }

        return $string;
    }
}
