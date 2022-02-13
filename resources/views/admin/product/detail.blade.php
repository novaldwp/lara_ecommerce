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
                            <h4>Detail Produk</h4>
                            <div class="card-header-action">
                                <a href="{{ route('admin.products.index') }}"><i class="fas fa-chevron-left"></i> Kembali</a>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-3">
                                    <ul class="nav nav-pills flex-column" id="myTab4" role="tablist">
                                    <li class="nav-item">
                                        <a class="nav-link active" id="profile-tab" data-toggle="tab" href="#profile" role="tab" aria-controls="profile" aria-selected="true">Informasi Produk</a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link" id="order-tab" data-toggle="tab" href="#order" role="tab" aria-controls="order" aria-selected="false">Ulasan Produk</a>
                                    </li>
                                    </ul>
                                </div>
                                <div class="col-md-9">
                                    <div class="tab-content no-padding" id="myTab2Content">
                                        <div class="tab-pane fade show active" id="profile" role="tabpanel" aria-labelledby="profile-tab">
                                            <div class="col-12 col-md-12 col-lg-12">
                                                <div class="row">
                                                    <div class="form-group col-md-9 col-12">
                                                    <label>Nama Produk</label>
                                                    <input type="hidden" name="product_id" value="{{ simple_encrypt($product->id) ?? "" }}">
                                                    <input type="text" class="form-control" value="{{ $product->name ?? "" }}" readonly>
                                                    </div>
                                                    <div class="form-group col-md-3 col-12">
                                                    <label>Harga</label>
                                                    <input type="text" class="form-control" value="{{ convert_to_rupiah($product->price) ?? "" }}" readonly>
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="form-group col-md-6 col-12">
                                                    <label>Berat</label>
                                                    <input type="email" class="form-control" value="{{ $product->weight ?? "empty" }}" readonly>
                                                    </div>
                                                    <div class="form-group col-md-6 col-12">
                                                    <label>Stok</label>
                                                    <input type="tel" class="form-control" value="{{ $product->stock ?? "empty" }}" readonly>
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="form-group col-md-8 col-12">
                                                    <label>Kategori</label>
                                                    <input type="text" class="form-control" value="{{ $product->categories->name ?? "empty" }}" readonly>
                                                    </div>
                                                    <div class="form-group col-md-4 col-12">
                                                    <label>Merk</label>
                                                    <input type="text" class="form-control" value="{{ $product->brands->name ?? "empty" }}" readonly>
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="form-group col-md-12 col-12">
                                                        <label>Deskripsi</label>
                                                        <textarea name="" id="" cols="30" rows="10" class="form-control" style="height: 120px;" readonly>{{ strip_tags($product->description)  ?? "empty" }}</textarea>
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="form-group col-md-12 col-12">
                                                        <label>Spesifikasi</label>
                                                        <textarea name="" id="" cols="30" rows="10" class="form-control" style="height: 120px;" readonly>{{ strip_tags($product->specification) ?? "empty" }}</textarea>
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="form-group col-md-6 col-12">
                                                    <label>Produk Unggulan</label>
                                                    <input type="text" class="form-control" value="{{ getProductStatusUnggulan($product->is_featured) ?? "empty" }}" readonly>
                                                    </div>
                                                    <div class="form-group col-md-6 col-12">
                                                    <label>Status</label>
                                                    <input type="text" class="form-control" value="{{ getProductStatus($product->status) ?? "empty" }}" readonly>
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
                                                            <th>Nama Pelanggan</th>
                                                            <th>Ulasan</th>
                                                            <th>Rating</th>
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

@section('page_css')
<link href="{{ asset('css/backend/product.css') }}" rel="stylesheet" type="text/css"/>
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
    });
    let product_id = $('input[name="product_id"]').val();
    let url = '{{ route("admin.reviews.product", ":id") }}';
    url = url.replace(":id", product_id);

    var table = $('#data-table').DataTable({
        responsive: true,
        processing: true,
        serverSide: true,
        ajax:  {
            url: url,
            type: "GET"
        },
        columns: [
            {
                data: 'DT_RowIndex',
                name: 'DT_RowIndex',
                width: '1%'
            },
            {
                data: 'name',
                name: 'name',
                width: '20%',
            },
            {
                data: 'message',
                name: 'message',
                width: '50%',
                orderable: false,
            },
            {
                data: 'rating',
                name: 'rating',
                width: '5%',
                searchable: false
            },
        ]
    });
</script>
@endsection
