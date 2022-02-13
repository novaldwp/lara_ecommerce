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
                        <a class="nav-link active" href="#">All <span class="badge badge-white" name="all">{{ $count['all'] }}</span></a>
                        </li>
                        <li class="nav-item">
                        <a class="nav-link" href="javascript:void(0);" data-filter="4">Pending <span class="badge badge-primary" name="pending">{{ $count['pending'] }}</span></a>
                        </li>
                        <li class="nav-item">
                        <a class="nav-link" href="javascript:void(0);" data-filter="3">Diterima <span class="badge badge-primary" name="received">{{ $count['received'] }}</span></a>
                        </li>
                        <li class="nav-item">
                        <a class="nav-link" href="javascript:void(0);" data-filter="2">Dikirim <span class="badge badge-primary" name="delivered">{{ $count['delivered'] }}</span></a>
                        </li>
                        <li class="nav-item">
                        <a class="nav-link" href="javascript:void(0);" data-filter="1">Selesai <span class="badge badge-primary" name="completed">{{ $count['completed'] }}</span></a>
                        </li>
                        <li class="nav-item">
                        <a class="nav-link" href="javascript:void(0);" data-filter="0"> Dibatalkan <span class="badge badge-primary" name="canceled">{{ $count['canceled'] }}</span></a>
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
                            <h4 class="page__heading">Daftar Order</h4>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-bordered table-hovered table-striped table-md text-center" id="data-table">
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>Kode Order</th>
                                            <th>Tanggal Order</th>
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

    @include('admin.order._modal')
@endsection

@section('page_js')
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.0/jquery.validate.js"></script>
<script src="https://cdn.datatables.net/1.10.16/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.10.19/js/dataTables.bootstrap4.min.js"></script>
<script src="{{ asset('vendor/lightbox2/dist/js/lightbox.js') }}"></script>
@endsection

@include('admin.order._script')
