@extends('layouts.front.app')

@section('content')
<!-- Breadcrumb Start -->
<div class="breadcrumb-wrap">
    <div class="container-fluid">
        <ul class="breadcrumb">
            <li class="breadcrumb-item"><a href="#">Home</a></li>
            <li class="breadcrumb-item active">Daftar</li>
        </ul>
    </div>
</div>
<!-- Breadcrumb End -->

<!-- Login Start -->
<div class="login">
    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-12">
                <form action="{{ route('auth.register.store') }}" method="post">
                    @csrf
                    <div class="register-form">
                        <div class="row">
                            <div class="col-md-6">
                                <label>Nama Depan</label>
                                <input class="form-control @error('first_name') is-invalid @enderror" type="text" name="first_name"
                                value="{{ old('first_name') }}" placeholder="Nama Depan" required autofocus>
                                <div class="invalid-feedback mb-1">
                                    {{ $errors->has('first_name') ? $errors->first('first_name') : "" }}
                                </div>
                            </div>
                            <div class="col-md-6">
                                <label>Nama Belakang</label>
                                <input class="form-control" type="text" name="last_name" value="{{ old('last_name') }}" placeholder="Nama Belakang" required>
                            </div>
                            <div class="col-md-6">
                                <label>E-mail</label>
                                <input class="form-control @error('email') is-invalid @enderror" type="email" name="email"
                                value="{{ old('email') }}" placeholder="E-mail" required>
                                <div class="invalid-feedback mb-1">
                                    {{ $errors->has('email') ? $errors->first('email') : "" }}
                                </div>
                            </div>
                            <div class="col-md-6">
                                <label>No. Handphone</label>
                                <input class="form-control @error('phone') is-invalid @enderror" type="text" name="phone"
                                value="{{ old('phone') }}" placeholder="No. Handphone" oninput="this.value = this.value.replace(/[^0-9]/g, '').replace(/(\..*)\./g, '$1');" required>
                                <div class="invalid-feedback mb-1">
                                    {{ $errors->has('phone') ? $errors->first('phone') : "" }}
                                </div>
                            </div>
                            <div class="col-md-6">
                                <label>Propinsi</label>
                                <select name="province_id" id="province_id" class="form-control @error('province_id') is-invalid @enderror">
                                    <option value="" selected disabled>Pilih Propinsi</option>
                                    @forelse ($provinces as $row)
                                        <option value="{{ $row->id }}">{{ $row->name }}</option>
                                    @empty
                                        <option disabled> -- Data Propinsi Tidak Tersedia --</option>
                                    @endforelse
                                </select>
                                <div class="invalid-feedback mb-3">
                                    {{ $errors->has('province_id') ? $errors->first('province_id') : "" }}
                                </div>
                            </div>
                            <div class="col-md-6">
                                <label>Kota</label>
                                <select name="city_id" id="city_id" class="form-control  @error('city_id') is-invalid @enderror" disabled>
                                    <option value="" selected disabled>Select City</option>
                                </select>
                                <div class="invalid-feedback mb-3">
                                    {{ $errors->has('city_id') ? $errors->first('city_id') : "" }}
                                </div>
                            </div>
                            <div class="col-md-12">
                                <label>Kode Pos</label>
                                <input class="form-control @error('postcode') is-invalid @enderror" type="text" name="postcode"
                                value="{{ old('postcode') }}" placeholder="Kode Pos" oninput="this.value = this.value.replace(/[^0-9]/g, '').replace(/(\..*)\./g, '$1');" required>
                                <div class="invalid-feedback mb-1">
                                    {{ $errors->has('postcode') ? $errors->first('postcode') : "" }}
                                </div>
                            </div>
                            <div class="col-md-12">
                                <label>Alamat</label>
                                    <textarea name="street" class="form-control" id="" cols="30" rows="5" placeholder="Alamat" required>{{ old('street') }}</textarea>
                            </div>
                            <div class="col-md-6">
                                <label>Password</label>
                                <input class="form-control @error('password') is-invalid @enderror" type="password" name="password" placeholder="Password" required>
                                <div class="invalid-feedback mb-1">
                                    {{ $errors->has('password') ? $errors->first('password') : "" }}
                                </div>
                            </div>
                            <div class="col-md-6">
                                <label>Konfirmasi Password</label>
                                <input class="form-control" type="password" name="password_confirmation" placeholder="Password" required>
                            </div>
                            <div class="col-md-12 mt-3">
                                <button class="btn mr-3">Daftar</button>
                                <span> Sudah mempunyai akun? Klik <a href="{{ route('auth.login') }}">disini</a> untuk Login.</span>.
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<!-- Login End -->
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
