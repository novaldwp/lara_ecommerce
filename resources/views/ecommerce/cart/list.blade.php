@extends('layouts.front.app')

@section('title')
    Keranjang | Toko Putra Elektronik
@endsection

@section('content')
<!-- Breadcrumb Start -->
<div class="breadcrumb-wrap">
    <div class="container-fluid">
        <ul class="breadcrumb">
            <li class="breadcrumb-item"><a href="#">Beranda</a></li>
            <li class="breadcrumb-item active">Keranjang</li>
        </ul>
    </div>
</div>
<!-- Breadcrumb End -->

<!-- Cart Start -->
<div class="cart-page">
    @if ($errors->has('weight'))
        <div class="container-fluid">
            <div class="row">
                <div class="col-lg-8">
                    <div class="cart-page-inner alert alert-danger">
                        <i class="fa fa-exclamation-triangle"></i>
                        {{ $errors->first('weight') }}
                    </div>
                </div>
            </div>
        </div>
    @endif
    <div class="container-fluid">
        <div class="row">
            <form action="{{ route('ecommerce.orders.checkout') }}" method="post">
                @csrf
                <div class="col-lg-8">
                    <div class="cart-page-inner">
                        <div class="table-responsive">
                            <table class="table table-bordered" id="list-cart">
                                <thead class="thead-dark">
                                    <tr>
                                        <th width="1%"></th>
                                        <th>Produk</th>
                                        <th width="15%">Harga</th>
                                        <th width="20%">Jumlah</th>
                                        <th width="17%">Total</th>
                                        <th width="2%">Remove</th>
                                    </tr>
                                </thead>
                                <tbody class="align-middle" name="body-table" count="{{ $carts->count() }}">
                                    @forelse ($carts as $cart)
                                        @php $cart_id = simple_encrypt($cart->id); @endphp
                                        <input type="hidden" name="id[]" value="{{ $cart->id }}">
                                        <tr class="order-{{ $cart_id }}">
                                            <td><input type="checkbox" name="select[]" value="{{ $cart_id }}"></td>
                                            <td>
                                                <div class="img">
                                                    <a href="{{ route('ecommerce.product.detail', [$cart->products->categories->parent->slug, $cart->products->categories->slug, $cart->products->slug]) }}">
                                                        <img src="{{ $cart->products->productimages->thumb.$cart->products->productimages->image1 }}" alt="Image">
                                                    </a>
                                                    <p>{{ $cart->products->name }}</p>
                                                </div>
                                            </td>
                                            <td>{{ convert_to_rupiah($cart->products->price) }}</td>
                                            <td>
                                                <div class="quantity">
                                                    <a href="javascript:void(0);" id="minusCartQty" order="{{ $cart_id }}" class="btn" onclick="minusQty('{{ $cart_id }}');">
                                                        <i class="fa fa-minus"></i>
                                                    </a>
                                                    <input type="text" id="qty" order="{{ $cart_id }}" value="{{ $cart->amount }}" name="amount[]" readonly>
                                                    <a href="javascript:void(0);" id="plusCartQty" order="{{ $cart_id }}" class="btn" onclick="plusQty('{{ $cart_id }}')">
                                                        <i class="fa fa-plus"></i>
                                                    </a>
                                                </div>
                                            </td>
                                            <td><p id="total-price-{{ $cart_id }}">{{ convert_to_rupiah($cart->products->price * $cart->amount) }}</p></td>
                                            <td>
                                                <a href="javascript:void(0);" class="btn" id="delete" order="{{ $cart_id }}"><i class="fa fa-trash"></i></a>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="7" class="text-center"><img src="{{ asset('uploads/images/cart_empty.jpg') }}" width="100%"></td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4">
                    <div class="cart-page-inner">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="cart-summary">
                                    <div class="cart-content">
                                        <h1>Ringkasan Keranjang</h1>
                                        <p>Total Berat<span id="totalWeight"><strong>0 Kg</strong></span></p>
                                        <p>Total Harga<span id="totalPrice"><strong>Rp. 0</strong></span></p>
                                    </div>
                                    <div class="cart-btn">
                                        <button name="action" value="checkout" id="cart-checkout" class="">Checkout</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
<!-- Cart End -->
@endsection

