@extends('layouts.front.app')

@section('content')
<!-- Breadcrumb Start -->
<div class="breadcrumb-wrap">
    <div class="container-fluid">
        <ul class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('ecommerce.index') }}">Home</a></li>
            <li class="breadcrumb-item"><a href="#">{{ $product->categories->parent->name }}</a></li>
            <li class="breadcrumb-item"><a href="#">{{ $product->categories->name }}</a></li>
            <li class="breadcrumb-item active">{{ substr($product->name, 0, 60) }}</li>
        </ul>
    </div>
</div>
<!-- Breadcrumb End -->

<!-- Product Detail Start -->
<div class="product-detail">
    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-8">
                <div class="product-detail-top">
                    <div class="row align-items-center">
                        <div class="col-md-5">
                            <div class="product-slider-single normal-slider">
                                <img src="{{ asset($product->productimages->path.$product->productimages->image1) }}" alt="Product Image">
                                <img src="{{ asset($product->productimages->path.$product->productimages->image2) }}" alt="Product Image">
                                <img src="{{ asset($product->productimages->path.$product->productimages->image3) }}" alt="Product Image">
                                <img src="{{ asset($product->productimages->path.$product->productimages->image4) }}" alt="Product Image">
                                <img src="{{ asset($product->productimages->path.$product->productimages->image5) }}" alt="Product Image">
                            </div>
                            <div class="product-slider-single-nav ">
                                <div class="slider-nav-img"><img src="{{ asset($product->productimages->thumb.$product->productimages->image1) }}" alt="Product Image" width="50px"></div>
                                <div class="slider-nav-img"><img src="{{ asset($product->productimages->thumb.$product->productimages->image2) }}" alt="Product Image" width="50px"></div>
                                <div class="slider-nav-img"><img src="{{ asset($product->productimages->thumb.$product->productimages->image3) }}" alt="Product Image" width="50px"></div>
                                <div class="slider-nav-img"><img src="{{ asset($product->productimages->thumb.$product->productimages->image4) }}" alt="Product Image" width="50px"></div>
                                <div class="slider-nav-img"><img src="{{ asset($product->productimages->thumb.$product->productimages->image5) }}" alt="Product Image" width="50px"></div>
                            </div>
                        </div>
                        <div class="col-md-7">
                            <div class="product-content">
                                <div class="title"><h2>{{ $product->name }}</h2></div>
                                <div class="info-product">
                                    <span class="sold-product">
                                        Sold : 123132
                                        •
                                    </span>
                                    <span class="ratting">
                                        <i class="fa fa-star"></i>
                                        0 (120 reviews)
                                    </span>
                                </div>
                                <div class="brands">
                                    <h4>Brand:</h4>
                                    <a href="{{ route('ecommerce.product.brand', $product->brands->slug) }}"><p>{{ $product->brands->name }}</p></a>
                                </div>
                                <div class="warranty">
                                    <h4>Warranty:</h4>
                                    <p>{{ $product->warranties->name }}</p>
                                </div>
                                <div class="price">
                                    <h4>Price :</h4>
                                    {{-- <p>$99 <span>$149</span></p> --}}
                                    <p>Rp. {{ number_format($product->price, 0) }}</p>
                                </div>
                                <div class="quantity">
                                    <h4>Quantity:</h4>
                                    <div class="qty">
                                        <button class="btn-minus"><i class="fa fa-minus"></i></button>
                                        <input type="text" value="1">
                                        <button class="btn-plus"><i class="fa fa-plus"></i></button>
                                    </div>
                                </div>
                                <div class="action">
                                    <a class="btn" href="#"><i class="fa fa-shopping-cart"></i>Add to Cart</a>
                                    <a class="btn" href="#"><i class="fa fa-shopping-bag"></i>Buy Now</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row product-detail-bottom">
                    <div class="col-lg-12">
                        <ul class="nav nav-pills nav-justified">
                            <li class="nav-item">
                                <a class="nav-link active" data-toggle="pill" href="#description">Description</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" data-toggle="pill" href="#specification">Specification</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" data-toggle="pill" href="#reviews">Reviews (1)</a>
                            </li>
                        </ul>

                        <div class="tab-content">
                            <div id="description" class="container tab-pane active overflow-auto">
                                {!! $product->description !!}
                            </div>
                            <div id="specification" class="container tab-pane fade overflow-auto">
                                {!! $product->specification !!}
                            </div>
                            <div id="reviews" class="container tab-pane fade overflow-auto">
                                <div class="reviews-submitted">
                                    <div class="reviewer">Phasellus Gravida - <span>01 Jan 2020</span></div>
                                    <div class="ratting">
                                        <i class="fa fa-star"></i>
                                        <i class="fa fa-star"></i>
                                        <i class="fa fa-star"></i>
                                        <i class="fa fa-star"></i>
                                        <i class="fa fa-star"></i>
                                    </div>
                                    <p>
                                        Sed ut perspiciatis unde omnis iste natus error sit voluptatem accusantium doloremque laudantium, totam rem aperiam.
                                    </p>
                                </div>
                                <div class="reviews-submit">
                                    <h4>Give your Review:</h4>
                                    <div class="ratting">
                                        <i class="far fa-star"></i>
                                        <i class="far fa-star"></i>
                                        <i class="far fa-star"></i>
                                        <i class="far fa-star"></i>
                                        <i class="far fa-star"></i>
                                    </div>
                                    <div class="row form">
                                        <div class="col-sm-6">
                                            <input type="text" placeholder="Name">
                                        </div>
                                        <div class="col-sm-6">
                                            <input type="email" placeholder="Email">
                                        </div>
                                        <div class="col-sm-12">
                                            <textarea placeholder="Review"></textarea>
                                        </div>
                                        <div class="col-sm-12">
                                            <button>Submit</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="product">
                    <div class="section-header">
                        <h1>Related Products</h1>
                    </div>
                    <div class="row align-items-center product-slider product-slider-3">
                        @forelse ($relatedProduct as $related)
                        <div class="{{ $relatedProductCount < 3 ? "col-lg-12":"col-lg-4" }}">
                            <div class="product-item">
                                <div class="product-title">
                                    <a href="#">{{ substr($related->name, 0, 60) }}</a>
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
                                        <img src="{{ asset($related->productimages->thumb.$related->productimages->image1) }}" alt="Product Image">
                                    </a>
                                    <div class="product-action">
                                        <a href="#"><i class="fa fa-cart-plus"></i></a>
                                        <a href="#"><i class="fa fa-heart"></i></a>
                                    </div>
                                </div>
                                <div class="product-price">
                                    <h3><span>$</span>99</h3>
                                </div>
                            </div>
                        </div>
                        @empty
                            <div class="col-lg-12">
                                <div class="product-item">

                                    <span>Related Products No Available</span>
                                </div>
                            </div>
                        @endforelse
                    </div>
                </div>
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
                                    <a href="{{ route('ecommerce.product.detail', [$random->categories->parent->slug, $random->categories->slug, $random->slug]) }}">{{substr($random->name, 0, 35) }} {{ strlen($random->name) > 35 ? "..." : ""}}</a>
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

                <div class="sidebar-widget tag">

                </div>
            </div>
            <!-- Side Bar End -->
        </div>
    </div>
</div>
<!-- Product Detail End -->

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
<style>
    #description, #specification, #reviews {
        min-height: 350px;
        max-height: 350px;
    }

    .sidebar-widget.brands {
        max-height: 350px;
    }

.product-content .brands h4 {
    display: inline-block;
    width: 80px;
    font-size: 18px;
    font-weight: 700;
    margin-right: 5px;
    margin-top: 10px;
}

.product-content .brands p {
    display: inline-block;
    font-size: 18px;
    font-weight: 700;
    margin: 0;
}

.product-content .warranty h4 {
    display: inline-block;
    width: 80px;
    font-size: 18px;
    font-weight: 700;
    margin-right: 5px;
    margin-top: 10px;
}

.product-content .warranty p {
    display: inline-block;
    font-size: 18px;
    font-weight: 700;
    margin: 0;
}
</style>
@endsection
