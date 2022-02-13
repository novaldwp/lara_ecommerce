@extends('layouts.front.app')

@section('title')
    Checkout | Toko Putra Elektronik
@endsection

@section('content')
<!-- Breadcrumb Start -->
<div class="breadcrumb-wrap">
    <div class="container-fluid">
        <ul class="breadcrumb">
            <li class="breadcrumb-item"><a href="#">Beranda</a></li>
            <li class="breadcrumb-item"><a href="#">Keranjang</a></li>
            <li class="breadcrumb-item active">Checkout</li>
        </ul>
    </div>
</div>
<!-- Breadcrumb End -->

<!-- Checkout Start -->
<div class="checkout">
    <div class="container-fluid">
        <form action="{{ route('ecommerce.orders.store') }}" method="post">
            @csrf
            <div class="row">
                <div class="col-lg-7">
                    <div class="checkout-inner">
                        <div class="billing-address">
                            <h2>Alamat Pengiriman</h2>
                            <div class="row">
                                <div class="col-md-6">
                                    <input type="hidden" name="base_price" value="{{ $totalCalculate['totalPrice'] }}">
                                    <input type="hidden" name="shipping_cost" value="">
                                    <input type="hidden" name="total_price" value="">
                                    @foreach($carts as $cart)
                                    <input type="hidden" name="cart_id[]" value="{{ simple_encrypt($cart->id) }}">
                                    @endforeach
                                    <label>Nama Depan</label>
                                    <input class="form-control" type="text" name="first_name" value="{{ auth()->user()->first_name }}">
                                </div>
                                <div class="col-md-6">
                                    <label>Nama Belakang</label>
                                    <input class="form-control" type="text" name="last_name" value="{{ auth()->user()->last_name ?? "" }}">
                                </div>
                                <div class="col-md-12">
                                    <label>No. Handphone</label>
                                    <input class="form-control" type="text" name="phone" value="{{ auth()->user()->phone }}">
                                </div>
                                <div class="col-md-6">
                                    <select name="province_id" id="province_id" class="form-control @error('province_id') is-invalid @enderror">
                                        @forelse ($provinces as $row)
                                            <option value="{{ $row->id }}" {{ ($row->id == auth()->user()->addresses->province_id) ? "selected":"" }}>{{ $row->name }}</option>
                                        @empty
                                            <option disabled> -- Data Provinces Not Found --</option>
                                        @endforelse
                                    </select>
                                    <div class="invalid-feedback mb-3">
                                        {{ $errors->has('province_id') ? $errors->first('province_id') : "" }}
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <select name="city_id" id="city_id" class="form-control  @error('city_id') is-invalid @enderror">
                                        @forelse ($cities as $row)
                                            <option value="{{ $row->id }}" {{ ($row->id == auth()->user()->addresses->city_id) ? "selected":"" }}>{{ $row->name }}</option>
                                        @empty
                                            <option disabled> -- Data Provinces Not Found --</option>
                                        @endforelse
                                    </select>
                                    <div class="invalid-feedback mb-3">
                                        {{ $errors->has('city_id') ? $errors->first('city_id') : "" }}
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <input class="form-control @error('postcode') is-invalid @enderror" type="text" name="postcode" value="11480" placeholder="Kode Pos" required>
                                    <div class="invalid-feedback mb-3">
                                        {{ $errors->has('postcode') ? $errors->first('postcode') : "" }}
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <textarea name="street" class="form-control" rows="5" cols="30" required>{{ auth()->user()->addresses->street }}</textarea>
                                    <div class="invalid-feedback mb-3">
                                        {{ $errors->has('email') ? $errors->first('email') : "" }}
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <label>Kurir</label>
                                    <select name="shipping_courier" id="shipping_courier" class="form-control">
                                        <option value="" selected disabled>Pilih Kurir</option>
                                        <option value="jne">JNE</option>
                                        <option value="pos">POS</option>
                                        <option value="tiki">TIKI</option>
                                    </select>
                                </div>
                                <div class="col-md-12">
                                    <label>Layanan Kurir</label>
                                    <select name="shipping_service" id="shipping_service" class="form-control" disabled>
                                        <option value="" selected>Pilih Layanan Kurir</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-5">
                    <div class="checkout-inner">
                        <div class="checkout-summary">
                            <h1>Total Keranjang</h1>
                            <p class="sub-total" data-weight="{{ $totalCalculate['totalWeight'] }}">Sub Total<span>{{ convert_to_rupiah($totalCalculate['totalPrice']) }}</span></p>
                            <p class="ship-cost">Ongkos Kirim ( {{ convert_to_kilogram($totalCalculate['totalWeight']) }} ) <span name="shipping_cost">Rp. 0</span></p>
                            <h2>Grand Total<span name="total_price">Rp. 0</span></h2>
                        </div>
                        <div class="checkout-payment">
                            <div class="checkout-btn">
                                <button id="btn-order">Pesan</button>
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

        $('#province_id').on('change', function(e) {
            e.preventDefault();
            let id = $(this).val();
            $('#shipping_courier').val("");
            $('#shipping_courier').prop('disabled', true);

            $('#shipping_service').empty();
            $('#shipping_service').prop('disabled', true);
            $('#shipping_service').append('<option selected disabled>Pilih Layanan Kurir</option>');

            $.ajax({
                url: "{{ url('/api/get-city') }}",
                method: "GET",
                dataType: "JSON",
                data: { id:id },
                success: function(res)
                {
                    if(res)
                    {
                        $('#city_id').empty();
                        $('#city_id').append('<option selected disabled>Pilih Kota</option>');
                        $('#city_id').prop('disabled', false);

                        $.each(res, function(key, item) {
                            $('#city_id').append('<option value="'+item.id+'">'+item.name+'</option>')
                        })
                    }
                }
            });
        });

        $('#city_id').on('change', function(e) {
            e.preventDefault();

            $('#shipping_courier').prop('disabled', false);
        });

        $('#shipping_courier').on('change', function(e) {
            e.preventDefault();
            let courier = $(this).val();
            let city = $('#city_id option:selected').val();
            let weight = $('p.sub-total').data('weight');

            $.ajax({
                url: '/api/get-shipping-cost',
                method: 'GET',
                dataType: 'JSON',
                data: { courier:courier, city:city, weight:weight },
                success: function(res)
                {
                    $('#shipping_service').empty();
                    $('#shipping_service').append('<option selected disabled>Pilih Layanan Kurir</option>');
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
            if ((statusCourier == 1 && statusService == 1))
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
