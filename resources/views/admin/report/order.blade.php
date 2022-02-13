@extends('layouts.app')

@section('css')
<link href="https://cdn.datatables.net/1.10.16/css/jquery.dataTables.min.css" rel="stylesheet">
<link href="https://cdn.datatables.net/1.10.19/css/dataTables.bootstrap4.min.css" rel="stylesheet">
<link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css" />

<style>
    input[name="dates"] {
        text-align: center;
    }
</style>
@endsection

@section('title')
    {{ $title }}
@endsection

@section('content')
    <section class="section">
        <div class="section-header">
            <h3 class="page__heading">Laporan Order</h3>
            <div class="section-header-breadcrumb">
                <div class="breadcrumb-item active"><a href="#">Dashboard</a></div>
                <div class="breadcrumb-item"><a href="#">Laporan</a></div>
                <div class="breadcrumb-item">Order</div>
            </div>
        </div>
        <div class="row">
            <div class="col-12">
                <div class="card mb-0">
                    <div class="card-body">
                        <div class="form-group row mb-0">
                            <label class="col-sm-1 text-right col-form-label">Filter : </label>
                            <div class="col-sm-3">
                                <input type="text" class="form-control" name="dates" value="">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="section-body mt-4">
            <div class="row">
                <div class="col-lg-12">
                    <div class="card">
                        <div class="card-header">
                            <h4 class="page__heading">Daftar Order</h4>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-bordered table-hovered table-striped table-md text-center" id="data-table">
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>Order ID</th>
                                            <th>Nama Pembeli</th>
                                            <th>Tanggal dan Waktu</th>
                                            <th>Total Pembayaran</th>
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
    </section>
@endsection

@section('page_js')
<script src="{{ asset('vendor/lightbox2/dist/js/lightbox.js') }}"></script>
@endsection

@section('scripts')
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.0/jquery.validate.js"></script>
<script src="https://cdn.datatables.net/1.10.16/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.10.19/js/dataTables.bootstrap4.min.js"></script>
<script type="text/javascript" src="https://cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>
<script type="text/javascript" src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>
<script>
    lightbox.option({
        'disableScrolling': true,
        'showImageNumberLabel': false,
        'alwaysShowNavOnTouchDevices': false
    })
</script>
<script>
$(document).ready(function() {
    let start_date;
    let end_date;

    $('.modal-dialog').css({
        'top' : '20%'
    });

    $('input[name="dates"]').daterangepicker({
        opens: 'left',
        locale: {
            format: 'DD-MM-YYYY'
        },
    }, function(start, end, label) {
        start_date = start.format('YYYY-MM-DD');
        end_date = end.format('YYYY-MM-DD');

        table.ajax.reload();
    });

    $(document).on('click', 'a.nav-link', function() {
        filter = $(this).data('filter');
        $('.link-item').find('a.nav-link.active').removeClass('active');
        $(this).addClass('active');

        table.ajax.reload();
    })

    var table = $('#data-table').DataTable({
        responsive: true,
        processing: true,
        serverSide: true,
        ajax:  {
            url: "{{ route('admin.report.orders') }}",
            type: "GET",
            data: function(d) {
                d.start_date = start_date;
                d.end_date = end_date;
            }
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
                width: "5%"
            },
            {
                data: 'name',
                name: 'name',
                width: "9%",
                searchable: false
            },
            {
                data: 'order_date',
                name: 'order_date',
                width: "10%",
                searchable: false
            },
            {
                data: 'total_price',
                name: 'total_price',
                width: "5%",
                searchable: false
            },
            {
                data: 'status',
                name: 'status',
                width: "5%",
                align: "center",
                orderable: false,
                searchable: false
            },
            {
                data: 'action',
                name: 'action',
                width: "5%",
                align: "center",
                orderable: false,
                searchable: false
            }
        ]
    });

});
</script>
@endsection
