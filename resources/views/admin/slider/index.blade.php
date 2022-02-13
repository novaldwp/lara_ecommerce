@extends('layouts.app')

@section('css')
<link href="https://cdn.datatables.net/1.10.16/css/jquery.dataTables.min.css" rel="stylesheet">
<link href="https://cdn.datatables.net/1.10.19/css/dataTables.bootstrap4.min.css" rel="stylesheet">
<link href="{{ asset('vendor/lightbox2/dist/css/lightbox.css') }}" rel="stylesheet" type="text/css"/>
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
                        <a class="nav-link" href="javascript:void(0);" data-filter="1">{{ getStatus(1) }} <span class="badge badge-primary" name="active">{{ $countStatus['active'] }}</span></a>
                        </li>
                        <li class="nav-item">
                        <a class="nav-link" href="javascript:void(0);" data-filter="2">{{ getStatus(0) }} <span class="badge badge-primary" name="nonactive">{{ $countStatus['nonactive'] }}</span></a>
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
                            <h4 class="page__heading">Daftar Slider</h4>
                            <a href="{{ route('admin.setting.sliders.create') }}" class="btn btn-success">Tambah Slider</a>
                        </div>
                        <div class="card-body">
                            @include('admin._partial.session')
                            <div class="table-responsive">
                                <table class="table table-bordered table-hovered table-striped table-md" id="data-table">
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>Nama Slider</th>
                                            <th>Deskripsi</th>
                                            <th>Gambar</th>
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

<script>
    lightbox.option({
        'disableScrolling': true,
        'showImageNumberLabel': false,
        'alwaysShowNavOnTouchDevices': false
    })
</script>
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
            url: "{{ route('admin.setting.sliders.index') }}",
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
                data: 'name',
                name: 'name',
                width: "20%"
            },
            {
                data: 'description',
                name: 'description',
                searchable: false
            },
            {
                data: 'images',
                name: 'images',
                width: "8%",
                searchable: false
            },
            {
                data: 'status',
                name: 'status',
                width: "5%",
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

    $(document).on('click', '#deleteButton', function(e) {
        e.preventDefault();
        let slider_id = $(this).data('slider');

        swal({
            title: "Apakah Kamu yakin?",
            text: "Slider ini akan di non-aktifkan",
            icon: "warning",
            buttons: true,
            dangerMode: true,
            })
            .then((willDelete) => {
            if (willDelete) {
                $.ajax({
                    url: 'sliders/'+slider_id,
                    type: 'DELETE',
                    dataType: 'JSON',
                    success: function(res)
                    {
                        updateSpanText(res);
                        table.ajax.reload();
                        swal(res.message, {
                            icon: res.status,
                        });
                    }
                });
            }
        });
    });

    $(document).on('click', '#restoreButton', function(e) {
        e.preventDefault();
        let slider_id = $(this).data('slider');

        swal({
            title: "Apakah Kamu Yakin?",
            text: "Slider ini akan diaktifkan",
            icon: "warning",
            buttons: true,
            dangerMode: true,
            })
            .then((willDelete) => {
            if (willDelete) {
                $.ajax({
                    url: 'sliders/restore/'+slider_id,
                    type: 'GET',
                    dataType: 'JSON',
                    success: function(res)
                    {
                        updateSpanText(res);
                        table.ajax.reload();
                        swal(res.message, {
                            icon: res.status,
                        });
                    }
                });
            }
        });
    });

    function updateSpanText(array)
    {
        $('span[name="all"]').text(array.count.all);
        $('span[name="featured"]').text(array.count.featured);
        $('span[name="active"]').text(array.count.active);
        $('span[name="nonactive"]').text(array.count.nonactive);
    }
});
</script>
@endsection
