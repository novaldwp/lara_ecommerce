@extends('layouts.front.app')

@section('content')
<!-- Breadcrumb Start -->
<div class="breadcrumb-wrap">
    <div class="container-fluid">
        <ul class="breadcrumb">
            <li class="breadcrumb-item"><a href="#">Home</a></li>
            <li class="breadcrumb-item"><a href="#">Products</a></li>
            <li class="breadcrumb-item active">Login & Register</li>
        </ul>
    </div>
</div>
<!-- Breadcrumb End -->

<!-- Login Start -->
<div class="login">
    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-12">
                <form action="{{ route('ecommerce.register.post') }}" method="post">
                    @csrf
                    <div class="register-form">
                        {{-- {{ dd($errors) }} --}}
                        <div class="row">
                            <div class="col-md-6">
                                <label>First Name</label>
                                <input class="form-control @error('first_name') is-invalid @enderror" type="text" name="first_name"
                                value="{{ old('first_name') }}" placeholder="First Name" autofocus>
                                <div class="invalid-feedback mb-1">
                                    {{ $errors->has('first_name') ? $errors->first('first_name') : "" }}
                                </div>
                            </div>
                            <div class="col-md-6">
                                <label>Last Name</label>
                                <input class="form-control" type="text" name="last_name" value="{{ old('last_name') }}" placeholder="Last Name">
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
                                value="{{ old('phone') }}" placeholder="Mobile No" oninput="this.value = this.value.replace(/[^0-9]/g, '').replace(/(\..*)\./g, '$1');" required>
                                <div class="invalid-feedback mb-1">
                                    {{ $errors->has('phone') ? $errors->first('phone') : "" }}
                                </div>
                            </div>
                            <div class="col-md-6">
                                <label>Password</label>
                                <input class="form-control @error('password') is-invalid @enderror" type="password" name="password" placeholder="Password" required>
                                <div class="invalid-feedback mb-1">
                                    {{ $errors->has('password') ? $errors->first('password') : "" }}
                                </div>
                            </div>
                            <div class="col-md-6">
                                <label>Retype Password</label>
                                <input class="form-control" type="password" name="password_confirmation" placeholder="Password" required>
                            </div>
                            <div class="col-md-12 mt-3">
                                <button class="btn mr-3">Register</button>
                                <span> have a registered account? Click <a href="{{ route('ecommerce.login.index') }}">here</a></span>.
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
