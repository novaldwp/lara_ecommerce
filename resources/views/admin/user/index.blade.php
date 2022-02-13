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
                            <h4 class="page__heading">Daftar Petugas</h4>
                            <a href="{{ route('admin.admins.create') }}" class="btn btn-success">Tambah Petugas</a>
                        </div>
                        <div class="card-body">
                            @if (session('success'))
                                <div class="alert alert-success alert-dismissible fade show" role="alert">{{ session('success') }}</div>
                            @elseif (session('error'))
                                <div class="alert alert-error alert-dismissible fade show" role="alert">{{ session('error') }}</div>
                            @endif
                            <div class="table-responsive">
                                <table class="table table-bordered table-hovered table-striped table-md" id="data-table">
                                    <thead>
                                        <tr>
                                        <th width="4%">#</th>
                                        <th>Nama</th>
                                        <th>E-mail</th>
                                        <th>No. Handphone</th>
                                        <th>Alamat</th>
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
            url: "{{ route('admin.admins.index') }}",
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
                data: 'email',
                name: 'email',
                searchable: false
            },
            {
                data: 'phone',
                name: 'phone',
                searchable: false
            },
            {
                data: 'address',
                name: 'address',
                searchable: false
            },
        ]
    });
});
</script>
@endsection
