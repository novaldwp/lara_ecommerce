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
                        <form class="needs-validation" method="POST" action="{{ route('admin.setting.sliders.update', simple_encrypt($slider->id)) }}" novalidate="" enctype="multipart/form-data">
                            @csrf
                            @method('PUT')
                            <div class="card-header">
                                <h4>Ubah Slider</h4>
                            </div>
                            <div class="card-body">
                                @if (session('error'))
                                    <div class="alert alert-danger alert-dismissible fade show" role="alert">{{ session('error') }}</div>
                                @endif
                                <div class="form-group row has-error">
                                    <label class="col-sm-2 text-right col-form-label">Nama : </label>
                                    <div class="col-sm-9">
                                        <input type="text" class="form-control @error('name') is-invalid @enderror" name="name" value="{{ $slider->name }}" required="" autofocus>
                                        <div class="invalid-feedback">
                                            {{ $errors->has('name') ? $errors->first('name'):"Masukkan Nama Slider" }}
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-sm-2 text-right col-form-label">Deskripsi : </label>
                                    <div class="col-sm-9">
                                        <textarea name="description" id="" cols="30" rows="10" class="summernote-simple form-control @error('description') is-invalid @enderror" required>{{ $slider->description }}</textarea>
                                        <div class="invalid-feedback">
                                            {{ $errors->has('description') ? $errors->first('description'):"Masukkan Deskripsi" }}
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group row has-error">
                                    <label class="col-sm-2 text-right col-form-label">Gambar : </label>
                                    <div class="col-sm-9">
                                        <div>
                                            <a href="{{ URL::to('uploads/images/'.($slider->image ? 'sliders/'.$slider->image : 'no_image.png')) }}" data-lightbox="image-1" alt="{{ $slider->name }}">
                                                <img src="{{ URL::to('uploads/images/'.($slider->image ? 'sliders/thumb/'.$slider->image : 'no_image.png')) }}" data-lightbox="image-1" alt="{{ $slider->name }}" width="100px" height="80px">
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
                                <input type="submit" class="btn btn-primary" value="Ubah">
                                <a href="{{ route('admin.setting.sliders.index') }}" class="btn btn-default">Kembali</a>
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
<link href="{{ asset('assets/css/summernote.min.css') }}" rel="stylesheet" type="text/css"/>
<link href="http://cdnjs.cloudflare.com/ajax/libs/summernote/0.8.1/summernote.css" rel="stylesheet">
@endsection

@section('page_js')
<script src="{{ asset('vendor/lightbox2/dist/js/lightbox.js') }}"></script>
<script src="{{ asset('assets/js/summernote.min.js') }}"></script>
@endsection

@section('scripts')
<script>
    lightbox.option({
        'disableScrolling': true,
        'showImageNumberLabel': false,
    })
</script>
@endsection
