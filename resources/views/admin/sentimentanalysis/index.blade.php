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
        @include('admin._partial.breadcrumb')
        <div class="row">
            <div class="col-12">
                <div class="card mb-0">
                    <div class="card-body link-item">
                    <ul class="nav nav-pills">
                        <li class="nav-item">
                            <a class="nav-link active" href="javascript:void(0);" data-filter="0">{{ getStatus(2) }} <span class="badge badge-white" name="all">{{ $countStatus['all'] }}</span></a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="javascript:void(0);" data-filter="1">{{ getStatus(5) }} <span class="badge badge-primary" name="positive">{{ $countStatus['positive'] }}</span></a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="javascript:void(0);" data-filter="2">{{ getStatus(6) }} <span class="badge badge-primary" name="negative">{{ $countStatus['negative'] }}</span></a>
                        </li>
                    </ul>
                    </div>
                </div>
            </div>
        </div>
        <div class="section-body mt-4">
            <div class="row">
                <div class="col-lg-12">
                    <div class="card">
                        <div class="card-header">
                            <h4 class="page__heading">Daftar Analisis Sentimen</h4>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-bordered table-hovered table-striped table-md" id="data-table">
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>Nama Produk</th>
                                            <th>Ulasan</th>
                                            <th>Status</th>
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

<script>
$(document).ready(function() {
    let filter;
    $('.modal-dialog').css({
        'top' : '20%'
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
            url: "{{ route('admin.analyst.sentiment-analyses.index') }}",
            type: "GET",
            data: function(d) {
                d.filter = filter;
            }
        },
        columns: [
            {
                data: 'DT_RowIndex',
                name: 'DT_RowIndex',
                width: "1%"
            },
            {
                data: 'product',
                name: 'product',
            },
            {
                data: 'review',
                name: 'review',
                searchable: false
            },
            {
                data: 'status',
                name: 'status',
                width: "10%",
                searchable: false
            }
        ]
    });

});
</script>
@endsection
