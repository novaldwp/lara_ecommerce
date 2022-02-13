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
                            <h4 class="page__heading">Daftar Kategori</h4>
                            <a href="{{ route('admin.categories.create') }}" class="btn btn-success">Tambah Baru</a>
                        </div>
                        <div class="card-body">
                            @include('admin._partial.session')
                            <div class="table-responsive">
                                <table class="table table-bordered table-hovered table-striped table-md" id="data-table">
                                    <thead>
                                        <tr>
                                        <th width="4%">#</th>
                                        <th>Nama</th>
                                        <th>Kategori Induk</th>
                                        <th>Slug</th>
                                        <th>Status</th>
                                        <th width="15%">Action</th>
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

@section('scripts')
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.0/jquery.validate.js"></script>
<script src="https://cdn.datatables.net/1.10.16/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.10.19/js/dataTables.bootstrap4.min.js"></script>
<script>
$(document).ready(function() {
    let filter;

    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    var table = $('#data-table').DataTable({
        responsive: true,
        processing: true,
        serverSide: true,
        ajax:  {
            url: "{{ route('admin.categories.index') }}",
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
            },
            {
                data: 'parent',
                name: 'parent',
                searchable: false
            },
            {
                data: 'slug',
                name: 'slug',
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
                width: "20%",
                align: "center",
                orderable: false,
                searchable: false
            },
        ]
    });

    $(document).on('click', 'a.nav-link', function() {
        filter = $(this).data('filter');
        $('.link-item').find('a.nav-link.active').removeClass('active');
        $(this).addClass('active');

        table.ajax.reload();
    })

    $(document).on('click', '#deleteButton', function(e) {
        e.preventDefault();
        let category_id = $(this).data('category');

        swal({
            title: "Apakah Kamu yakin?",
            text: "Kategori ini akan di non-aktifkan",
            icon: "warning",
            buttons: true,
            dangerMode: true,
            })
            .then((willDelete) => {
            if (willDelete) {
                $.ajax({
                    url: 'categories/'+category_id,
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
        let category_id = $(this).data('category');

        swal({
            title: "Apakah Kamu yakin?",
            text: "Kategori ini akan diaktifkan",
            icon: "warning",
            buttons: true,
            dangerMode: true,
            })
            .then((willDelete) => {
            if (willDelete) {
                $.ajax({
                    url: 'categories/restore/'+category_id,
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
        $('span[name="active"]').text(array.count.active);
        $('span[name="nonactive"]').text(array.count.nonactive);
    }
});
</script>
@endsection
