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
                        <form class="needs-validation" method="POST" action="{{ route('admin.categories.update', simple_encrypt($category->id)) }}" novalidate="">
                            @csrf
                            @method('PUT')
                            <div class="card-header">
                                <h4>Edit Category</h4>
                            </div>
                            <div class="card-body">
                                @if (session('error'))
                                    <div class="alert alert-danger alert-dismissible fade show" role="alert">{{ session('error') }}</div>
                                @endif
                                <div class="form-group row">
                                    <label class="col-sm-2 text-right col-form-label">Name Category : </label>
                                    <div class="col-sm-9">
                                        <input type="text" class="form-control @error('name') is-invalid @enderror" name="name" value="{{ $category->name }}" required="">
                                        <div class="invalid-feedback">
                                            {{ $errors->has('name') ? $errors->first('name'):"Silahkan masukkan nama kategori" }}
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-sm-2 text-right col-form-label">Parent : </label>
                                    <div class="col-sm-9">
                                        <select name="parent_id" id="parent_id" class="select2 form-control">
                                            <option value="">-- --</option>
                                            @foreach($categories as $cat)
                                                @if($cat->id != $category->id)
                                                    <option value="{{ $cat->id }}" {{ $cat->id == $category->parent_id ? "selected":""}} {{ $cat->id == $category->id ? "disabled": ""}}>{{ $cat->name }}</option>
                                                @endif
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="card-footer offset-md-2 text-left">
                                <input type="submit" class="btn btn-primary" value="Ubah">
                                <a href="{{ route('admin.categories.index') }}" class="btn btn-default">Kembali</a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection

