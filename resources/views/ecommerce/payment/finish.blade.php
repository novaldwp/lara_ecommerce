@extends('layouts.front.app')
@section('css')
<style>
p.header-completed {
    line-height: 1;
}

p.header-completed.timer {
    color: #fd7e14;
}

div.payment-information {
    max-width: 33%;
    margin: 0 auto;
}
.payment-information .information-header {
    font-size: calc(12px + 1.5vw);
}

.payment-code {
    padding-bottom:20px;
}
.payment-code p {
    line-height: 0.5;
}

.payment-code .header {
    color: gray;
}

.payment-amount p {
    line-height: 0.5;
}

.payment-amount .header {
    color: grey;
}

.payment-button {
    padding-top: 1%;
    max-width: 33%;
    margin: 0 auto;
    position: center;
}

.payment-button .left {
    float: left;
    width: 48%;
}

.payment-button .right {
    float: right;
    width: 48%;
}
</style>
@endsection
@section('content')
<!-- Breadcrumb Start -->
<div class="breadcrumb-wrap">
    <div class="container-fluid">
        <ul class="breadcrumb">
            <li class="breadcrumb-item"><a href="#">Home</a></li>
            <li class="breadcrumb-item active">Cart</li>
        </ul>
    </div>
</div>
<!-- Breadcrumb End -->

<!-- Wishlist Start -->
<div class="wishlist-page">
    <div class="container-fluid">
        <div class="wishlist-page-inner">
            <div class="row">
                <div class="col-lg-12">
                    <div class="col-lg-12">
                        <p class="alert alert-success">Thank you for making the payment, your order now is in progress.</p>
                    </div>
                    <div class="payment-button">
                        <a href="{{ route('ecommerce.product.index') }}" class="btn left">Shopping Again</a>
                        <a href="{{ route('ecommerce.profile.orders') }}" class="btn right">Check Payment Status</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- Wishlist End -->
@endsection
