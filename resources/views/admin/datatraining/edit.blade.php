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
                        <form class="needs-validation" method="POST" action="{{ route('admin.analyst.data-trainings.update', simple_encrypt($dataTraining->id)) }}" novalidate="">
                            @csrf
                            @method('PUT')
                            <div class="card-header">
                                <h4>Ubah Data Latih</h4>
                            </div>
                            <div class="card-body">
                                @if (session('error'))
                                    <div class="alert alert-danger alert-dismissible fade show" role="alert">{{ session('error') }}</div>
                                @endif
                                <div class="form-group row">
                                    <label class="col-sm-2 text-right col-form-label">Komentar : </label>
                                    <div class="col-sm-9">
                                        <input type="text" class="form-control @error('comment') is-invalid @enderror" name="comment" value="{{ $dataTraining->comment }}" required="" autofocus>
                                        <div class="invalid-feedback">
                                            {{ $errors->has('comment') ? $errors->first('comment'):"Masukkan Komentar" }}
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-sm-2 text-right col-form-label">Kelas : </label>
                                    <div class="col-sm-9">
                                        <select name="class" id="class" class="select2 form-control" required>
                                            <option value="" disabled selected>-- Pilih Kelas --</option>
                                            <option value="1" {{ ($dataTraining->class == "1") ? "selected" : "" }}>Positif</option>
                                            <option value="0" {{ ($dataTraining->class == "0") ? "selected" : "" }}>Negatif</option>
                                        </select>
                                        {{-- {{ dd($dataTraining) }} --}}
                                        <div class="invalid-feedback">
                                            {{ $errors->has('class') ? $errors->first('class'):"Pilih Kelas" }}
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="card-footer offset-md-2 text-left">
                                <input type="submit" class="btn btn-primary" value="Ubah">
                                <a href="{{ route('admin.analyst.data-trainings.index') }}" class="btn btn-default">Kembali</a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection

