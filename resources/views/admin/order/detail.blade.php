@extends('layouts.app')

@section('css')
<link href="https://cdn.datatables.net/1.10.16/css/jquery.dataTables.min.css" rel="stylesheet">
<link href="https://cdn.datatables.net/1.10.19/css/dataTables.bootstrap4.min.css" rel="stylesheet">
@endsection

@section('title')
    {{ $title }}
@endsection

@section('content')
    <section class="section">
        @include('admin.order._breadcrumb')

        <div class="section-body">
            <div class="invoice">
                <div class="invoice-print">
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="invoice-title">
                                <h2>Invoice</h2>
                                <div class="invoice-number">
                                    Order #{{ $order->code }}
                                    <button class="btn btn-warning btn-icon icon-left" title="Print">
                                        <i class="fas fa-print"></i>
                                    </button>
                                </div>
                            </div>
                            <hr>
                            <div class="row">
                                <div class="col-md-4">
                                    <address>
                                    <strong>Informasi Tagihan:</strong><br>
                                        <strong> Atas Nama : </strong> {{ $order->users->first_name. ' ' . $order->users->last_name }} <br>
                                        <strong> E-mail : </strong> {{ $order->users->email }} <br>
                                        <strong> No. Handphone : </strong> {{ $order->users->phone }}
                                    </address>
                                </div>
                                <div class="col-md-4 text-md-center">
                                    <address>
                                        <strong>Informasi Pengiriman:</strong> <br>
                                        <strong> Nama Penerima : </strong> {{ $order->first_name . ' '. $order->last_name }} <br/>
                                        <strong> Alamat : </strong> {{ $order->street }} <br>
                                        {{ $order->provinces->name }},
                                        {{ $order->cities->name }},
                                        {{ $order->postcode }} <br>
                                        <strong> Layanan Kurir : </strong> {{ strtoupper($order->shipping_courier.' - '.$order->shipping_service) }} <br>
                                        <strong> No. Resi : </strong> {{ $order->airway_bill ?? "-" }}
                                    </address>
                                </div>
                                <div class="col-md-4 text-md-right">
                                    <address>
                                    <strong>Informasi Order:</strong><br>
                                    <strong> Tanggal Order : </strong> {{ $order->order_date }} <br/>
                                    <strong> Status : </strong> {!! getOrderStatusMember($order->status) !!}
                                    </address>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="section-title">
                                Daftar Produk
                            </div>
                            <div class="table-responsive">
                                <table class="table table-striped table-hover table-md">
                                    <thead>
                                        <tr>
                                            <th class="text-center">#</th>
                                            <th>Nama Produk</th>
                                            <th class="text-center">Harga</th>
                                            <th class="text-center">Jumlah</th>
                                            <th class="text-right">Sub Total</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @php $i = 1; @endphp
                                        @foreach($order->orderproducts as $item)
                                            <tr>
                                                <td class="text-center">{{ $i++ }}</td>
                                                <td width="50%">{{ $item->products->name }}</td>
                                                <td class="text-center">{{ convert_to_rupiah($item->products->price) }}</td>
                                                <td class="text-center">{{ $item->amount }}</td>
                                                <td class="text-right">{{ convert_to_rupiah($item->sub_total) }}</td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                    <tfoot>
                                        <tr>
                                            <td class="text-right" colspan="4">Ongkos Kirim</td>
                                            <td class="text-right">{{ convert_to_rupiah($order->shipping_cost) }}</td>
                                        </tr>
                                        <tr>
                                            <td class="text-right" colspan="4">TOTAL</td>
                                            <td class="text-right">{{ convert_to_rupiah($order->total_price) }}</td>
                                        </tr>
                                    </tfoot>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
                <hr>
                <div class="text-md-right">
                    <a href="{{ route('admin.orders.index') }}" class="btn btn-default btn-icon icon-left">
                        <i class="fas fa-arrow-left"></i>
                        Kembali
                    </a>
                </div>
            </div>
        </div>
    </section>
@endsection
