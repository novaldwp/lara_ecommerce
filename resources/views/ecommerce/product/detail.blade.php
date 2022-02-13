@extends('layouts.front.app')

@section('title')
    Jual {{ $product->name }} | Toko Putra Elektronik
@endsection

@section('content')
<!-- Breadcrumb Start -->
<div class="breadcrumb-wrap">
    <div class="container-fluid">
        <ul class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('ecommerce.index') }}">Beranda</a></li>
            <li class="breadcrumb-item"><a href="#">{{ $product->categories->parent->name }}</a></li>
            <li class="breadcrumb-item"><a href="#">{{ $product->categories->name }}</a></li>
            <li class="breadcrumb-item active">{{ substr($product->name, 0, 50) }} {{ strlen($product->name) > 50 ? "..." : "" }}</li>
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
                            <div class="product-slider-single-nav">
                                <div class="slider-nav-img"><img src="{{ asset($product->productimages->thumb.$product->productimages->image1) }}" alt="Product Image" width="50px"></div>
                                <div class="slider-nav-img"><img src="{{ asset($product->productimages->thumb.$product->productimages->image2) }}" alt="Product Image" width="50px"></div>
                                <div class="slider-nav-img"><img src="{{ asset($product->productimages->thumb.$product->productimages->image3) }}" alt="Product Image" width="50px"></div>
                                <div class="slider-nav-img"><img src="{{ asset($product->productimages->thumb.$product->productimages->image4) }}" alt="Product Image" width="50px"></div>
                                <div class="slider-nav-img"><img src="{{ asset($product->productimages->thumb.$product->productimages->image5) }}" alt="Product Image" width="50px"></div>
                            </div>
                        </div>
                        <div class="col-md-7">
                            <div class="product-content">
                                <input type="hidden" name="slug" value="{{ $product->slug }}">
                                <div class="title"><h2>{{ $product->name }}</h2></div>
                                <div class="info-product">
                                    <span class="sold-product">
                                        Terjual : {{ $productSold ?? 0 }}
                                        •
                                    </span>
                                    <span class="ratting">
                                        <i class="fa fa-star"></i>
                                        {{ $productRating }} ({{ $product->reviews->count() }} ulasan)
                                    </span>
                                </div>
                                <div class="brands">
                                    <h4>Merk: </h4>
                                    @if ($product->brands)
                                        <a href="{{ route('ecommerce.product.brand', $product->brands->slug) }}"><p>{{ $product->brands->name }}</p></a>
                                    @else
                                        <a href="javascript:void(0);"><p>Tidak ada Merk</p></a>
                                    @endif
                                </div>
                                <div class="price">
                                    <h4>Price :</h4>
                                    <p>{{ convert_to_rupiah($product->price, 0) }}</p>
                                </div>
                                @role('customer')
                                    <div class="action">
                                        <button class="btn" name="action" value="cart" id="addCartButton" data-product="{{ simple_encrypt($product->id) }}"><i class="fa fa-shopping-cart"></i>Add to Cart</button>
                                    </div>
                                @endrole
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row product-detail-bottom">
                    <div class="col-lg-12">
                        <ul class="nav nav-pills nav-justified">
                            <li class="nav-item">
                                <a class="nav-link active" data-toggle="pill" href="#description">Deskripsi</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" data-toggle="pill" href="#specification">Spesifikasi</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" data-toggle="pill" href="#reviews">Ulasan ({{ $product->reviews_count }})</a>
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
                                {{-- {{ dd($product->reviews) }} --}}
                                @forelse ($productReviews as $productReview )
                                    <div class="reviews-submitted">
                                        <div class="reviewer">{{ $productReview->users->first_name . ' ' . $productReview->users->last_name }}</div>
                                        <div class="ratting">
                                            @for($i = 0; $i < $productReview->rating; $i++)
                                            <i class="fa fa-star"></i>
                                            @endfor
                                        </div>
                                        <p>
                                            {{ $productReview->message }}
                                        </p>
                                        <hr>
                                    </div>
                                @empty
                                    <div class="reviews-submitted">
                                        Belum ada ulasan.
                                    </div>
                                @endforelse
                            </div>
                        </div>
                    </div>
                </div>

                <div class="product">
                    <div class="section-header">
                        <h1>Produk Terkait</h1>
                    </div>
                    <div class="row align-items-center product-slider product-slider-3">
                        @forelse ($relatedProducts as $relatedProduct)
                        <div class="{{ $relatedProductCount < 3 ? "col-lg-12":"col-lg-4" }}">
                            <div class="product-item">
                                <div class="product-title">
                                    <a href="{{ route('ecommerce.product.detail', [$relatedProduct->categories->parent->slug, $relatedProduct->categories->slug, $relatedProduct->slug]) }}">{{ substr($relatedProduct->name, 0, 45) }}</a>
                                </div>
                                <div class="product-image">
                                    <a href="{{ route('ecommerce.product.detail', [$relatedProduct->categories->parent->slug, $relatedProduct->categories->slug, $relatedProduct->slug]) }}">
                                        <img src="{{ asset($relatedProduct->productimages->thumb.$relatedProduct->productimages->image1) }}" loading="lazy" alt="{{ $relatedProduct->name }}">
                                    </a>
                                    @role('customer')
                                        <div class="product-action">
                                            <a href="javascript:void(0);" id="addCartButton" data-product="{{ simple_encrypt($relatedProduct->id) }}"><i class="fa fa-cart-plus"></i></a>
                                            <a href="javascript:void(0);" id="addWishlistButton" data-product="{{ simple_encrypt($relatedProduct->id) }}"><i class="fa fa-heart"></i></a>
                                        </div>
                                    @endrole
                                </div>
                                <div class="product-price text-center">
                                    <h3>{{ convert_to_rupiah($relatedProduct->price) }}</h3>
                                </div>
                            </div>
                        </div>
                        @empty
                            <div class="col-lg-12">
                                <div class="product-item">
                                    <span>No Related Products Available</span>
                                </div>
                            </div>
                        @endforelse
                    </div>
                </div>
            </div>

            <!-- Side Bar Start -->
                @include('ecommerce._partial.sidebar')
            <!-- Side Bar End -->
        </div>
    </div>
</div>
<!-- Product Detail End -->

<!-- Brand Start -->
    @include('ecommerce._partial.brands-slider')
<!-- Brand End -->
@endsection

@section('scripts')
<script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
<script src="{{ asset('js/front/cart.js') }}"></script>
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

.product-detail .product-content .quantity a {
    width: 30px;
    height: 30px;
    padding: 2px 0;
    font-size: 16px;
    text-align: center;
    color: #ffffff;
    background: #FF6F61;
    border: none;
    margin-bottom: 12px;
}
</style>
@endsection
