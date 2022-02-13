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
                        <form class="needs-validation" method="POST" action="{{ route('admin.brands.store') }}" novalidate="" enctype="multipart/form-data">
                            @csrf
                            <div class="card-header">
                                <h4>Tambah Merk</h4>
                            </div>
                            <div class="card-body">
                                @if (session('error'))
                                    <div class="alert alert-danger alert-dismissible fade show" role="alert">{{ session('error') }}</div>
                                @endif
                                <div class="form-group row has-error">
                                    <label class="col-sm-2 text-right col-form-label">Nama : </label>
                                    <div class="col-sm-9">
                                        <input type="text" class="form-control @error('name') is-invalid @enderror" name="name" value="{{ old('name') }}" required="" autofocus>
                                        <div class="invalid-feedback">
                                            {{ $errors->has('name') ? $errors->first('name'):"Masukkan Nama Merk." }}
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group row has-error">
                                    <label class="col-sm-2 text-right col-form-label">Gambar : </label>
                                    <div class="col-sm-9">
                                        <input type="file" class="form-control @error('image') is-invalid @enderror" name="image">
                                        <div class="invalid-feedback">
                                            {{ $errors->has('image') ? $errors->first('image'):"Masukkan gambar dengan benar." }}
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="card-footer offset-md-2 text-left">
                                <input type="submit" class="btn btn-primary" value="Simpan">
                                <a href="{{ route('admin.brands.index') }}" class="btn btn-default">Kembali</a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection

