<?php

function getDay($date)
{
    $day = date('l', strtotime($date));

    switch($day)
    {
        case 'Sunday' :
            $day = "Minggu";
            break;
        case 'Monday' :
            $day = "Senin";
            break;
        case 'Tuesday' :
            $day = "Selasa";
            break;
        case 'Wednesday' :
            $day = "Rabu";
            break;
        case 'Thursday' :
            $day = "Kamis";
            break;
        case 'Friday' :
            $day = "Jum'at";
            break;
        default :
            $day = "Sabtu";
            break;
    }

    return $day;
}

function getMonth($date)
{
    $month = date('F', strtotime($date));

    switch($month)
    {
        case 'January' :
            $month = "Januari";
            break;
        case 'February' :
            $month = "Februari";
            break;
        case 'March' :
            $month = "Maret";
            break;
        case 'April' :
            $month = "April";
            break;
        case 'Mei' :
            $month = "Mei";
            break;
        case 'June' :
            $month = "Juni";
            break;
        case 'July' :
            $month = "Juli";
            break;
        case 'August' :
            $month = "Agustus";
            break;
        case 'September' :
            $month = "September";
            break;
        case 'October' :
            $month = "Oktober";
            break;
        case 'November' :
            $month = "November";
            break;
        default :
            $month = "Desember";
            break;
    }

    return $month;
}

function getDateTimeIndo($dateTime)
{
    $day = getDay($dateTime);
    $month = getMonth($dateTime);
    $date = date('d', strtotime($dateTime));
    $year = date('Y', strtotime($dateTime));
    $his = date('H:i:s', strtotime($dateTime));
    $result = $day.', '.$date.' '.$month.' '.$year.' '.$his;

    return $result;
}
