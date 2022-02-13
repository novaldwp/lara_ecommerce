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
                        <form class="needs-validation" method="POST" action="{{ route('admin.analyst.negative-words.store') }}" novalidate="">
                            @csrf
                            <div class="card-header">
                                <h4>Tambah Kata Dasar Negatif</h4>
                            </div>
                            <div class="card-body">
                                @if (session('error'))
                                    <div class="alert alert-danger alert-dismissible fade show" role="alert">{{ session('error') }}</div>
                                @endif
                                <div class="form-group row">
                                    <label class="col-sm-2 text-right col-form-label">Kata Negatif : </label>
                                    <div class="col-sm-9">
                                        <input type="text" class="form-control @error('word') is-invalid @enderror" name="word" required="" autofocus>
                                        <div class="invalid-feedback">
                                            {{ $errors->has('word') ? $errors->first('word'):"Masukkan Kata Negatif" }}
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="card-footer offset-md-2 text-left">
                                <input type="submit" class="btn btn-primary" name="submitButton" value="Simpan">
                                <a href="{{ route('admin.analyst.negative-words.index') }}" class="btn btn-default">Kembali</a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
