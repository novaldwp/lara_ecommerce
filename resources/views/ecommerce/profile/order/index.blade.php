@extends('layouts.front.app')

@section('css')
<link href="https://cdn.datatables.net/1.10.16/css/jquery.dataTables.min.css" rel="stylesheet">
<link href="https://cdn.datatables.net/1.10.19/css/dataTables.bootstrap4.min.css" rel="stylesheet">
@endsection

@section('title')
    Riwayat Order | Toko Putra Elektronik
@endsection

@section('content')
<!-- Breadcrumb Start -->
<div class="breadcrumb-wrap">
    <div class="container-fluid">
        <ul class="breadcrumb">
            <li class="breadcrumb-item"><a href="#">Beranda</a></li>
            <li class="breadcrumb-item active"><a href="#"> Akun Saya</a></li>
            <li class="breadcrumb-item">Riwayat Order</li>
        </ul>
    </div>
</div>
<!-- Breadcrumb End -->

<!-- My Account Start -->
<div class="my-account">
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-3">
                @include('layouts.front.profile.navbar')
            </div>
            <div class="col-md-9">
                <div class="tab-content">
                    <div class="table-responsive">
                        <table class="table table-bordered" id="data-table">
                            <thead class="thead-dark">
                                <tr>
                                    <th>No</th>
                                    <th>Order ID</th>
                                    <th>Tanggal dan Waktu</th>
                                    <th>Total Harga</th>
                                    <th>Status</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>

                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- My Account End -->
@endsection

@section('scripts')
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.0/jquery.validate.js"></script>
<script src="https://cdn.datatables.net/1.10.16/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.10.19/js/dataTables.bootstrap4.min.js"></script>

<script>
$(document).ready(function() {
    var table = $('#data-table').DataTable({
        processing: true,
        serverSide: true,
        ajax: "{{ route('ecommerce.profile.orders') }}",
        columns: [
            {
                data: 'DT_RowIndex',
                name: 'DT_RowIndex',
                width: "5%"
            },
            {
                data: 'code',
                name: 'code',
                width: "12%"
            },
            {
                data: 'order_date',
                name: 'order_date',
                width: "30%"
            },
            {
                data: 'total_price',
                name: 'total_price',
                width: "15%"
            },
            {
                data: 'status',
                name: 'status',
                width: "10%"
            },
            {
                data: 'action',
                name: 'action',
                width: "8%",
                orderable: false,
                searchable: false
            },
        ]
    });
});
</script>
@endsection