@section('scripts')
<script>
    function plusQty(val)
    {
        let value = parseInt(document.querySelector(`input[order="${val}"]`).value, 10);

        console.log(value);
        value = isNaN(value) ? 0 : value;
        value++;
        document.querySelector('input[order="'+val+'"]').value = value;
    }

    function minusQty(val)
    {
        let value = parseInt(document.querySelector(`input[order="${val}"]`).value, 10);

        if (value > 1)
        {
            value--;
            document.querySelector('input[order="'+val+'"]').value = value;
        }
    }

    $(document).ready(function() {
        $.ajaxSetup({
            headers: {
            'X-CSRF-TOKEN' : $('meta[name="csrf-token"]').attr('content')
            }
        });

        $('a#delete').on('click', function(e) {
            e.preventDefault();

            let id = $(this).attr('order');
            Swal.fire({
                title: 'Apakah Kamu yakin?',
                text: "Produk yang dipilih akan dihapus dari Keranjang",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Ya',
                cancelButtonText: 'Tidak'
                }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: 'carts/delete/'+id,
                        method: 'delete',
                        dataType: 'JSON',
                        success: function(res)
                        {
                            $('tr.order-'+id).remove();
                            Swal.fire({
                                icon: res.type,
                                title: res.title,
                                text: res.message,
                                showConfirmButton: false,
                                timer: 1500
                            });

                            if (res.count == 0)
                            {
                                let html = '';
                                    html += '<tr>';
                                    html += '<td colspan="5" class="text-center"><img src="{{ asset('uploads/images/cart_empty.jpg') }}" width="100%"></td>';
                                    html += '</tr>';

                                $('tbody[name="body-table"]').attr('count', 0);
                                $('.align-middle').html(html);
                                $('#cart-update').prop('disabled', true);
                            }

                            $('span#countShoppingCart').text(`(${res.count})`);
                        }
                    });
                }
            });
        });

        $('#cart-checkout').on('click', function(e) {
            if ($('input:checkbox:checked').length == 0)
            {
            e.preventDefault();
                Swal.fire({
                    icon: 'warning',
                    title: 'Can\'t Proceed',
                    text: 'Please select one of listed items to check out',
                    showConfirmButton: false,
                    timer: 1500
                });
            }
        })

        $('a#plusCartQty').on('click', function(e) {
            e.preventDefault();
            let cart_id = $(this).attr('order');

            $.ajax({
                url: "carts/increaseProductAmountByCartId/"+cart_id,
                method: "GET",
                dataType: "JSON",
                success: function(res, textStatus, xhr)
                {
                    if (xhr.status == 200)
                    {
                        $(`p#total-price-${cart_id}`).html();
                        $(`p#total-price-${cart_id}`).text(res.total);
                        calculateTotal();
                    }
                }
            });
        });

        $('a#minusCartQty').on('click', function(e) {
            e.preventDefault();
            let cart_id = $(this).attr('order');

            $.ajax({
                url: "carts/decreaseProductAmountByCartId/"+cart_id,
                method: "GET",
                dataType: "JSON",
                success: function(res, textStatus, xhr)
                {
                    if (xhr.status == 200)
                    {
                        $(`p#total-price-${cart_id}`).html();
                        $(`p#total-price-${cart_id}`).text(res.total);
                        calculateTotal();
                    }
                }
            });
        });

        $(':checkbox').change(function(){
            calculateTotal();
        })

        // CORE FUNCTION
        function calculateTotal()
        {
            var arrSelected = $(':checkbox:checked').map(function() {
                return this.value;
            }).get();

            if (arrSelected.length < 1 )
            {
                clearTextWeightPrice();

                return false;
            }

            $.ajax({
                url: "carts/selected-item",
                method: "POST",
                dataType: "JSON",
                data: {array:arrSelected},
                success: function(res)
                {
                    if (res.type == "success")
                    {
                        $('#totalWeight').html('<strong>'+res.totalWeight+'</strong>');
                        $('#totalPrice').html('<strong>'+res.totalPrice+'</strong>');
                    }
                    else {
                        Swal.fire({
                            icon: res.type,
                            title: res.title,
                            text: res.message,
                            showConfirmButton: false,
                            timer: 2000
                        });

                        $('input:checkbox').prop('checked', false);
                        clearTextWeightPrice()

                    }
                }
            });
        }

        function clearTextWeightPrice()
        {
            $('#totalPrice').html('<strong>Rp. 0</strong>');
            $('#totalWeight').html('<strong>0 Kg</strong>');
        }
    });
</script>
@endsection

@section('css')
<link rel="stylesheet" href="{{ asset('assets/front/css/custom.css') }}">
<style>

.quantity a {
    width: 30px;
    height: 30px;
    padding: 2px 0;
    font-size: 16px;
    text-align: center;
    color: #ffffff;
    background: #FF6F61;
    border: none;
    margin-bottom: 4px;
}

form {
    display: flex;
}
</style>
@endsection
