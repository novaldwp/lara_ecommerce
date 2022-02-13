@extends('layouts.front.app')

@section('css')
<style>
    fieldset {
        position: center;
    }
    .rating input[type="radio"]:not(:nth-of-type(0)) {
        /* hide visually */
        border: 0;
        clip: rect(0 0 0 0);
        height: 10px;
        margin: -1px;
        overflow: hidden;
        padding: 0;
        position: absolute;
        width: 1px;
    }
    .rating [type="radio"]:not(:nth-of-type(0)) + label {
        display: none;
    }

    label[for]:hover {
        cursor: pointer;
    }

    .stars {
        align-content: center;
    }
    .rating .stars label:before {
        content: "★";
    }

    .stars label {
        color: lightgray;
        font-size: 35px;
    }

    .stars label:hover {
        text-shadow: 0 0 1px #000;
    }

    .rating [type="radio"]:nth-of-type(1):checked ~ .stars label:nth-of-type(-n+1),
    .rating [type="radio"]:nth-of-type(2):checked ~ .stars label:nth-of-type(-n+2),
    .rating [type="radio"]:nth-of-type(3):checked ~ .stars label:nth-of-type(-n+3),
    .rating [type="radio"]:nth-of-type(4):checked ~ .stars label:nth-of-type(-n+4),
    .rating [type="radio"]:nth-of-type(5):checked ~ .stars label:nth-of-type(-n+5) {
        color: orange;
    }

    .rating [type="radio"]:nth-of-type(1):focus ~ .stars label:nth-of-type(1),
    .rating [type="radio"]:nth-of-type(2):focus ~ .stars label:nth-of-type(2),
    .rating [type="radio"]:nth-of-type(3):focus ~ .stars label:nth-of-type(3),
    .rating [type="radio"]:nth-of-type(4):focus ~ .stars label:nth-of-type(4),
    .rating [type="radio"]:nth-of-type(5):focus ~ .stars label:nth-of-type(5) {
        color: darkorange;
    }
</style>
@endsection

@section('title')
    Order Detail | Toko Putra Elektronik
@endsection

@section('content')
<!-- Breadcrumb Start -->
<div class="breadcrumb-wrap">
    <div class="container-fluid">
        <ul class="breadcrumb">
            <li class="breadcrumb-item"><a href="#">Beranda</a></li>
            <li class="breadcrumb-item"><a href="#">Akun Saya</a></li>
            <li class="breadcrumb-item"><a href="{{ route('ecommerce.profile.orders') }}">Order</a></li>
            <li class="breadcrumb-item active">Detail</li>
        </ul>
    </div>
</div>
<!-- Breadcrumb End -->

<!-- Wishlist Start -->
<div class="wishlist-page">
    <div class="container-fluid">
        <div class="wishlist-page-inner">
            <div class="row">
                <div class="col-md-4">
                    <h2>Informasi Tagihan</h2>
                    <p><strong>Nama : </strong> {{ auth()->user()->first_name.' '.auth()->user()->last_name }}</p>
                    <p><strong>No. Handphone : </strong> {{ auth()->user()->phone }}</p>
                    <p><strong>Email : </strong> {{ auth()->user()->email }}</p>
                </div>
                <div class="col-md-4">
                    <h2>Alamat Pengiriman</h2>
                    <p><strong>Nama Penerima : </strong> {{ $order->first_name.' '.$order->last_name }}</p>
                    <p><strong>No. Handphone : </strong> {{ $order->phone }}</p>
                    <p><strong>Alamat : </strong> {{ $order->street.', '.$order->cities->name.', '.$order->provinces->name.', '.$order->postcode }}</p>
                    <p><strong>Kurir : </strong> {{ strtoupper($order->shipping_courier) }} ( {{ $order->shipping_service }} )</p>
                    @if ($order->status == 2)
                        <p><strong>No. Resi : </strong> {{ $order->airway_bill }} </p>
                    @endif
                </div>
                <div class="col-md-4 text-right">
                    <h2>Ringkasan Order</h2>
                    <p><strong>Invoice : </strong> #{{ $order->code }}</p>
                    <p><strong>Tanggal : </strong> {{ date('d-m-Y H:i:s', strtotime($order->order_date)) }}</p>
                    <p><strong>Status : </strong> {!! getOrderStatusMember($order->status) !!}</p>
                    @if ($order->status == 9)
                        <u><a href="{{ route('ecommerce.payment.detail', $order->code) }}">View Payment Details</a></u>
                    @else
                        <p><strong>Status Pembayaran : <span class="badge badge-success">Completed</span> </strong></p>
                    @endif
                </div>
                <div class="col-md-12">
                    <div class="table-responsive">
                        <table class="table table-bordered">
                            <thead class="thead-dark">
                                <tr>
                                    <th>#</th>
                                    <th>Product</th>
                                    <th>Price</th>
                                    <th>Quantity</th>
                                    <th>Sub Total</th>
                                </tr>
                            </thead>
                            <tbody class="align-middle">
                                @php $ii = 1; @endphp
                                @foreach($order->orderproducts as $item)
                                <tr>
                                    <td width="5%"> {{ $ii++ }} </td>
                                    <td width="40%">
                                        {{ $item->products->name }}
                                            @if ($order->status == 1)
                                                @if ($item->is_review == 0)
                                                    <a href="" id="reviewButton" data-product="{{ $item->product_id }}">[Beri Ulasan]</a>
                                                @endif
                                            @endif
                                    </td>
                                    <td> {{ convert_to_rupiah($item->products->price) }} </td>
                                    <td> {{ $item->amount }} </td>
                                    <td class="text-right"> {{ convert_to_rupiah($item->sub_total) }} </td>
                                </tr>
                                @endforeach
                            </tbody>
                            <tfoot>
                                <tr>
                                    <td colspan="4">Ongkos Kirim</td>
                                    <td class="text-right"> {{ convert_to_rupiah($order->shipping_cost) }}</td>
                                </tr>
                                <tr>
                                    <td colspan="4">TOTAL</td>
                                    <td class="text-right"> {{ convert_to_rupiah($order->total_price) }}</td>
                                </tr>
                            </tfoot>
                        </table>
                        <br/>
                        <div class="text-right">
                            <a href="{{ route('ecommerce.profile.orders') }}" class="btn">Back</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- Wishlist End -->

