@extends('layouts.front.app')

@section('content')
<!-- Breadcrumb Start -->
<div class="breadcrumb-wrap">
    <div class="container-fluid">
        <ul class="breadcrumb">
            <li class="breadcrumb-item"><a href="#">Beranda</a></li>
            <li class="breadcrumb-item active"><a href="#"> Akun Saya</a></li>
            <li class="breadcrumb-item">Biodata Diri</li>
        </ul>
    </div>
</div>
<!-- Breadcrumb End -->

@section('title')
    Biodata Diri | Toko Putra Elektronik
@endsection

<!-- My Account Start -->
<div class="my-account">
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-3">
                @include('layouts.front.profile.navbar')
            </div>
            <div class="col-md-9">
                <div class="tab-content">
                    <h4>Biodata Diri</h4>

                    @if (session('success'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">{{ session('success') }}</div>
                    @elseif (session('error'))
                        <div class="alert alert-error alert-dismissible fade show" role="alert">{{ session('error') }}</div>
                    @endif
                    <form action="{{ route('ecommerce.profile.account.detail.update', simple_encrypt(auth()->user()->id)) }}" method="post">
                        @csrf
                        @method('PATCH')
                        <div class="row">
                            <div class="col-md-6">
                                <input class="form-control @error('first_name') is-invalid @enderror" type="text" name="first_name" value="{{ auth()->user()->first_name }}" placeholder="First Name" required>
                                <div class="invalid-feedback mb-3">
                                    {{ $errors->has('first_name') ? $errors->first('first_name') : "" }}
                                </div>
                            </div>
                            <div class="col-md-6">
                                <input class="form-control" type="text" name="last_name" value="{{ auth()->user()->last_name }}" placeholder="Last Name" required>
                            </div>
                            <div class="col-md-6">
                                <input class="form-control @error('phone') is-invalid @enderror" type="text" name="phone" value="{{ auth()->user()->phone }}" oninput="this.value = this.value.replace(/[^0-9]/g, '').replace(/(\..*)\./g, '$1');" placeholder="No. Handphone" required>
                                <div class="invalid-feedback mb-3">
                                    {{ $errors->has('phone') ? $errors->first('phone') : "" }}
                                </div>
                            </div>
                            <div class="col-md-6">
                                <input class="form-control @error('email') is-invalid @enderror" type="email" name="email" value="{{ auth()->user()->email }}" placeholder="Email" required>
                                <div class="invalid-feedback mb-3">
                                    {{ $errors->has('email') ? $errors->first('email') : "" }}
                                </div>
                            </div>
                            <div class="col-md-6">
                                <select name="province_id" id="province_id" class="form-control @error('province_id') is-invalid @enderror">
                                    @forelse ($provinces as $row)
                                        <option value="{{ $row->id }}" {{ ($row->id == $user->addresses->province_id) ? "selected":"" }}>{{ $row->name }}</option>
                                    @empty
                                        <option disabled> -- Data Provinces Not Found --</option>
                                    @endforelse
                                </select>
                                <div class="invalid-feedback mb-3">
                                    {{ $errors->has('province_id') ? $errors->first('province_id') : "" }}
                                </div>
                            </div>
                            <div class="col-md-6">
                                <select name="city_id" id="city_id" class="form-control  @error('city_id') is-invalid @enderror">
                                    @forelse ($cities as $row)
                                        <option value="{{ $row->id }}" {{ ($row->id == $user->addresses->city_id) ? "selected":"" }}>{{ $row->name }}</option>
                                    @empty
                                        <option disabled> -- Data Provinces Not Found --</option>
                                    @endforelse
                                </select>
                                <div class="invalid-feedback mb-3">
                                    {{ $errors->has('city_id') ? $errors->first('city_id') : "" }}
                                </div>
                            </div>
                            <div class="col-md-12">
                                <input class="form-control @error('postcode') is-invalid @enderror" type="text" name="postcode" value="11480" placeholder="Kode Pos" required>
                                <div class="invalid-feedback mb-3">
                                    {{ $errors->has('postcode') ? $errors->first('postcode') : "" }}
                                </div>
                            </div>
                            <div class="col-md-12">
                                <textarea class="form-control" name="street" rows="5" cols="30"required> {{ $user->addresses->street }}</textarea>
                                <div class="invalid-feedback mb-3">
                                    {{ $errors->has('email') ? $errors->first('email') : "" }}
                                </div>
                            </div>
                            <div class="col-md-12">
                                <button class="btn">Perbarui Akun</button>
                                <br><br>
                            </div>
                        </div>
                    </form>
                    <h4>Ubah Password</h4>
                    <form action="{{ route('ecommerce.profile.account.password.update', simple_encrypt(auth()->user()->id)) }}" method="post">
                        @csrf
                        @method('PATCH')
                        <div class="row">
                            <div class="col-md-12">
                                <input class="form-control @error('current_password') is-invalid @enderror" type="password" name="current_password" placeholder="Current Password">
                                <div class="invalid-feedback mb-3">
                                    {{ $errors->has('current_password') ? $errors->first('current_password') : "" }}
                                </div>
                            </div>
                            <div class="col-md-6">
                                <input class="form-control @error('password') is-invalid @enderror" type="password" name="password" placeholder="New Password">
                                <div class="invalid-feedback mb-3">
                                    {{ $errors->has('password') ? $errors->first('password') : "" }}
                                </div>
                            </div>
                            <div class="col-md-6">
                                <input class="form-control @error('password_confirmation') is-invalid @enderror" type="password" name="password_confirmation" placeholder="Confirm Password">
                                <div class="invalid-feedback mb-3">
                                    {{ $errors->has('emapassword_confirmationil') ? $errors->first('password_confirmation') : "" }}
                                </div>
                            </div>
                            <div class="col-md-12">
                                <button class="btn">Perbarui Password</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- My Account End -->
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
