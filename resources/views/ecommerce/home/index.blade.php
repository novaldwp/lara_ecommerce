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
                    <div class="header-slider-item">
                        <img src="{{ asset('assets/front/img/slider-1.jpg') }}" alt="Slider Image" width="100%" height="400"/>
                        <div class="header-slider-caption">
                            <p>Lorem ipsum, dolor sit amet consectetur adipisicing elit. Nesciunt corporis modi, veritatis mollitia pariatur quod harum qui tempora! Iusto dolorum qui consequuntur fugiat? Sint beatae quibusdam necessitatibus, consequatur a molestiae?</p>
                        </div>
                    </div>
                    <div class="header-slider-item">
                        <img src="{{ asset('assets/front/img/slider-2.jpg') }}" alt="Slider Image" width="100%" height="400" />
                        <div class="header-slider-caption">
                            <p>Lorem ipsum dolor, sit amet consectetur adipisicing elit. Modi sed accusamus alias ipsum iure ex tempore beatae quos. Consequatur aut, esse perspiciatis enim cum libero ipsa illum ut sit et.</p>
                        </div>
                    </div>
                    <div class="header-slider-item">
                        <img src="{{ asset('assets/front/img/slider-3.jpg') }}" alt="Slider Image" width="100%" height="400" />
                        <div class="header-slider-caption">
                            <p>Lorem ipsum dolor sit amet consectetur adipisicing elit. Recusandae sint ullam tempora veritatis nobis sed quia ut architecto consequuntur impedit modi, at numquam ex temporibus alias vitae quis cum. Ullam.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- Main Slider End -->

<!-- Brand Start -->
<div class="brand">
    <div class="container-fluid">
        <div class="brand-slider">
            @forelse ($brands as $brand)
                <div class="brand-item"><img src="{{ asset('uploads/images/brands/thumb/'.$brand->image) }}" height="80" width="150" alt=""></div>
            @empty
                <div class="brand-item"><img src="{{ asset('uploads/images/no_image.png') }}" height="80" width="150" alt=""></div>
            @endforelse
        </div>
    </div>
</div>
<!-- Brand End -->

<!-- Call to Action Start -->
<div class="call-to-action">
    <div class="container-fluid">
        <div class="row align-items-center">
            <div class="col-md-6">
                <h1>call us for any queries</h1>
            </div>
            <div class="col-md-6">
                <a href="tel:0123456789">+012-345-6789</a>
            </div>
        </div>
    </div>
</div>
<!-- Call to Action End -->

<!-- Featured Product Start -->
<div class="featured-product product">
    <div class="container-fluid">
        <div class="section-header">
            <h1>Featured Product</h1>
        </div>
        <div class="row align-items-center product-slider product-slider-4">
            @forelse ($featureds as $feature)
                <div class="col-lg-3">
                    <div class="product-item">
                        <div class="product-title">
                            <a href="{{ route('ecommerce.product.detail', [$feature->categories->parent->slug, $feature->categories->slug, $feature->slug]) }}">{{ substr($feature->name, 0, 60) }}</a>
                            <div class="ratting">
                                <i class="fa fa-star"></i>
                                <i class="fa fa-star"></i>
                                <i class="fa fa-star"></i>
                                <i class="fa fa-star"></i>
                                <i class="fa fa-star"></i>
                            </div>
                        </div>
                        <div class="product-image">
                            <a href="#">
                                <img src="{{ asset($feature->productimages->thumb.$feature->productimages->image1) }}" alt="Product Image" width="300" height="300">
                            </a>
                            <div class="product-action">
                                <a href="#"><i class="fa fa-cart-plus"></i></a>
                                <a href="#"><i class="fa fa-heart"></i></a>
                            </div>
                        </div>
                        <div class="product-price text-center">
                            <h3><span>Rp. </span>{{ number_format($feature->price, 0) }}</h3>
                        </div>
                    </div>
                </div>
            @empty
                kosong
            @endforelse

        </div>
    </div>