<!-- Modal -->
<div class="modal fade" id="reviewModal" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Tulis Ulasan Produk</h5>
            </div>
            <div class="modal-body">
                <form class="rating">
                    <input type="hidden" name="product_id">
                    <input type="hidden" name="order_id" value="{{ $order->id }}">
                    <fieldset class="rating">
                        <input id="rating-1" type="radio" name="rating" value="1">
                        <label for="rating-1">1 star</label>
                        <input id="rating-2" type="radio" name="rating" value="2">
                        <label for="rating-2">2 stars</label>
                        <input id="rating-3" type="radio" name="rating" value="3">
                        <label for="rating-3">3 stars</label>
                        <input id="rating-4" type="radio" name="rating" value="4">
                        <label for="rating-4">4 stars</label>
                        <input id="rating-5" type="radio" name="rating" value="5">
                        <label for="rating-5">5 stars</label>

                        <div class="stars text-center">
                            <label for="rating-1" aria-label="1 star" title="1 star"></label>
                            <label for="rating-2" aria-label="2 stars" title="2 stars"></label>
                            <label for="rating-3" aria-label="3 stars" title="3 stars"></label>
                            <label for="rating-4" aria-label="4 stars" title="4 stars"></label>
                            <label for="rating-5" aria-label="5 stars" title="5 stars"></label>
                        </div>
                    </fieldset>
                    <hr>
                    <textarea name="message" class="form-control" id="message" cols="50" rows="5"></textarea>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" id="reviewModalClose">Close</button>
                <button type="button" class="btn btn-primary" id="reviewModalSubmit">Save changes</button>
            </div>
        </div>
    </div>
</div>
<!-- Modal End-->
@endsection

@section('scripts')
<script>
    $(document).ready(function() {
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        $('.modal-dialog').css({
            top: '20%'
        });

        $(document).on('click', 'a#reviewButton', function(e) {
            e.preventDefault();

            $('#reviewModal').modal('show');
            $('input[name="rating"]').prop('checked', false);
            $('input[name="product_id"]').val($(this).data('product'));
            $('textarea[name="message"]').val('');
        });

        $(document).on('click', '#reviewModalClose', function(e) {
            e.preventDefault();

            $('#reviewModal').modal('hide');
        });

        $(document).on('click', '#reviewModalSubmit', function(e) {
            e.preventDefault();

            let order_id = $('input[name="order_id"]').val();
            let product_id = $('input[name="product_id"]').val();
            let rating = $('input[name="rating"]:checked').val();
            let message = $('textarea[name="message"]').val();

            if (rating == null) alert("Please Select Rating");
            if (message == "") alert("Please Input Message");

            $.ajax({
                url: "{{ route('ecommerce.reviews.create') }}",
                method: 'POST',
                dataType: 'JSON',
                data: {
                    order_id:order_id,
                    product_id:product_id,
                    rating:rating,
                    message:message
                },
                success: function(res) {
                    $('#reviewModal').modal('hide');
                    $('a[data-product="'+product_id+'"]').hide();
                    Swal.fire({
                        icon: res.type,
                        text: res.message,
                        showConfirmButton: false,
                        timer: 1500
                    });
                }
            });
        });

        var rating = document.querySelector('.rating');
            var handle = document.getElementById('toggle-rating');
            handle.onchange = function(event) {
                rating.classList.toggle('rating', handle.checked);
            };
    });
</script>
@endsection
