<?php


if (!function_exists('getOrderStatusMember'))
{
    function getOrderStatusMember($value)
    {
        switch($value)
        {
            case 0 :
                $status = '<span class="badge badge-danger">Cancel</span>';
                break;
            case 1 :
                $status = '<span class="badge badge-success">Completed</span>';
                break;
            case 2 :
                $status = '<span class="badge badge-primary">On Delivery</span>';
                break;
            case 3 :
                $status = '<span class="badge badge-info">On Progress</span>';
                break;
            case 4 :
                $status = '<span class="badge badge-light">Pending</span>';
                break;
            default :
                $status = '<span class="badge badge-secondary">Waiting for Payment</span>';
        }

        return $status;
    }
}
