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
                            <li class="nav-item">
                                <a class="nav-link" href="javascript:void(0);" data-filter="3">{{ getStatus(1) }} <span class="badge badge-primary" name="active">{{ $countStatus['active'] }}</span></a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="javascript:void(0);" data-filter="4">{{ getStatus(0) }} <span class="badge badge-primary" name="nonactive">{{ $countStatus['nonactive'] }}</span></a>
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
                            <h4 class="page__heading">Daftar Data Latih</h4>
                                <a href="{{ route('admin.analyst.data-trainings.create') }}" class="btn btn-success">Tambah Baru</a>
                                &nbsp;
                                <a href="#" class="btn btn-info" name="fake-import">Import</a>
                                &nbsp;
                                <input type="file" name="import" id="" hidden>
                                <a href="{{ route('admin.analyst.data-trainings.export') }}" class="btn btn-primary">Export</a>
                        </div>
                        <div class="card-body">
                            @include('admin._partial.session')
                            <div class="table-responsive">
                                <table class="table table-bordered table-hovered table-striped table-md" id="data-table">
                                    <thead>
                                        <tr>
                                        <th width="4%">#</th>
                                        <th>Komentar</th>
                                        <th>Kelas</th>
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
            url: "{{ route('admin.analyst.data-trainings.index') }}",
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
                data: 'comment',
                name: 'comment',
            },
            {
                data: 'class',
                name: 'class',
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
        let training_id = $(this).data('training');

        swal({
            title: "Apakah Kamu yakin?",
            text: "Kata positif ini akan di non-aktifkan",
            icon: "warning",
            buttons: true,
            dangerMode: true,
            })
            .then((willDelete) => {
            if (willDelete) {
                $.ajax({
                    url: 'data-trainings/'+training_id,
                    type: 'DELETE',
                    dataType: 'JSON',
                    success: function(res, textStatus)
                    {
                        if (textStatus == "success")
                        {
                            updateSpanText(res);
                        }

                        table.ajax.reload();
                        swal(res.message, {
                            icon: textStatus,
                        });
                    },
                    error:function(xhr, textStatus)
                    {
                        if (textStatus == "error")
                        {
                            let res = JSON.parse(xhr.responseText);

                            swal(res.message, {
                                icon: textStatus,
                            });
                        }
                    }
                });
            }
        });
    });

    $(document).on('click', '#restoreButton', function(e) {
        e.preventDefault();
        let training_id = $(this).data('training');

        swal({
            title: "Apakah Kamu yakin?",
            text: "Kata positif ini akan diaktifkan",
            icon: "warning",
            buttons: true,
            dangerMode: true,
            })
            .then((willDelete) => {
            if (willDelete) {
                $.ajax({
                    url: 'data-trainings/restore/'+training_id,
                    type: 'GET',
                    dataType: 'JSON',
                    success: function(res, textStatus)
                    {
                        if (textStatus == "success")
                        {
                            updateSpanText(res);
                        }

                        table.ajax.reload();
                        swal(res.message, {
                            icon: textStatus,
                        });
                    },
                    error:function(xhr, textStatus)
                    {
                        if (textStatus == "error")
                        {
                            let res = JSON.parse(xhr.responseText);

                            swal(res.message, {
                                icon: textStatus,
                            });
                        }
                    }
                });
            }
        });
    });

    $('a[name="fake-import"]').on('click', function(e) {
        e.preventDefault();
        $('input[name="import"]').click();
    });

    $('input[name="import"]').on('change', function(e) {
        e.preventDefault();

        let file = $(this).prop("files")[0];
        let myFormData = new FormData();
        myFormData.append("file", file);

        if (file)
        {
            swal({
                title: "Apakah Kamu yakin?",
                text: "Pastikan File Yang Ingin Di Import Sudah Benar",
                icon: "warning",
                buttons: true,
                dangerMode: true,
                })
                .then((willImport) => {
                if (willImport) {
                    $.ajax({
                        url: 'data-trainings/import',
                        type: 'POST',
                        dataType: 'JSON',
                        processData: false,
                        contentType: false,
                        cache: false,
                        enctype: 'multipart/form-data',
                        data: myFormData,
                        success: function(res, textStatus)
                        {
                            if (textStatus == "success")
                            {
                                updateSpanText(res);
                            }

                            table.ajax.reload();
                            swal(res.message, {
                                icon: textStatus,
                            });
                            console.log(res);
                        },
                        error: function(xhr, textStatus)
                        {
                            if (textStatus == "error")
                            {
                                let res = JSON.parse(xhr.responseText);
                                swal("Error", res.errors.file[0], {
                                    icon: textStatus,
                                });
                            }
                        }
                    })
                }
            });
        }

        $(this).val('');
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
