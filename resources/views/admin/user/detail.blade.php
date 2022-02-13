@extends('layouts.app')

@section('css')
<link href="https://cdn.datatables.net/1.10.16/css/jquery.dataTables.min.css" rel="stylesheet">
<link href="https://cdn.datatables.net/1.10.19/css/dataTables.bootstrap4.min.css" rel="stylesheet">
@endsection

@section('content')
    <section class="section">
        @include('admin._partial.breadcrumb')
        <div class="section-body">
            <div class="row">

                <div class="col-12 col-sm-7 col-lg-12">
                    <div class="card">
                        <div class="card-header">
                            <h4>Detail Pembeli</h4>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-12 col-sm-12 col-md-3">
                                    <ul class="nav nav-pills flex-column" id="myTab4" role="tablist">
                                    <li class="nav-item">
                                        <a class="nav-link active" id="profile-tab" data-toggle="tab" href="#profile" role="tab" aria-controls="profile" aria-selected="true">Informasi Pembeli</a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link" id="order-tab" data-toggle="tab" href="#order" role="tab" aria-controls="order" aria-selected="false">Informasi Order</a>
                                    </li>
                                    </ul>
                                </div>
                                <div class="col-12 col-sm-12 col-md-9">
                                    <div class="tab-content no-padding" id="myTab2Content">
                                        <div class="tab-pane fade show active" id="profile" role="tabpanel" aria-labelledby="profile-tab">
                                            <div class="col-12 col-md-12 col-lg-12">
                                                <div class="row">
                                                    <div class="form-group col-md-6 col-12">
                                                    <label>Nama Depan</label>
                                                    <input type="hidden" name="customer_id" value="{{ $customer->id }}">
                                                    <input type="text" class="form-control" value="{{ $customer->first_name ?? "" }}" readonly>
                                                    </div>
                                                    <div class="form-group col-md-6 col-12">
                                                    <label>Nama Belakang</label>
                                                    <input type="text" class="form-control" value="{{ $customer->last_name ?? "" }}" readonly>
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="form-group col-md-8 col-12">
                                                    <label>Email</label>
                                                    <input type="email" class="form-control" value="{{ $customer->email ?? "empty" }}" readonly>
                                                    </div>
                                                    <div class="form-group col-md-4 col-12">
                                                    <label>Phone</label>
                                                    <input type="tel" class="form-control" value="{{ $customer->phone ?? "empty" }}" readonly>
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="form-group col-md-6 col-12">
                                                    <label>Propinsi</label>
                                                    <input type="text" class="form-control" value="{{ $customer->addresses->provinces->name ?? "empty" }}" readonly>
                                                    </div>
                                                    <div class="form-group col-md-6 col-12">
                                                    <label>Kota</label>
                                                    <input type="text" class="form-control" value="{{ $customer->phone ?? "empty" }}" readonly>
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="form-group col-md-12 col-12">
                                                        <label>Alamat</label>
                                                        <input type="text" class="form-control" value="{{ $customer->addresses->street ?? "empty" }}" readonly>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="tab-pane fade" id="order" role="tabpanel" aria-labelledby="order-tab">
                                            <div class="table-responsive">
                                                <table class="table table-bordered table-hovered table-striped table-md" id="data-table" style="width:100%">
                                                    <thead>
                                                        <tr>
                                                            <th>#</th>
                                                            <th>Order #</th>
                                                            <th>Tanggal</th>
                                                            <th>Total Harga</th>
                                                            <th>Status</th>
                                                            <th>Action</th>
                                                        </tr>
                                                    </thead>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection

@section('scripts')
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.0/jquery.validate.js"></script>
<script src="https://cdn.datatables.net/1.10.16/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.10.19/js/dataTables.bootstrap4.min.js"></script>
<script>
$(document).ready(function() {
    let user_id = $('input[name="user_id"]').val();
    var table = $('#data-table').DataTable({
        responsive: true,
        processing: true,
        serverSide: true,
        ajax:  {
            url: '{{ route("admin.orders.customer", 1) }}',
            type: "GET"
        },
        columns: [
            {
                data: 'DT_RowIndex',
                name: 'DT_RowIndex',
                width: "1%"
            },
            {
                data: 'code',
                name: 'code',
            },
            {
                data: 'order_date',
                name: 'order_date',
            },
            {
                data: 'total_price',
                name: 'total_price',
                orderable: false,
                searchable: false
            },
            {
                data: 'status',
                name: 'status',
                width: '10%',
                orderable: false,
                searchable: false
            },
            {
                data: 'action',
                name: 'action',
                align: "center",
                width: '10%',
                orderable: false,
                searchable: false
            },
        ]
    });
});
</script>
@endsection

