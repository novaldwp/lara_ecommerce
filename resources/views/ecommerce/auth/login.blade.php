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
                <form action="{{ route('ecommerce.login.post') }}" method="post">
                    @csrf
                    <div class="login-form">
                        @if (session()->has('message'))
                            <div class="alert alert-danger"><li>{{ session()->get('message') }}</li></div>
                        @elseif (session()->has('success'))
                            <div class="alert alert-success"><li>{{ session()->get('success') }}</li></div>
                        @endif
                        <div class="row">
                            <div class="col-md-6">
                                <label>E-mail</label>
                                <input class="form-control @error('email') is-invalid @enderror" type="email" name="email" value="{{ old('email') }}" placeholder="E-mail" required autofocus>
                                <div class="invalid-feedback mb-3">
                                    {{ $errors->has('email') ? $errors->first('email') : "" }}
                                </div>
                            </div>
                            <div class="col-md-6">
                                <label>Password</label>
                                <input class="form-control" type="password" name="password" placeholder="Password" required>
                            </div>
                            <div class="col-md-12">
                                <div class="custom-control custom-checkbox">
                                    <input type="checkbox" class="custom-control-input" id="newaccount">
                                    <label class="custom-control-label" for="newaccount">Keep me signed in</label>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <button class="btn">Submit</button>
                                <span class="ml-3">Don't have account? Click <a href="{{ route('ecommerce.register.index') }}">here</a>.</span>
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
