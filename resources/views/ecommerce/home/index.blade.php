@extends('layouts.front.app')

@section('content')
<!-- Main Slider Start -->
<div class="header">
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-3">
                <nav class="navbar bg-light overflow-auto">
                    <ul class="navbar-nav">
                        @forelse ($categories as $category)
                            <li class="dropdown">
                                <a href="#" class="nav-link has-dropdown" data-toggle="dropdown"><span>{{ $category->name }}</span></a>
                                <ul class="dropdown-menu category">
                                    @foreach ($category->child as $cat)
                                        <li class=""><a class="nav-link" href="{{ route('ecommerce.product.category', [$cat->parent->slug, $cat->slug]) }}">&raquo; {{ $cat->name }}</a></li>
                                    @endforeach
                                </ul>
                            </li>
                        @empty
                            <li class="nav-item">
                                <a class="nav-link" href="#"><i class="fas fa-exclamation-triangle"></i> No Categories Found</a>
                            </li>
                        @endforelse
                    </ul>
                </nav>
            </div>
            <div class="col-md-9 slider">
                <div class="header-slider normal-slider">
                    @foreach ($sliders as $slider)
                        <div class="header-slider-item">
                            <img src="{{ asset('uploads/images/sliders/' . $slider->image) }}" alt="{{ $slider->name }}" width="100%" height="400"/>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</div>
<!-- Main Slider End -->

<!-- Brand Slider Start -->
    @include('ecommerce._partial.brands-slider')
<!-- Brand Slider End -->

<!-- Featured Product Start -->
<div class="featured-product product">
    <div class="container-fluid">
        <div class="section-header">
            <h1>Produk Unggulan </h1>
        </div>
        <div class="row align-items-center product-slider product-slider-4">
            @forelse ($featuredProducts as $featuredProduct)
                <div class="col-lg-3">
                    <div class="product-item">
                        <div class="product-title">
                            <a href="{{ route('ecommerce.product.detail', [$featuredProduct->categories->parent->slug, $featuredProduct->categories->slug, $featuredProduct->slug]) }}">
                                {{ substr($featuredProduct->name, 0, 45) }} {{ strlen($featuredProduct->name) > 45 ? "..." : "" }}
                            </a>
                        </div>
                        <div class="product-image">
                            <a href="{{ route('ecommerce.product.detail', [$featuredProduct->categories->parent->slug, $featuredProduct->categories->slug, $featuredProduct->slug]) }}">
                                <img src="{{ asset($featuredProduct->productimages->thumb.$featuredProduct->productimages->image1) }}" alt="Product Image" width="300" height="300">
                            </a>
                            @role('customer')
                            <div class="product-action">
                                <a href="javascript:void(0);" id="addCartButton" data-product="{{ simple_encrypt($featuredProduct->id) }}"><i class="fa fa-cart-plus"></i></a>
                                <a href="javascript:void(0);" id="addWishlistButton" data-product="{{ simple_encrypt($featuredProduct->id) }}"><i class="fa fa-heart"></i></a>
                            </div>
                            @endrole
                        </div>
                        <div class="product-price text-center">
                            <h3><span></span>{{ convert_to_rupiah($featuredProduct->price) }}</h3>
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-lg-12 badge badge-danger center">
                    Belum ada Produk
                </div>
            @endforelse

        </div>
    </div>
</div>
<!-- Featured Product End -->

<!-- Recent Product Start -->
<div class="recent-product product">
    <div class="container-fluid">
        <div class="section-header">
            <h1>Produk Terbaru</h1>
        </div>
        <div class="row align-items-center product-slider product-slider-4">
            @forelse ($recentProducts as $recentProduct)
                <div class="col-lg-3">
                    <div class="product-item">
                        <div class="product-title">
                            <a href="{{ route('ecommerce.product.detail', [$recentProduct->categories->parent->slug, $recentProduct->categories->slug, $recentProduct->slug]) }}">
                                {{ substr($recentProduct->name, 0, 45) }} {{ strlen($recentProduct->name) > 45 ? "..." : "" }}
                            </a>
                        </div>
                        <div class="product-image">
                            <a href="{{ route('ecommerce.product.detail', [$recentProduct->categories->parent->slug, $recentProduct->categories->slug, $recentProduct->slug]) }}">
                                <img src="{{ asset($recentProduct->productimages->thumb.$recentProduct->productimages->image1) }}" loading="lazy" width="300" height="300" alt="Product Image">
                            </a>
                            @role('customer')
                            <div class="product-action">
                                <a href="javascript:void(0);" id="addCartButton" data-product="{{ simple_encrypt($recentProduct->id) }}"><i class="fa fa-cart-plus"></i></a>
                                <a href="javascript:void(0);" id="addWishlistButton" data-product="{{ simple_encrypt($recentProduct->id) }}"><i class="fa fa-heart"></i></a>
                            </div>
                            @endrole
                        </div>
                        <div class="product-price text-center">
                            <h3><span></span>{{ convert_to_rupiah($recentProduct->price) }}</h3>
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-lg-12 badge badge-danger center">
                    Belum ada Produk
                </div>
            @endforelse
        </div>
    </div>
</div>
<!-- Recent Product End -->
@endsection

@section('css')
<link rel="stylesheet" href="{{ asset('assets/front/css/custom.css') }}">
@endsection

@section('scripts')
<script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
<script src="{{ asset('js/front/cart.js') }}"></script>
@endsection
