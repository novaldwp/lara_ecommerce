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

@section('content')
    <section class="section">
        <div class="section-header">
            <h3 class="page__heading">Laporan Produk</h3>
            <div class="section-header-breadcrumb">
                <div class="breadcrumb-item active"><a href="#">Dashboard</a></div>
                <div class="breadcrumb-item"><a href="#">Laporan</a></div>
                <div class="breadcrumb-item">Produk</div>
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
                            <h4 class="page__heading">Daftar Produk</h4>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-bordered table-hovered table-striped table-md text-center" id="data-table">
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>Nama Produk</th>
                                            <th>Kategori</th>
                                            <th>Harga</th>
                                            <th>Stok</th>
                                            <th>Terjual</th>
                                            <th>Gambar</th>
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
            url: "{{ route('admin.report.products') }}",
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
                data: 'product_name',
                name: 'product_name'
            },
            {
                data: 'category_name',
                name: 'category_name',
                searchable: false
            },
            {
                data: 'price',
                name: 'price',
                width: '12%',
                searchable: false
            },
            {
                data: 'stock',
                name: 'stock',
                searchable: false
            },
            {
                data: 'sum_product',
                name: 'sum_product',
                searchable: false
            },
            {
                data: 'images',
                name: 'images',
                width: "8%",
                orderable: false,
                searchable: false
            },
            {
                data: 'action',
                name: 'action',
                width: "18%",
                orderable: false,
                searchable: false
            },
        ]
    });

});
</script>
@endsection
