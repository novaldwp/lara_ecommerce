@extends('layouts.front.app')

@if (count($products) > 0)
    @section('title')
        Jual Aneka {{ ucfirst($keyword) }} Resmi Lengkap & Termurah | Toko Putra Elektronik
    @endsection
@endif

@section('content')
<!-- Breadcrumb Start -->
<div class="breadcrumb-wrap">
    <div class="container-fluid">
        <ul class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('ecommerce.index') }}">Beranda</a></li>
            <li class="breadcrumb-item"><a href="#">Produk</a></li>
            <li class="breadcrumb-item active">Daftar Produk</li>
        </ul>
    </div>
</div>
<!-- Breadcrumb End -->

<!-- Product List Start -->
<div class="product-view">
    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-8">
                <div class="row">
                    @forelse ($products as $product)
                    <div class="col-md-4">
                        <div class="product-item">
                            <div class="product-title">
                                <a href="{{ route('ecommerce.product.detail', [$product->categories->parent->slug, $product->categories->slug, $product->slug]) }}">
                                    {{ substr($product->name, 0, 45) }} {{ strlen($product->name) > 45 ? "..." : "" }}
                                </a>
                            </div>
                            <div class="product-image">
                                <a href="product-detail.html">
                                    <img src="{{ asset($product->productimages->thumb.$product->productimages->image1) }}" alt="Product Image" width="300" height="300">
                                </a>
                                @role('customer')
                                    <div class="product-action">
                                        <a href="javascript:void(0);" id="addCartButton" data-product="{{ simple_encrypt($product->id) }}"><i class="fa fa-cart-plus"></i></a>
                                        <a href="javascript:void(0);" id="addWishlistButton" data-product="{{ simple_encrypt($product->id) }}"><i class="fa fa-heart"></i></a>
                                    </div>
                                @endrole
                            </div>
                            <div class="product-price text-center">
                                <h3>{{ convert_to_rupiah($product->price) }}</h3>
                            </div>
                        </div>
                    </div>
                    @empty
                    <div class="col-md-12">
                        <div class="product-item">
                            <div class="product-title">
                                <a href="#">No Product Available</a>
                            </div>
                        </div>
                    </div>
                    @endforelse

                </div>

                <!-- Pagination Start -->
                <div class="col-md-12">
                    <nav aria-label="Page navigation example">
                        <ul class="pagination justify-content-center">
                            {!! $products->links() !!}
                        </ul>
                    </nav>
                </div>
                <!-- Pagination Start -->
            </div>

            <!-- Side Bar Start -->
                @include('ecommerce._partial.sidebar')
            <!-- Side Bar End -->
        </div>
    </div>
</div>
<!-- Product List End -->

<!-- Brand Start -->
    @include('ecommerce._partial.brands-slider')
<!-- Brand End -->

@endsection

@section('css')
<link rel="stylesheet" href="{{ asset('assets/front/css/custom.css') }}">
@endsection

@section('scripts')
<script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
<script src="{{ asset('js/front/cart.js') }}"></script>
@endsection
