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
{{-- {{ dd($carts) }} --}}
<!-- Checkout Start -->
<div class="checkout">
    <div class="container-fluid">
        <form action="{{ route('ecommerce.order.store') }}" method="post">
            @csrf
            <div class="row">
                <div class="col-lg-7">
                    <div class="checkout-inner">
                        <div class="billing-address">
                            <h2>Shipping Address</h2>
                            <div class="row">
                                <div class="col-md-6">
                                    <input type="hidden" name="member_id" value="{{ auth()->guard('members')->user()->id }}">
                                    <input type="hidden" name="base_price" value="{{ $basePrice }}">
                                    <input type="hidden" name="shipping_cost" value="">
                                    <input type="hidden" name="total_price" value="">
                                    @foreach($carts as $cart)
                                    <input type="hidden" name="product_id[]" value="{{ $cart->product_id }}">
                                    <input type="hidden" name="amount[]" value="{{ $cart->amount }}">
                                    @endforeach
                                    <label>First Name</label>
                                    <input class="form-control" type="text" name="first_name" value="{{ auth()->guard('members')->user()->first_name }}">
                                </div>
                                <div class="col-md-6">
                                    <label>Last Name</label>
                                    <input class="form-control" type="text" name="last_name" value="{{ auth()->guard('members')->user()->last_name ?? "" }}">
                                </div>
                                <div class="col-md-12">
                                    <label>Mobile No</label>
                                    <input class="form-control" type="text" name="phone" value="{{ auth()->guard('members')->user()->phone }}">
                                </div>
                                <div class="col-md-12">
                                    <label>Address</label>
                                    <select name="address_id" id="address_id" class="form-control">
                                        @forelse ($addresses as $row)
                                            @if ($loop->first)
                                                <option value="" selected disabled>Pilih Alamat</option>
                                            @endif
                                            <option value="{{ $row->id }}" data-city="{{ $row->cities->id }}"><strong>
                                                {{ $row->name }} : </strong>
                                                {{ $row->street }}, {{ $row->cities->name }}, {{ $row->provinces->name }}, {{ $row->postcode }}
                                            </option>
                                        @empty
                                            <option value="" selected>No Address Available</option>
                                        @endforelse
                                    </select>
                                </div>
                                <div class="col-md-12">
                                    <label>Shipping Courier</label>
                                    <select name="shipping_courier" id="shipping_courier" class="form-control">
                                        <option value="" selected disabled>Choose Courier</option>
                                        <option value="jne">JNE</option>
                                        <option value="pos">POS</option>
                                        <option value="tiki">TIKI</option>
                                    </select>
                                </div>
                                <div class="col-md-12">
                                    <label>Shipping Service</label>
                                    <select name="shipping_service" id="shipping_service" class="form-control" disabled>
                                        <option value="" selected>Choose Courier Service</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-5">
                    <div class="checkout-inner">
                        <div class="checkout-summary">
                            <h1>Cart Total</h1>
                            <p class="sub-total" data-weight="{{ $totalWeight }}">Sub Total<span>Rp. {{ number_format($basePrice, 0) }}</span></p>
                            <p class="ship-cost">Shipping Cost<span name="shipping_cost">Rp. 0</span></p>
                            <h2>Grand Total<span name="total_price">Rp. 0</span></h2>
                        </div>
                        <div class="checkout-payment">
                            <div class="checkout-btn">
                                <button id="btn-order">Place Order</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </form>
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
<script src="{{ asset('js/convertrupiah.js') }}"></script>
<script>
    $(document).ready(function() {

        let statusCourier = 0;
        let statusService = 0;
        let statusAddress = 0;

        $('#address_id').on('change', function(e) {
            e.preventDefault();

            $('#courier_service').empty();
            $('#courier_service').prop('disabled', true);
            $('#courier_service').append('<option selected disabled>Choose Courier Service</option>');

            $('#courier option[value=""]').prop('disabled', false);
            $('#courier').val("").change();
            $('#courier option[value=""]').prop('disabled', true);

            if ($(this).val() != null) statusAddress = 1;
        })

        $('#shipping_courier').on('change', function(e) {
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
                    $('#shipping_service').empty();
                    $('#shipping_service').append('<option selected disabled>Choose Courier Service</option>');
                    $('#shipping_service').prop('disabled', false);
                    statusCourier = 1;
                    let data = res.rajaongkir.results[0].costs;
                    for (let i=0; i<data.length; i++)
                    {
                        $('#shipping_service').append('<option value="'+data[i].service+'" data-price="'+data[i].cost[0].value+'">'+data[i].description+' ('+data[i].service+')</option>');
                    }
                }
            })
        })

        $('#shipping_service').on('change', function(e) {
            e.preventDefault();

            let shipping_cost   = parseInt($('#shipping_service option:selected').data('price'));
            let base_price      = parseInt($('input:hidden[name="base_price"]').val());
            let total_price     = (shipping_cost + base_price);
            statusService = 1;

            $('span[name="shipping_cost"]').text(convertToRupiah(shipping_cost));
            $('span[name="total_price"]').text(convertToRupiah(total_price));
            $('input:hidden[name="shipping_cost"]').val(shipping_cost);
            $('input:hidden[name="total_price"]').val(total_price);
        });

        $('#btn-order').on('click', function(e) {
            let shipping_courier = $('#shipping_courier').val();
            let shipping_service = $('#shipping_service').val();
            let address          = $('#address_id option:selected').val();

            if ((statusAddress == 1 && statusCourier == 1 && statusService == 1))
            {
                return true;
            }
            else {
                e.preventDefault();
                Swal.fire({
                    icon: 'warning',
                    title: 'Can\'t Proceed',
                    text: 'Please complete the shipping form',
                    showConfirmButton: false,
                    timer: 1500
                });
            }
        })
    })
</script>
@endsection
