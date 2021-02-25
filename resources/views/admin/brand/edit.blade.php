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
                        <form class="needs-validation" method="POST" action="{{ route('brands.update', $brand->id) }}" novalidate="" enctype="multipart/form-data">
                            @csrf
                            @method('PUT')
                            <div class="card-header">
                                <h4>Edit Brand</h4>
                            </div>
                            <div class="card-body">
                                <div class="form-group row has-error">
                                    <label class="col-sm-2 text-right col-form-label">Name : </label>
                                    <div class="col-sm-9">
                                        <input type="text" class="form-control @error('name') is-invalid @enderror" name="name" value="{{ $brand->name }}" required="" autofocus>
                                        <div class="invalid-feedback">
                                            {{ $errors->has('name') ? $errors->first('name'):"Please enter name of brand" }}
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group row has-error">
                                    <label class="col-sm-2 text-right col-form-label">Image : </label>
                                    <div class="col-sm-9">
                                        <div>
                                            <a href="{{ URL::to('uploads/images/'.($brand->image ? 'brands/'.$brand->image : 'no_image.png')) }}" data-lightbox="image-1" alt="{{ $brand->name }}">
                                                <img src="{{ URL::to('uploads/images/'.($brand->image ? 'brands/thumb/'.$brand->image : 'no_image.png')) }}" data-lightbox="image-1" alt="{{ $brand->name }}" width="100px" height="80px">
                                            </a>
                                        </div>
                                        <br/>
                                        <input type="file" class="form-control @error('image') is-invalid @enderror" name="image">
                                        <div class="invalid-feedback">
                                            {{ $errors->has('image') ? $errors->first('image'):"Please select the valid image" }}
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="card-footer offset-md-2 text-left">
                                <input type="submit" class="btn btn-primary">
                                <a href="{{ route('brands.index') }}" class="btn btn-default">Back</a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection

@section('page_css')
<link href="{{ asset('vendor/lightbox2/dist/css/lightbox.css') }}" rel="stylesheet" type="text/css"/>
@endsection

@section('page_js')
<script src="{{ asset('vendor/lightbox2/dist/js/lightbox.js') }}"></script>
@endsection

@section('scripts')
<script>
    lightbox.option({
        'disableScrolling': true,
        'showImageNumberLabel': false,
    })
</script>
@endsection
