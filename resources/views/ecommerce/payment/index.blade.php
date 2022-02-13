@extends('layouts.front.app')

@section('title')
    Pembayaran | Toko Putra Elektronik
@endsection

@section('content')
<!-- Breadcrumb Start -->
<div class="breadcrumb-wrap">
    <div class="container-fluid">
        <ul class="breadcrumb">
            <li class="breadcrumb-item"><a href="#">Beranda</a></li>
            <li class="breadcrumb-item"><a href="#">Keranjang</a></li>
            <li class="breadcrumb-item"><a href="#">Checkout</a></li>
            <li class="breadcrumb-item active">Pembayaran</li>
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
                    <p><strong>Name : </strong> {{ $dataOrder['order']->first_name.' '.$dataOrder['order']->last_name }}</p>
                    <p><strong>No. Handphone : </strong> {{ $dataOrder['order']->phone }}</p>
                    <p><strong>Alamat : </strong> {{ $dataOrder['order']->street.', '.$dataOrder['order']->cities->name.', '.$dataOrder['order']->provinces->name.', '.$dataOrder['order']->postcode }}</p>
                    <p><strong>Kurir : </strong> {{ strtoupper($dataOrder['order']->shipping_courier) }} - {{ $dataOrder['order']->shipping_service }} </p>
                </div>
                <div class="col-md-4 text-right">
                    <h2>Informasi Order</h2>
                    <p><strong>Order ID : </strong> #{{ $dataOrder['order']->code }}</p>
                    <p><strong>Tanggal : </strong> {{ date('d-m-Y H:i:s', strtotime($dataOrder['order']->order_date)) }}</p>
                </div>
                <div class="col-md-12">
                    <div class="table-responsive">
                        <table class="table table-bordered">
                            <thead class="thead-dark">
                                <tr>
                                    <th>#</th>
                                    <th>Produk</th>
                                    <th>Harga</th>
                                    <th>Jumlah</th>
                                    <th>Sub Total</th>
                                </tr>
                            </thead>
                            <tbody class="align-middle">
                                @php $ii = 1; @endphp
                                @for($i = 0; $i < count($dataOrder['listProducts']); $i++)
                                <tr>
                                    <td width="5%"> {{ $ii++ }} </td>
                                    <td width="40%"> {{ $dataOrder['listProducts'][$i]['name'] }} </td>
                                    <td> Rp. {{ number_format($dataOrder['listProducts'][$i]['price'], 0) }} </td>
                                    <td> {{ $dataOrder['listProducts'][$i]['quantity'] }} </td>
                                    <td class="text-right"> Rp. {{ number_format($dataOrder['listProducts'][$i]['sub_total'], 0) }} </td>
                                </tr>
                                @endfor
                            </tbody>
                            <tfoot>
                                <tr>
                                    <td colspan="4">Ongkos Kirim</td>
                                    <td class="text-right">Rp. {{ number_format($dataOrder['order']->shipping_cost, 0) }}</td>
                                </tr>
                                <tr>
                                    <td colspan="4">TOTAL HARGA</td>
                                    <td class="text-right">Rp. {{ number_format($dataOrder['order']->total_price, 0) }}</td>
                                </tr>
                            </tfoot>
                        </table>
                        <br/>
                        <div class="text-right">
                            <button id="pay-button" class="btn text-right">Proses Pembayaran</button>
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
      snap.pay('<?=$dataOrder['snapToken']?>'); // Replace it with your transaction token
    });
  </script>

@endsection
