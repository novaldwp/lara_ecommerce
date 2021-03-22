@extends('layouts.front.app')

@section('content')
<!-- Breadcrumb Start -->
<div class="breadcrumb-wrap">
    <div class="container-fluid">
        <ul class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('ecommerce.index') }}">Home</a></li>
            <li class="breadcrumb-item"><a href="#">Brands</a></li>
            <li class="breadcrumb-item active">{{ $currentBrand->name }}</li>
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
                    @forelse ($brandProducts as $product)
                    <div class="col-md-4">
                        <div class="product-item">
                            <div class="product-title">
                                <a href="{{ route('ecommerce.product.detail', [$product->categories->parent->slug, $product->categories->slug, $product->slug]) }}">
                                    {{ substr($product->name, 0, 50) }} {{ strlen($product->name) > 50 ? "..." : "" }}
                                </a>
                                <div class="ratting">
                                    <i class="fa fa-star"></i>
                                    <i class="fa fa-star"></i>
                                    <i class="fa fa-star"></i>
                                    <i class="fa fa-star"></i>
                                    <i class="fa fa-star"></i>
                                </div>
                            </div>
                            <div class="product-image">
                                <a href="product-detail.html">
                                    <img src="{{ asset($product->productimages->thumb.$product->productimages->image1) }}" alt="Product Image" width="300" height="300">
                                </a>
                                <div class="product-action">
                                    <a href="#"><i class="fa fa-cart-plus"></i></a>
                                    <a href="#"><i class="fa fa-heart"></i></a>
                                </div>
                            </div>
                            <div class="product-price text-center">
                                <h3><span>Rp. </span>{{ number_format($product->price, 0) }}</h3>
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
                            {{-- {!! $brandProducts->links() !!} --}}
                        </ul>
                    </nav>
                </div>
                <!-- Pagination Start -->
            </div>

            <!-- Side Bar Start -->
            <div class="col-lg-4 sidebar">
                <div class="sidebar-widget category">
                    <h2 class="title">Category</h2>
                    <nav class="navbar bg-light">
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

                <div class="sidebar-widget widget-slider">
                    <div class="sidebar-slider normal-slider">
                        @forelse ($randomProduct as $random)
                            <div class="product-item">
                                <div class="product-title">
                                    <a href="{{ route('ecommerce.product.detail', [$random->categories->parent->slug, $random->categories->slug, $random->slug]) }}">
                                        {{substr($random->name, 0, 50) }} {{ strlen($random->name) > 50 ? "..." : ""}}
                                    </a>
                                    <div class="ratting">
                                        <i class="fa fa-star"></i>
                                        <i class="fa fa-star"></i>
                                        <i class="fa fa-star"></i>
                                        <i class="fa fa-star"></i>
                                        <i class="fa fa-star"></i>
                                    </div>
                                </div>
                                <div class="product-image">
                                    <a href="product-detail.html">
                                        <img src="{{ asset($random->productimages->thumb.$random->productimages->image1) }}" alt="Product Image" width="300" height="300">
                                    </a>
                                    <div class="product-action">
                                        <a href="#"><i class="fa fa-cart-plus"></i></a>
                                        <a href="#"><i class="fa fa-heart"></i></a>
                                    </div>
                                </div>
                                <div class="product-price text-center">
                                    <h3><span>Rp. </span>{{ number_format($random->price, 0) }}</h3>
                                </div>
                            </div>
                        @empty
                            <div class="product-item"><span>No Random Product Available</span></div>
                        @endforelse
                    </div>
                </div>

                <div class="sidebar-widget brands overflow-auto">
                    <h2 class="title">Our Brands</h2>
                    <ul>
                        @forelse ($brands as $brand)
                            <li><a href="{{ route('ecommerce.product.brand', $brand->slug) }}">{{ $brand->name }}</a><span>({{ $brand->products_count }})</span></li>
                        @empty
                            <span>No Brands Available</span>
                        @endforelse
                    </ul>
                </div>
            </div>
            <!-- Side Bar End -->
        </div>
    </div>
</div>
<!-- Product List End -->

<!-- Brand Start -->
<div class="brand">
    <div class="container-fluid">
        <div class="brand-slider">
            @forelse ($brandsSlider as $brandSlider)
                <div class="brand-item"><img src="{{ asset('uploads/images/brands/thumb/'.$brandSlider->image) }}" height="80" width="150" alt=""></div>
            @empty
                <div class="brand-item"><img src="{{ asset('assets/front/img/brand-1.png') }}" alt=""></div>
            @endforelse
        </div>
    </div>
</div>
<!-- Brand End -->

@endsection

@section('css')
<link rel="stylesheet" href="{{ asset('assets/front/css/custom.css') }}">
@endsection
