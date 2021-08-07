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
                    @if($order->status != "9")
                        @include('ecommerce.payment.finish')
                    @else
                        @include('ecommerce.payment.unfinish')
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
<!-- Wishlist End -->
@endsection

@section('scripts')
<script>
    let payment_due = document.querySelector('#payment_due').value;
    let due = new Date(payment_due).getTime();

    let x = setInterval(function() {
        let now = new Date().getTime();
        let distance = due - now;
        let days = Math.floor(distance / (1000 * 60 * 60 * 24));
        let hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
        var minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
        var seconds = Math.floor((distance % (1000 * 60)) / 1000);
        minutes = minutes < 10 ? `0${minutes}`:minutes;

        document.querySelector('.timer').innerHTML = `${hours}:${minutes}:${seconds}`;

        if (distance < 0)
        {
            clearInterval();
            document.querySelector('.timer').value = "EXPIRED";
        }
    }, 1000);
</script>
@endsection