</div>
<!-- Featured Product End -->

<!-- Newsletter Start -->
<div class="newsletter">
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-6">
                <h1>Subscribe Our Newsletter</h1>
            </div>
            <div class="col-md-6">
                <div class="form">
                    <input type="email" value="Your email here">
                    <button>Submit</button>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- Newsletter End -->

<!-- Recent Product Start -->
<div class="recent-product product">
    <div class="container-fluid">
        <div class="section-header">
            <h1>Recent Product</h1>
        </div>
        <div class="row align-items-center product-slider product-slider-4">
            @forelse ($recents as $recent)
                <div class="col-lg-3">
                    <div class="product-item">
                        <div class="product-title">
                            <a href="#">{{ substr($recent->name, 0, 60) }}</a>
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
                                <img src="{{ asset($recent->productimages->thumb.$recent->productimages->image1) }}" alt="Product Image">
                            </a>
                            <div class="product-action">
                                <a href="#"><i class="fa fa-cart-plus"></i></a>
                                <a href="#"><i class="fa fa-heart"></i></a>
                                <a href="#"><i class="fa fa-search"></i></a>
                            </div>
                        </div>
                        <div class="product-price">
                            <h3><span>Rp. </span>{{ number_format($recent->price, 0) }}</h3>
                            <a class="btn" href=""><i class="fa fa-shopping-cart"></i>Buy Now</a>
                        </div>
                    </div>
                </div>
            @empty

            @endforelse
        </div>
    </div>
</div>
<!-- Recent Product End -->

<!-- Review Start -->
<div class="review">
    <div class="container-fluid">
        <div class="row align-items-center review-slider normal-slider">
            <div class="col-md-6">
                <div class="review-slider-item">
                    <div class="review-img">
                        <img src="{{ asset('assets/front/img/review-1.jpg') }}" alt="Image">
                    </div>
                    <div class="review-text">
                        <h2>Customer Name</h2>
                        <h3>Profession</h3>
                        <div class="ratting">
                            <i class="fa fa-star"></i>
                            <i class="fa fa-star"></i>
                            <i class="fa fa-star"></i>
                            <i class="fa fa-star"></i>
                            <i class="fa fa-star"></i>
                        </div>
                        <p>
                            Lorem ipsum dolor sit amet, consectetur adipiscing elit. Curabitur vitae nunc eget leo finibus luctus et vitae lorem
                        </p>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="review-slider-item">
                    <div class="review-img">
                        <img src="{{ asset('assets/front/img/review-2.jpg') }}" alt="Image">
                    </div>
                    <div class="review-text">
                        <h2>Customer Name</h2>
                        <h3>Profession</h3>
                        <div class="ratting">
                            <i class="fa fa-star"></i>
                            <i class="fa fa-star"></i>
                            <i class="fa fa-star"></i>
                            <i class="fa fa-star"></i>
                            <i class="fa fa-star"></i>
                        </div>
                        <p>
                            Lorem ipsum dolor sit amet, consectetur adipiscing elit. Curabitur vitae nunc eget leo finibus luctus et vitae lorem
                        </p>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="review-slider-item">
                    <div class="review-img">
                        <img src="{{ asset('assets/front/img/review-3.jpg') }}" alt="Image">
                    </div>
                    <div class="review-text">
                        <h2>Customer Name</h2>
                        <h3>Profession</h3>
                        <div class="ratting">
                            <i class="fa fa-star"></i>
                            <i class="fa fa-star"></i>
                            <i class="fa fa-star"></i>
                            <i class="fa fa-star"></i>
                            <i class="fa fa-star"></i>
                        </div>
                        <p>
                            Lorem ipsum dolor sit amet, consectetur adipiscing elit. Curabitur vitae nunc eget leo finibus luctus et vitae lorem
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- Review End -->
@endsection

@section('css')
<link rel="stylesheet" href="{{ asset('assets/front/css/custom.css') }}">
@endsection
