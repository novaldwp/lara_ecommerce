@extends('layouts.app')

@section('content')
    <section class="section">
        <div class="section-header">
            <h3 class="page__heading">Manage Brands</h3>
            <div class="section-header-breadcrumb">
                <div class="breadcrumb-item active"><a href="#">Dashboard</a></div>
                <div class="breadcrumb-item"><a href="#">Manage Products</a></div>
                <div class="breadcrumb-item">Brands</div>
            </div>
        </div>
        <div class="section-body">
            <div class="row">
                <div class="col-lg-12">
                    <div class="card">
                        <form class="needs-validation" method="POST" action="{{ route('products.update', $product->id) }}" novalidate="" enctype="multipart/form-data">
                            @csrf
                            @method('PUT')
                            <div class="card-header">
                                <h4>Edit Brand</h4>
                            </div>
                            <div class="card-body">
                                <div class="form-group row">
                                    <label class="col-sm-2 text-right col-form-label">Name : </label>
                                    <div class="col-sm-9">
                                        <input type="text" class="form-control @error('name') is-invalid @enderror" name="name" value="{{ $product->name }}" required="" autofocus>
                                        <div class="invalid-feedback">
                                            {{ $errors->has('name') ? $errors->first('name'):"Please enter name of product" }}
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-sm-2 text-right col-form-label">Price : </label>
                                    <div class="col-sm-9">
                                        <input type="text" class="form-control @error('price') is-invalid @enderror" name="price" value="{{ $product->price }}" required="">
                                        <div class="invalid-feedback">
                                            {{ $errors->has('price') ? $errors->first('price'):"Please enter price of product" }}
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-sm-2 text-right col-form-label">Weight : </label>
                                    <div class="col-sm-9">
                                        <input type="text" class="form-control @error('price') is-invalid @enderror" name="weight" value="{{ $product->weight }}" required="">
                                        <div class="invalid-feedback">
                                            {{ $errors->has('weight') ? $errors->first('weight'):"Please enter weight of product" }}
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-sm-2 text-right col-form-label">Category : </label>
                                    <div class="col-sm-9">
                                        <select name="category_id" id="category_id" title="Select Category of Product" class="select2 form-control @error('category_id') is-invalid @enderror" required="">
                                            <option value="" disabled selected></option>
                                            @foreach($categories as $cat)
                                                <option value="{{ $cat->id }}" disabled>{{ $cat->name }}</option>
                                                @foreach($cat->child as $catt)
                                                    <option value="{{ $catt->id }}" {{ $catt->id == $product->category_id ? "selected" : ""}}>-- {{ $catt->name }}</option>
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
                                                <option value="{{ $bra->id }}" {{ $bra->id == $product->brand_id ? "selected" : ""}}>{{ $bra->name }}</option>
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
                                                <option value="{{ $war->id }}" {{ $war->id == $product->warranty_id ? "selected" : ""}}>{{ $war->name }}</option>
                                            @endforeach
                                        </select>
                                        <div class="invalid-feedback">
                                            {{ $errors->has('warranty_id') ? $errors->first('warranty_id'):"Please select warranty of product" }}
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-sm-2 text-right col-form-label">Description : </label>
                                    <div class="col-sm-9">
                                        <textarea name="description" id="" cols="30" rows="10" class="summernote-simple form-control" required>{{ $product->description }}</textarea>
                                        <div class="invalid-feedback">
                                            {{ $errors->has('description') ? $errors->first('description'):"Please enter description of product" }}
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-sm-2 text-right col-form-label">Specification : </label>
                                    <div class="col-sm-9">
                                        <textarea name="specification" id="" cols="30" rows="10" class="summernote-simple form-control" required>{{ $product->specification ?? "" }}</textarea>
                                        <div class="invalid-feedback">
                                            {{ $errors->has('specification') ? $errors->first('specification'):"Please enter description of product" }}
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-sm-2 text-right col-form-label">Image : </label>
                                    <div class="col-sm-3">
                                        <div class="box-area {{ session()->has("error-image") ? "error":"" }}" id="custom-btn" onclick="image1Active()">
                                            <div class="image">
                                                <img name="image1" src="{{ asset(($product->productimages->image1 != "") ?$product->productimages->path.$product->productimages->image1 : "uploads/images/no_image.png") }}">
                                            </div>
                                            <div class="box-area-icon">
                                                <a href="javscript:void(0);">
                                                    <i class="fa fa-image"></i>
                                                </a>
                                            </div>
                                            <header>Main Image</header>

                                            <input type="file" name="image1" hidden>
                                        </div>
                                    </div>
                                    <div class="col-sm-3">
                                        <div class="box-area {{ session()->has("error-image") ? "error":"" }}" id="custom-btn" onclick="image2Active()">
                                            <div class="image">
                                                <img name="image2" src="{{ asset(($product->productimages->image2 != "") ?$product->productimages->path.$product->productimages->image2 : "uploads/images/no_image.png") }}">
                                            </div>
                                            <div class="box-area-icon">
                                                <a href="javscript:void(0);">
                                                    <i class="fa fa-image"></i>
                                                </a>
                                            </div>
                                            <header>Additional Image</header>

                                            <input type="file" name="image2" hidden>
                                        </div>
                                    </div>
                                    <div class="col-sm-3">
                                        <div class="box-area {{ session()->has("error-image") ? "error":"" }}" id="custom-btn" onclick="image3Active()">
                                            <div class="image">
                                                <img name="image3" src="{{ asset(($product->productimages->image3 != "") ?$product->productimages->path.$product->productimages->image3 : "uploads/images/no_image.png") }}">
                                            </div>
                                            <div class="box-area-icon">
                                                <a href="javscript:void(0);">
                                                    <i class="fa fa-image"></i>
                                                </a>
                                            </div>
                                            <header>Additional Image</header>

                                            <input type="file" name="image3" hidden>
                                        </div>
                                    </div>
                                    <div class="col-sm-3 offset-sm-2">
                                        <div class="box-area {{ session()->has("error-image") ? "error":"" }}" id="custom-btn" onclick="image4Active()">
                                            <div class="image">
                                                <img name="image4" src="{{ asset(($product->productimages->image4 != "") ?$product->productimages->path.$product->productimages->image4 : "uploads/images/no_image.png") }}">
                                            </div>
                                            <div class="box-area-icon">
                                                <a href="javscript:void(0);">
                                                    <i class="fa fa-image"></i>
                                                </a>
                                            </div>
                                            <header>Additional Image</header>

                                            <input type="file" name="image4" hidden>
                                        </div>
                                    </div>
                                    <div class="col-sm-3">
                                        <div class="box-area {{ session()->has("error-image") ? "error":"" }}" id="custom-btn" onclick="image5Active()">
                                            <div class="image">
                                                <img name="image5" src="{{ asset(($product->productimages->image5 != "") ?$product->productimages->path.$product->productimages->image5 : "uploads/images/no_image.png") }}">
                                            </div>
                                            <div class="box-area-icon">
                                                <a href="javscript:void(0);">
                                                    <i class="fa fa-image"></i>
                                                </a>
                                            </div>
                                            <header>Additional Image</header>

                                            <input type="file" name="image5" hidden>
                                        </div>
                                    </div>
                                    @if (session()->has('error-image'))
                                    <div class="image-feedback">
                                         {{ session()->get('error-image') }}
                                    </div>
                                    @endif
                                </div>
                                <div class="form-group row">
                                    <label class="col-sm-2 text-right col-form-label">Featured : </label>
                                    <div class="col-sm-9">
                                        <select name="is_featured" id="is_featured" title="Select Status of Product" class="select2 form-control  @error('is_featured') is-invalid @enderror" required="">
                                            <option value="" disabled></option>
                                            <option value="1" {{ $product->is_featured == 1 ? "selected" : "" }} >Yes</option>
                                            <option value="0" {{ $product->is_featured == 0 ? "selected" : "" }} >No</option>
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
                                            <option value="" disabled></option>
                                            <option value="1" {{ $product->status == 1 ? "selected" : "" }} >Active</option>
                                            <option value="0" {{ $product->status == 0 ? "selected" : "" }} >Not Active</option>
                                        </select>
                                        <div class="invalid-feedback">
                                            {{ $errors->has('status') ? $errors->first('status'):"Please select status of product" }}
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

@section('css')
<style>
    label.custom-switch {
        padding-left: 15px;
    }

    div.note-editable {
        max-height: 200px;
    }
</style>
@endsection

@section('js')
<script>
    $(document).ready(function() {
        // $('.summernote-simple').summernote({
        //     max-height: '300px';
        // });
    })
</script>
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
