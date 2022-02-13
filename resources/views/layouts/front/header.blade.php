<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <title>@yield('title', 'Situs Belanja Online Produk Elektronik Terlengkap & Termurah | Toko Putra Elektronik')</title>
        <meta content="width=device-width, initial-scale=1.0" name="viewport">
        <meta content="eCommerce HTML Template Free Download" name="keywords">
        <meta content="eCommerce HTML Template Free Download" name="description">
        <meta content="{{ csrf_token() }}" name="csrf-token">

        <!-- Favicon -->
        <link rel="shortcut icon" href="https://img.icons8.com/external-vitaliy-gorbachev-lineal-color-vitaly-gorbachev/60/000000/external-store-ecommerce-vitaliy-gorbachev-lineal-color-vitaly-gorbachev.png" type="image/x-icon">

        <!-- Google Fonts -->
        <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,400|Source+Code+Pro:700,900&display=swap" rel="stylesheet">

        <!-- CSS Libraries -->
        <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css" rel="stylesheet">
        <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.10.0/css/all.min.css" rel="stylesheet">
        <link href="{{ asset('assets/front/lib/slick/slick.css') }}" rel="stylesheet">
        <link href="{{ asset('assets/front/lib/slick/slick-theme.css') }}" rel="stylesheet">
        <link href="{{ asset('vendor/sweetalert2/dist/sweetalert2.min.css') }}" rel="stylesheet" type="text/css"/>

        <!-- Template Stylesheet -->
        <link href="{{ asset('assets/front/css/style.css') }}" rel="stylesheet">
        @yield('css')
    </head>
