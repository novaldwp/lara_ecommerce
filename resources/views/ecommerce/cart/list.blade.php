@extends('layouts.front.app')

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
            <form action="{{ route('ecommerce.cart.checkout') }}" method="post">
                @csrf
                <div class="col-lg-8">
                    <div class="cart-page-inner">
                        <div class="table-responsive">
                            <table class="table table-bordered" id="list-cart">
                                <thead class="thead-dark">
                                    <tr>
                                        <th width="1%"></th>
                                        <th>Product</th>
                                        <th width="15%">Price</th>
                                        <th width="20%">Quantity</th>
                                        <th width="17%">Total</th>
                                        <th width="2%">Remove</th>
                                    </tr>
                                </thead>
                                <tbody class="align-middle" name="body-table" count="{{ $carts->count() }}">
                                    @forelse ($carts as $cart)
                                        <input type="hidden" name="id[]" value="{{ $cart->id }}">
                                        <tr class="order-{{ $cart->id }}">
                                            <td><input type="checkbox" name="select[]" value="{{ $cart->id }}"></td>
                                            <td>
                                                <div class="img">
                                                    <a href="{{ route('ecommerce.product.detail', [$cart->products->categories->parent->slug, $cart->products->categories->slug, $cart->products->slug]) }}">
                                                        <img src="{{ $cart->products->productimages->thumb.$cart->products->productimages->image1 }}" alt="Image">
                                                    </a>
                                                    <p>{{ $cart->products->name }}</p>
                                                </div>
                                            </td>
                                            <td>Rp. {{ number_format($cart->products->price, 0) }}</td>
                                            <td>
                                                <div class="quantity">
                                                    <a href="javascript:void(0);" id="minusCartQty" order="{{ $cart->id }}" class="btn" onclick="minusQty({{ $cart->id }});">
                                                        <i class="fa fa-minus"></i>
                                                    </a>
                                                    <input type="text" id="qty" order="{{ $cart->id }}" value="{{ $cart->amount }}" name="amount[]" readonly>
                                                    <a href="javascript:void(0);" id="plusCartQty" order="{{ $cart->id }}" class="btn" onclick="plusQty({{ $cart->id }})">
                                                        <i class="fa fa-plus"></i>
                                                    </a>
                                                </div>
                                            </td>
                                            <td><p id="total-price-{{ $cart->id }}"> Rp. {{ number_format($cart->products->price * $cart->amount, 0) }}</p></td>
                                            <td>
                                                <a href="javascript:void(0);" class="btn" id="delete" order="{{ $cart->id }}"><i class="fa fa-trash"></i></a>
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
                                        <h1>Cart Summary</h1>
                                        <p>Grand Total<span id="grandTotal"><strong>Rp. 0</strong></span></p>
                                    </div>
                                    <div class="cart-btn">
                                        <button name="action" value="checkout" id="cart-checkout" class="">Checkout</button>
                                        <hr>
                                        <p class="mt-3">
                                            Shipping fee will be calculated when checkout
                                        </p>
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
        let value = parseInt(document.querySelector('input[order="'+val+'"]').value, 10);

        value = isNaN(value) ? 0 : value;
        value++;
        document.querySelector('input[order="'+val+'"]').value = value;
    }

    function minusQty(val)
    {
        let value = parseInt(document.querySelector('input[order="'+val+'"]').value, 10);

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
                title: 'Are you sure?',
                text: "You won't be able to revert this!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, delete it!'
                }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: 'cart/delete/'+id,
                        method: 'delete',
                        dataType: 'JSON',
                        success: function(res)
                        {
                            $('tr.order-'+id).remove();
                            Swal.fire({
                                icon: 'success',
                                title: "Successfully",
                                text: 'Delete cart item',
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
                            else {
                                $('#cart-update').prop('disabled', false);
                            }
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
            let id = $(this).attr('order');

            $.ajax({
                url: "api/cart/updateCartPlusQty",
                method: "POST",
                dataType: "JSON",
                data: {id:id},
                success: function(res, textStatus, xhr)
                {
                    if (xhr.status == 200)
                    {
                        $(`p#total-price-${id}`).html();
                        $(`p#total-price-${id}`).text(res.total);
                        calculateTotal();
                    }
                }
            });
        });

        $('a#minusCartQty').on('click', function(e) {
            e.preventDefault();
            let id = $(this).attr('order');

            $.ajax({
                url: "api/cart/updateCartMinusQty",
                method: "POST",
                dataType: "JSON",
                data: {id:id},
                success: function(res, textStatus, xhr)
                {
                    if (xhr.status == 200)
                    {
                        $(`p#total-price-${id}`).html();
                        $(`p#total-price-${id}`).text(res.total);
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

            $.ajax({
                url: "api/cart/selected-item",
                method: "POST",
                dataType: "JSON",
                data: {array:arrSelected},
                success: function(res, textStatus, xhr)
                {
                    if (xhr.status == 200)
                    {
                        $('#grandTotal').html('<strong>Rp. '+res.grandTotal+'</strong>');
                    }
                }
            });
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
