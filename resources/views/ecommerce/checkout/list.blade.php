@extends('layouts.front.app')

@section('content')
<!-- Breadcrumb Start -->
<div class="breadcrumb-wrap">
    <div class="container-fluid">
        <ul class="breadcrumb">
            <li class="breadcrumb-item"><a href="#">Home</a></li>
            <li class="breadcrumb-item"><a href="#">Products</a></li>
            <li class="breadcrumb-item active">Checkout</li>
        </ul>
    </div>
</div>
<!-- Breadcrumb End -->

<!-- Checkout Start -->
<div class="checkout">
    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-7">
                <div class="checkout-inner">
                    <div class="billing-address">
                        <h2>Shipping Address</h2>
                        <div class="row">
                            <div class="col-md-6">
                                <label>First Name</label>
                                <input class="form-control" type="text" value="{{ auth()->guard('members')->user()->first_name }}" readonly>
                            </div>
                            <div class="col-md-6">
                                <label>Last Name</label>
                                <input class="form-control" type="text" value="{{ auth()->guard('members')->user()->last_name ?? "" }}" readonly>
                            </div>
                            <div class="col-md-6">
                                <label>E-mail</label>
                                <input class="form-control" type="text" value="{{ auth()->guard('members')->user()->email }}" readonly>
                            </div>
                            <div class="col-md-6">
                                <label>Mobile No</label>
                                <input class="form-control" type="text" value="{{ auth()->guard('members')->user()->phone }}" readonly>
                            </div>
                            <div class="col-md-12">
                                <label>Address</label>
                                <select name="address_id" id="address_id" class="form-control">
                                    @forelse ($addresses as $row)
                                        <option value="{{ $row->id }}" data-city="{{ $row->cities->id }}"><strong>
                                            {{ $row->name }} : </strong>
                                            {{ $row->street }}, {{ $row->cities->name }}, {{ $row->provinces->name }}, {{ $row->postcode }}
                                        </option>
                                    @empty
                                        <option value="">No Address Available</option>
                                    @endforelse
                                </select>
                            </div>
                            <div class="col-md-12">
                                <label>Courier</label>
                                <select name="courier" id="courier" class="form-control">
                                    <option value="" selected disabled>Choose Courier</option>
                                    <option value="jne">JNE</option>
                                    <option value="pos">POS</option>
                                    <option value="tiki">TIKI</option>
                                </select>
                            </div>
                            <div class="col-md-12">
                                <label>Service</label>
                                <select name="courier_service" id="courier_service" class="form-control" disabled>
                                    <option value="" selected>Choose Courier Service</option>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="checkout-inner">
                    <div class="billing-address">
                        <h2>Products List</h2>
                        <div class="row">
                            <div class="col-lg-12">
                                <div class="table-responsive table-checkout">
                                    <table name="table-products" class="table table-bordered" id="list-cart">
                                        <thead>
                                            <th>Product</th>
                                            <th>Quantity</th>
                                            <th width="18%">Subtotal</th>
                                            <th width="15%">Weight (gr.)</th>
                                        </thead>
                                        <tbody>
                                            @forelse ($carts as $cart)
                                                <tr class="">
                                                    <td>
                                                        <div class="img">
                                                            <a href="{{ route('ecommerce.product.detail', [$cart->products->categories->parent->slug, $cart->products->categories->slug, $cart->products->slug]) }}">
                                                                <img src="{{ asset($cart->products->productimages->thumb.$cart->products->productimages->image1) }}" alt="Image">
                                                            </a>
                                                            <p>{{ $cart->products->name }}</p>
                                                        </div>
                                                    </td>
                                                    <td>{{ $cart->amount }}</td>
                                                    <td>Rp. {{ number_format(($cart->products->price * $cart->amount), 0) }}</td>
                                                    <td>{{ number_format($cart->products->weight * $cart->amount, 0) }}</td>
                                                </tr>
                                            @empty
                                                <tr>
                                                    <td colspan="5" class="text-center"><img src="{{ asset('uploads/images/cart_empty.jpg') }}" width="100%"></td>
                                                </tr>
                                            @endforelse
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-5">
                <div class="checkout-inner">
                    <div class="checkout-summary">
                        <h1>Cart Total</h1>
                        <p class="sub-total" data-weight="{{ $totalWeight }}">Sub Total<span>Rp. {{ number_format($totalPrice, 0) }}</span></p>
                        <p class="ship-cost">Shipping Cost<span>$1</span></p>
                        <h2>Grand Total<span>$100</span></h2>
                    </div>
                    <div class="checkout-payment">
                        <div class="payment-methods">
                            <h1>Payment Methods</h1>
                            <div class="payment-method">
                                <div class="custom-control custom-radio">
                                    <input type="radio" class="custom-control-input" id="payment-1" name="payment">
                                    <label class="custom-control-label" for="payment-1">Paypal</label>
                                </div>
                                <div class="payment-content" id="payment-1-show">
                                    <p>
                                        Lorem ipsum dolor sit amet, consectetur adipiscing elit. Cras tincidunt orci ac eros volutpat maximus lacinia quis diam.
                                    </p>
                                </div>
                            </div>
                            <div class="payment-method">
                                <div class="custom-control custom-radio">
                                    <input type="radio" class="custom-control-input" id="payment-2" name="payment">
                                    <label class="custom-control-label" for="payment-2">Payoneer</label>
                                </div>
                                <div class="payment-content" id="payment-2-show">
                                    <p>
                                        Lorem ipsum dolor sit amet, consectetur adipiscing elit. Cras tincidunt orci ac eros volutpat maximus lacinia quis diam.
                                    </p>
                                </div>
                            </div>
                            <div class="payment-method">
                                <div class="custom-control custom-radio">
                                    <input type="radio" class="custom-control-input" id="payment-3" name="payment">
                                    <label class="custom-control-label" for="payment-3">Check Payment</label>
                                </div>
                                <div class="payment-content" id="payment-3-show">
                                    <p>
                                        Lorem ipsum dolor sit amet, consectetur adipiscing elit. Cras tincidunt orci ac eros volutpat maximus lacinia quis diam.
                                    </p>
                                </div>
                            </div>
                            <div class="payment-method">
                                <div class="custom-control custom-radio">
                                    <input type="radio" class="custom-control-input" id="payment-4" name="payment">
                                    <label class="custom-control-label" for="payment-4">Direct Bank Transfer</label>
                                </div>
                                <div class="payment-content" id="payment-4-show">
                                    <p>
                                        Lorem ipsum dolor sit amet, consectetur adipiscing elit. Cras tincidunt orci ac eros volutpat maximus lacinia quis diam.
                                    </p>
                                </div>
                            </div>
                            <div class="payment-method">
                                <div class="custom-control custom-radio">
                                    <input type="radio" class="custom-control-input" id="payment-5" name="payment">
                                    <label class="custom-control-label" for="payment-5">Cash on Delivery</label>
                                </div>
                                <div class="payment-content" id="payment-5-show">
                                    <p>
                                        Lorem ipsum dolor sit amet, consectetur adipiscing elit. Cras tincidunt orci ac eros volutpat maximus lacinia quis diam.
                                    </p>
                                </div>
                            </div>
                        </div>
                        <div class="checkout-btn">
                            <button>Place Order</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- Checkout End -->
