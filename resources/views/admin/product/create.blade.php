@extends('layouts.app')

@section('title')
    {{ $title }}
@endsection

@section('content')
    <section class="section">
        @include('admin._partial.breadcrumb')
        <div class="section-body">
            <div class="row">
                <div class="col-lg-12">
                    <div class="card">
                        <form class="needs-validation" method="POST" action="{{ route('admin.products.store') }}" novalidate="" enctype="multipart/form-data">
                            @csrf
                            <div class="card-header">
                                <h4>Tambah Produk</h4>
                            </div>
                            <div class="card-body">
                                @if (session('error'))
                                    <div class="alert alert-danger alert-dismissible fade show" role="alert">{{ session('error') }}</div>
                                @endif
                                <div class="form-group row">
                                    <label class="col-sm-2 text-right col-form-label">Nama Produk : </label>
                                    <div class="col-sm-9">
                                        <input type="text" class="form-control @error('name') is-invalid @enderror" name="name" value="{{ old('name') }}" required="" autofocus>
                                        <div class="invalid-feedback">
                                            {{ $errors->has('name') ? $errors->first('name'):"Please enter name of product" }}
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-sm-2 text-right col-form-label">Harga : </label>
                                    <div class="col-sm-9">
                                        <input type="text" class="form-control @error('price') is-invalid @enderror" name="price" value="{{ old('price') }}" oninput="this.value = this.value.replace(/[^0-9]/g, '').replace(/(\..*)\./g, '$1');" required="">
                                        <div class="invalid-feedback">
                                            {{ $errors->has('price') ? $errors->first('price'):"Please enter price of product" }}
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-sm-2 text-right col-form-label">Berat : </label>
                                    <div class="col-sm-9">
                                        <input type="text" class="form-control @error('price') is-invalid @enderror" name="weight" value="{{ old('weight') }}" oninput="this.value = this.value.replace(/[^0-9]/g, '').replace(/(\..*)\./g, '$1');" required="">
                                        <div class="invalid-feedback">
                                            {{ $errors->has('weight') ? $errors->first('weight'):"Please enter weight of product" }}
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-sm-2 text-right col-form-label">Stok : </label>
                                    <div class="col-sm-9">
                                        <input type="text" class="form-control @error('stock') is-invalid @enderror" name="stock" value="{{ old('stock') }}" oninput="this.value = this.value.replace(/[^0-9]/g, '').replace(/(\..*)\./g, '$1');" required="">
                                        <div class="invalid-feedback">
                                            {{ $errors->has('stock') ? $errors->first('stock'):"Please enter stock of product" }}
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-sm-2 text-right col-form-label">Kategori : </label>
                                    <div class="col-sm-9">
                                        <select name="category_id" id="category_id" title="Select Category of Product" class="select2 form-control @error('category_id') is-invalid @enderror" required="">
                                            <option value="" disabled selected>Pilih Kategori</option>
                                            @forelse($categories as $cat)
                                                @foreach($cat->child as $catt)
                                                    <option value="{{ $catt->id }}" {{ $catt->id == old('category_id') ? "selected" : ""}}>{{ $catt->name }}</option>
                                                @endforeach
                                            @empty
                                                <option value="addKategori">Tambah Kategori Baru</option>
                                            @endforelse
                                        </select>
                                        <div class="invalid-feedback">
                                            {{ $errors->has('category_id') ? $errors->first('category_id'):"Please select category of product" }}
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-sm-2 text-right col-form-label">Merk : </label>
                                    <div class="col-sm-9">
                                        <select name="brand_id" id="brand_id" title="Select Brand of Product" class="select2 form-control @error('brand_id') is-invalid @enderror">
                                            <option value="" selected>Tanpa Merk</option>
                                            @foreach($brands as $brand)
                                                <option value="{{ $brand->id }}" {{ $brand->id == old('brand_id') ? "selected" : ""}}>{{ $brand->name }}</option>
                                            @endforeach
                                        </select>
                                        <div class="invalid-feedback">
                                            {{ $errors->has('brand_id') ? $errors->first('brand_id'):"Please select brand of product" }}
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-sm-2 text-right col-form-label">Deskripsi : </label>
                                    <div class="col-sm-9">
                                        <textarea name="description" id="" cols="30" rows="10" class="summernote-simple form-control @error('description') is-invalid @enderror" required>{{ old('description') }}</textarea>
                                        <div class="invalid-feedback">
                                            {{ $errors->has('description') ? $errors->first('description'):"Please enter description of product" }}
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-sm-2 text-right col-form-label">Spesifikasi : </label>
                                    <div class="col-sm-9">
                                        <textarea name="specification" id="" cols="30" rows="10" class="summernote-simple form-control @error('description') is-invalid @enderror" required>{{ old('specification') }}</textarea>
                                        <div class="invalid-feedback">
                                            {{ $errors->has('specification') ? $errors->first('specification'):"Please enter specification of product" }}
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-sm-2 text-right col-form-label">Gambar : </label>
                                    <div class="col-sm-3">
                                        <div class="box-area {{ session()->has("error-image") ? "error":"" }}" id="custom-btn" onclick="image1Active()">
                                            <div class="image">
                                                <img name="image1">
                                            </div>
                                            <div class="box-area-icon">
                                                <a href="javscript:void(0);">
                                                    <i class="fa fa-image"></i>
                                                </a>
                                            </div>
                                            <header>Gambar Utama</header>

                                            <input type="file" name="image1" hidden>
                                        </div>
                                    </div>
                                    <div class="col-sm-3">
                                        <div class="box-area {{ session()->has("error-image") ? "error":"" }}" id="custom-btn" onclick="image2Active()">
                                            <div class="image">
                                                <img name="image2">
                                            </div>
                                            <div class="box-area-icon">
                                                <a href="javscript:void(0);">
                                                    <i class="fa fa-image"></i>
                                                </a>
                                            </div>
                                            <header>Gambar Tambahan</header>

                                            <input type="file" name="image2" hidden>
                                        </div>
                                    </div>
                                    <div class="col-sm-3">
                                        <div class="box-area {{ session()->has("error-image") ? "error":"" }}" id="custom-btn" onclick="image3Active()">
                                            <div class="image">
                                                <img name="image3">
                                            </div>
                                            <div class="box-area-icon">
                                                <a href="javscript:void(0);">
                                                    <i class="fa fa-image"></i>
                                                </a>
                                            </div>
                                            <header>Gambar Tambahan</header>

                                            <input type="file" name="image3" hidden>
                                        </div>
                                    </div>
                                    <div class="col-sm-3 offset-sm-2">
                                        <div class="box-area {{ session()->has("error-image") ? "error":"" }}" id="custom-btn" onclick="image4Active()">
                                            <div class="image">
                                                <img name="image4">
                                            </div>
                                            <div class="box-area-icon">
                                                <a href="javscript:void(0);">
                                                    <i class="fa fa-image"></i>
                                                </a>
                                            </div>
                                            <header>Gambar Tambahan</header>

                                            <input type="file" name="image4" hidden>
                                        </div>
                                    </div>
                                    <div class="col-sm-3">
                                        <div class="box-area {{ session()->has("error-image") ? "error":"" }}" id="custom-btn" onclick="image5Active()">
                                            <div class="image">
                                                <img name="image5">
                                            </div>
                                            <div class="box-area-icon">
                                                <a href="javscript:void(0);">
                                                    <i class="fa fa-image"></i>
                                                </a>
                                            </div>
                                            <header>Gambar Tambahan</header>

                                            <input type="file" name="image5" hidden>
                                        </div>
                                    </div>
                                    <div class="col-sm-3"></div>
                                    @if (session()->has('error-image'))
                                        <div class="col-sm-3 offset-sm-2 image-feedback">
                                            {{ session()->get('error-image') }}
                                        </div>
                                    @endif
                                </div>
                                <div class="form-group row">
                                    <label class="col-sm-2 text-right col-form-label">Featured : </label>
                                    <div class="col-sm-9">
                                        <select name="is_featured" id="is_featured" title="Select Status of Product" class="select2 form-control  @error('is_featured') is-invalid @enderror" required="">
                                            <option value="1" {{ old('is_featured') == "1" ? "selected" : "" }} selected>Yes</option>
                                            <option value="0" {{ old('is_featured') == "0" ? "selected" : "" }} >No</option>
                                        </select>
                                        <div class="invalid-feedback">
                                            {{ $errors->has('is_featured') ? $errors->first('is_featured'):"Please select featured of product" }}
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-sm-2 text-right col-form-label">Status : </label>
                                    <div class="col-sm-9">
                                        <select name="status" id="status" title="Select Status of Product" class="select2 form-control  @error('status') is-invalid @enderror" required="">
                                            <option value="1" {{ old('status') == "1" ? "selected" : "" }} selected>Active</option>
                                            <option value="0" {{ old('status') == "0" ? "selected" : "" }} >Not Active</option>
                                        </select>
                                        <div class="invalid-feedback">
                                            {{ $errors->has('status') ? $errors->first('status'):"Please select status of product" }}
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="card-footer offset-md-2 text-left">
                                <input type="submit" class="btn btn-primary" name="Simpan">
                                <a href="{{ route('admin.products.index') }}" class="btn btn-default">Kembali</a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>


    <!-- Modal -->
    <div class="modal fade" id="actionModal" aria-labelledby="modalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                <h5 class="modal-title" id="modalLabel">This is dummy text</h5>
                </div>
                <div class="modal-body">
                    <form>
                        <input type="hidden" name="order_id">
                        <input type="text" name="textModal" id="textModal" class="form-control" placeholder="This is dummy placeholder">
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" id="actionModalClose">Close</button>
                    <button type="button" class="btn btn-primary" name="actionModalSubmit" id="actionModalSubmit" data-action="this is dummy action" disabled>Save changes</button>
                </div>
            </div>
        </div>
    </div>
  <!-- End of Modal -->
@endsection

@section('css')
<style>
    label.custom-switch {
        padding-left: 15px;
    }
</style>
@endsection

@section('page_css')
<link href="{{ asset('css/backend/product.css') }}" rel="stylesheet" type="text/css"/>
<link href="{{ asset('assets/css/summernote.min.css') }}" rel="stylesheet" type="text/css"/>
<link href="http://cdnjs.cloudflare.com/ajax/libs/summernote/0.8.1/summernote.css" rel="stylesheet">
@endsection

@section('page_js')
<script src="{{ asset('js/backend/product.js') }}"></script>
<script src="{{ asset('assets/js/summernote.min.js') }}"></script>
@endsection

@section('scripts')
<script>
$(document).ready(function() {
    $('#category_id').on('change', function(e) {
        e.preventDefault();

        let val = $('#category_id option:selected').val();
        if(val == "addKategori")
        {

        }
    });

    $('.alert').alert();
});
</script>
@endsection
