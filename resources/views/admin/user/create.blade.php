@extends('layouts.app')

@section('content')
    <section class="section">
        @include('admin._partial.breadcrumb')
        <div class="section-body">
            <div class="row">
                <div class="col-lg-12">
                    <div class="card">
                        <form class="needs-validation" method="POST" action="{{ route('admin.admins.store') }}" novalidate="">
                            @csrf
                            <div class="card-header">
                                <h4>Tambah Pengguna</h4>
                            </div>
                            <div class="card-body">
                                @if (session('error'))
                                    <div class="alert alert-danger alert-dismissible fade show" role="alert">{{ session('error') }}</div>
                                @endif
                                <div class="form-group row">
                                    <label class="col-sm-2 text-right col-form-label">Nama Depan : </label>
                                    <div class="col-sm-9">
                                        <input type="text" class="form-control @error('first_name') is-invalid @enderror" name="first_name" required="" autofocus>
                                        <div class="invalid-feedback">
                                            {{ $errors->has('first_name') ? $errors->first('first_name'):"Please enter name of category" }}
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-sm-2 text-right col-form-label">Nama Belakang : </label>
                                    <div class="col-sm-9">
                                        <input type="text" class="form-control @error('last_name') is-invalid @enderror" name="last_name" required="">
                                        <div class="invalid-feedback">
                                            {{ $errors->has('last_name') ? $errors->first('last_name'):"Please enter name of category" }}
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-sm-2 text-right col-form-label">No. Handphone : </label>
                                    <div class="col-sm-9">
                                        <input type="text" class="form-control @error('phone') is-invalid @enderror" name="phone" required="">
                                        <div class="invalid-feedback">
                                            {{ $errors->has('phone') ? $errors->first('phone'):"Please enter name of category" }}
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-sm-2 text-right col-form-label">E-mail : </label>
                                    <div class="col-sm-9">
                                        <input type="email" class="form-control @error('email') is-invalid @enderror" name="email" required="">
                                        <div class="invalid-feedback">
                                            {{ $errors->has('email') ? $errors->first('email'):"Please enter name of category" }}
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-sm-2 text-right col-form-label">Propinsi : </label>
                                    <div class="col-sm-9">
                                        <select name="province_id" id="province_id" class="select2 form-control">
                                            <option value="" disabled selected>Pilih Propinsi</option>
                                            @foreach($provinces as $item)
                                                <option value="{{ $item->id }}">{{ $item->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-sm-2 text-right col-form-label">Kota : </label>
                                    <div class="col-sm-9">
                                        <select name="city_id" id="city_id" class="select2 form-control" disabled>
                                            <option value="" disabled selected>Pilih Kota</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-sm-2 text-right col-form-label">Kode Pos : </label>
                                <div class="col-sm-9">
                                    <input type="text" class="form-control @error('postcode') is-invalid @enderror" name="postcode" required="">
                                    <div class="invalid-feedback">
                                        {{ $errors->has('postcode') ? $errors->first('postcode'):"Please enter name of category" }}
                                    </div>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-sm-2 text-right col-form-label">Alamat : </label>
                                <div class="col-sm-9">
                                    <textarea name="street" class="form-control @error('street') is-invalid @enderror" id="street" cols="30" rows="50" style="height: 120px;"></textarea>
                                    <div class="invalid-feedback">
                                        {{ $errors->has('street') ? $errors->first('street'):"Please enter name of category" }}
                                    </div>
                                </div>
                            </div>
                            <div class="card-footer offset-md-2 text-left">
                                <input type="submit" class="btn btn-primary" name="submitButton" value="Simpan">
                                <a href="{{ route('admin.admins.index') }}" class="btn btn-default">Kembali</a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection

@section('scripts')
<script>
    $(document).ready(function() {
        $('#province_id').on('change', function(e) {
            e.preventDefault();
            let id = $(this).val();

            console.log($(this).val());
            $.ajax({
                url: "{{ url('/api/get-city') }}",
                method: "GET",
                dataType: "JSON",
                data: { id:id },
                success: function(res)
                {
                    if(res)
                    {
                        $('#city_id').empty();
                        $('#city_id').append('<option selected disabled>Select City</option>');
                        $('#city_id').prop('disabled', false);

                        $.each(res, function(key, item) {
                            $('#city_id').append('<option value="'+item.id+'">'+item.name+'</option>')
                        })
                    }
                }
            });
        });
    });
</script>
@endsection
