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
                <div class="col-lg-12">
                    <div class="card">
                        <div class="card-header">
                            <h4 class="page__heading">Daftar Pembeli</h4>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-bordered table-hovered table-striped table-md" id="data-table">
                                    <thead>
                                        <tr>
                                        <th width="4%">#</th>
                                        <th>Nama</th>
                                        <th>E-mail</th>
                                        <th>No. Handphone</th>
                                        <th>Total Order</th>
                                        <th>Tanggal Registrasi</th>
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
    var table = $('#data-table').DataTable({
        responsive: true,
        processing: true,
        serverSide: true,
        ajax:  {
            url: "{{ route('admin.report.customers') }}",
            type: "GET"
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
                data: 'user_email',
                name: 'user_email',
                searchable: false
            },
            {
                data: 'user_phone',
                name: 'user_phone',
                orderable: false,
                searchable: false
            },
            {
                data: 'count_order',
                name: 'count_order',
                searchable: false
            },
            {
                data: 'created_at',
                name: 'created_at',
                searchable: false
            },
            {
                data: 'action',
                name: 'action',
                width: "10%",
                align: "center",
                orderable: false,
                searchable: false
            },
        ]
    });
});
</script>
@endsection
