@extends('layouts.app')

@section('content')
    <section class="section">
        <div class="section-header">
            <h3 class="page__heading">Manage Products</h3>
            <div class="section-header-breadcrumb">
                <div class="breadcrumb-item active"><a href="#">Dashboard</a></div>
                <div class="breadcrumb-item"><a href="#">Manage Products</a></div>
                <div class="breadcrumb-item">Products</div>
            </div>
        </div>
        <div class="section-body">
            <div class="row">
                <div class="col-lg-12">
                    <div class="card">
                        <form class="needs-validation" method="POST" action="{{ route('products.store') }}" novalidate="" enctype="multipart/form-data">
                            @csrf
                            <div class="card-header">
                                <h4>Add New Product</h4>
                            </div>
                            <div class="card-body">
                                <div class="form-group row">
                                    <label class="col-sm-2 text-right col-form-label">Name : </label>
                                    <div class="col-sm-9">
                                        <input type="text" class="form-control @error('name') is-invalid @enderror" name="name" value="{{ old('name') }}" required="" autofocus>
                                        <div class="invalid-feedback">
                                            {{ $errors->has('name') ? $errors->first('name'):"Please enter name of product" }}
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-sm-2 text-right col-form-label">Price : </label>
                                    <div class="col-sm-9">
                                        <input type="text" class="form-control @error('price') is-invalid @enderror" name="price" value="{{ old('price') }}" required="">
                                        <div class="invalid-feedback">
                                            {{ $errors->has('price') ? $errors->first('price'):"Please enter price of product" }}
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-sm-2 text-right col-form-label">Category : </label>
                                    <div class="col-sm-9">
                                        <select name="category_id" id="category_id" title="Select Category of Product" class="select2 form-control @error('category_id') is-invalid @enderror" required="">
                                            <option value="" disabled selected></option>
                                            @foreach($categories as $cat)
                                                @foreach($cat->child as $catt)
                                                    <option value="{{ $catt->id }}" disabled>{{ $catt->name }}</option>
                                                    @foreach($catt->child as $cattt)
                                                        <option value="{{ $cattt->id }}" {{ $cattt->id == old('category_id') ? "selected" : ""}}>-- {{ $cattt->name }}</option>
                                                    @endforeach
                                                @endforeach
                                            @endforeach
                                        </select>
                                        <div class="invalid-feedback">
                                            {{ $errors->has('category_id') ? $errors->first('category_id'):"Please select category of product" }}
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-sm-2 text-right col-form-label">Brand : </label>
                                    <div class="col-sm-9">
                                        <select name="brand_id" id="brand_id" title="Select Brand of Product" class="select2 form-control @error('brand_id') is-invalid @enderror" required="">
                                            <option value="" disabled selected></option>
                                            @foreach($brands as $bra)
                                                <option value="{{ $bra->id }}" {{ $bra->id == old('brand_id') ? "selected" : ""}}>{{ $bra->name }}</option>
                                            @endforeach
                                        </select>
                                        <div class="invalid-feedback">
                                            {{ $errors->has('brand_id') ? $errors->first('brand_id'):"Please select brand of product" }}
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-sm-2 text-right col-form-label">Warranty : </label>
                                    <div class="col-sm-9">
                                        <select name="warranty_id" id="warranty_id" title="Select Warranty of Product" class="select2 form-control  @error('warranty_id') is-invalid @enderror" required="">
                                            <option value="" disabled selected></option>
                                            @foreach($warranties as $war)
                                                <option value="{{ $war->id }}" {{ $war->id == old('warranty_id') ? "selected" : ""}}>{{ $war->name }}</option>
                                            @endforeach
                                        </select>
                                        <div class="invalid-feedback">
                                            {{ $errors->has('warranty_id') ? $errors->first('warranty_id'):"Please select warranty of product" }}
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-sm-2 text-right col-form-label">Image : </label>
                                    <div class="col-sm-9">
                                        <input type="file" class="form-control @error('image') is-invalid @enderror" name="image[]" required="" multiple>
                                        <div class="invalid-feedback">
                                            {{ $errors->has('image') ? $errors->first('image'):"Please enter image of product" }}
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="card-footer offset-md-2 text-left">
                                <input type="submit" class="btn btn-primary">
                                <a href="{{ route('products.index') }}" class="btn btn-default">Back</a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection

@section('page_css')
<link href="{{ asset('assets/css/dropzone.css') }}" rel="stylesheet" type="text/css"/>
<link href="{{ asset('assets/css/summernote.min.css') }}" rel="stylesheet" type="text/css"/>
<link href="http://cdnjs.cloudflare.com/ajax/libs/summernote/0.8.1/summernote.css" rel="stylesheet">
@endsection

@section('page_js')
<script src="{{ asset('assets/js/summernote.min.js') }}"></script>
<script src="{{ asset('assets/js/dropzone.js') }}"></script>
@endsection


@section('scripts')
<script>
$(function() {

});
</script>
@endsection
