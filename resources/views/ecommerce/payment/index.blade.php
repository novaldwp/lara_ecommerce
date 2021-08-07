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

<!-- Wishlist Start -->
<div class="wishlist-page">
    <div class="container-fluid">
        <div class="wishlist-page-inner">
            <div class="row">
                <div class="col-md-4">
                    <h2>Billing Details</h2>
                    <p><strong>Name : </strong> {{ auth()->guard('members')->user()->first_name.' '.auth()->guard('members')->user()->last_name }}</p>
                    <p><strong>Phone : </strong> {{ auth()->guard('members')->user()->phone }}</p>
                    <p><strong>Email : </strong> {{ auth()->guard('members')->user()->email }}</p>
                </div>
                <div class="col-md-4">
                    <h2>Shipment Address</h2>
                    <p><strong>Name : </strong> {{ $order->first_name.' '.$order->last_name }}</p>
                    <p><strong>Phone : </strong> {{ $order->phone }}</p>
                    <p><strong>Address : </strong> {{ $order->addresses->street.', '.$order->addresses->cities->name.', '.$order->addresses->provinces->name.', '.$order->addresses->postcode }}</p>
                    <p><strong>Courier : </strong> {{ strtoupper($order->shipping_courier) }} ( {{ $order->shipping_service }} )</p>
                </div>
                <div class="col-md-4 text-right">
                    <h2>Order Summary</h2>
                    <p><strong>Order ID : </strong> #{{ $order->code }}</p>
                    <p><strong>Order Date : </strong> {{ date('d-m-Y H:i:s', strtotime($order->order_date)) }}</p>
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
                                @for($i = 0; $i < count($listProduct); $i++)
                                <tr>
                                    <td width="5%"> {{ $ii++ }} </td>
                                    <td width="40%"> {{ $listProduct[$i]['name'] }} </td>
                                    <td> Rp. {{ number_format($listProduct[$i]['price'], 0) }} </td>
                                    <td> {{ $listProduct[$i]['quantity'] }} </td>
                                    <td class="text-right"> Rp. {{ number_format($listProduct[$i]['sub_total'], 0) }} </td>
                                </tr>
                                @endfor
                            </tbody>
                            <tfoot>
                                <tr>
                                    <td colspan="4">Ongkos Kirim</td>
                                    <td class="text-right">Rp. {{ number_format($order->shipping_cost, 0) }}</td>
                                </tr>
                                <tr>
                                    <td colspan="4">TOTAL</td>
                                    <td class="text-right">Rp. {{ number_format($order->total_price, 0) }}</td>
                                </tr>
                            </tfoot>
                        </table>
                        <br/>
                        <div class="text-right">
                            <button id="pay-button" class="btn text-right">Proceed Payment</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- Wishlist End -->
@endsection

@section('scripts')
<script type="text/javascript"
src="https://app.sandbox.midtrans.com/snap/snap.js"
data-client-key="SB-Mid-client-HFq_mN4i6XNsysWJ"></script>
<!-- Note: replace with src="https://app.midtrans.com/snap/snap.js" for Production environment -->

<script type="text/javascript">
    var payButton = document.getElementById('pay-button');
    // For example trigger on button clicked, or any time you need
    payButton.addEventListener('click', function () {
      snap.pay('<?=$snapToken?>'); // Replace it with your transaction token
    });
  </script>

@endsection