@endsection

@section('css')
<style>
div.img {
     display:flex;
    align-items: center;
 }

.table-checkout table td {
    font-size: 16px;
    vertical-align: middle;
}

.table-checkout table {
    text-align: center;
}

.table-checkout table .img img {
    max-width: 60px;
    max-height: 60px;
    margin-right: 15px;
}

tr {
    display: table-row;
    vertical-align: inherit;
    border-color: inherit;
}
</style>
@endsection

@section('scripts')
<script>
    $(document).ready(function() {

        $('#address_id').on('change', function(e) {
            e.preventDefault();

            $('#courier_service').empty();
            $('#courier_service').prop('disabled', true);
            $('#courier_service').append('<option selected disabled>Choose Courier Service</option>');

            $('#courier option[value=""]').prop('disabled', false);
            $('#courier').val("").change();
            $('#courier option[value=""]').prop('disabled', true);
        })

        $('#courier').on('change', function(e) {
            e.preventDefault();
            let courier = $(this).val();
            let city = $('#address_id option:selected').data('city');
            let weight = $('p.sub-total').data('weight');

            $.ajax({
                url: '/api/test',
                method: 'GET',
                dataType: 'JSON',
                data: { courier:courier, city:city, weight:weight },
                success: function(res)
                {
                    console.log(res.rajaongkir.results[0].costs);
                    $('#courier_service').empty();
                    $('#courier_service').append('<option selected disabled>Choose Courier Service</option>');
                    $('#courier_service').prop('disabled', false);
                    let data = res.rajaongkir.results[0].costs;
                    for (let i=0; i<data.length; i++)
                    {
                        $('#courier_service').append('<option value="'+data[i].service+'" data-price="'+data[i].cost[0].value+'">'+data[i].description+' ('+data[i].service+')</option>');
                    }
                }
            })
        })
    })
</script>
@endsection
