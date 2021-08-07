@extends('layouts.front.app')

@section('content')
<!-- Breadcrumb Start -->
<div class="breadcrumb-wrap">
    <div class="container-fluid">
        <ul class="breadcrumb">
            <li class="breadcrumb-item"><a href="#">Home</a></li>
            <li class="breadcrumb-item active">My Account</li>
        </ul>
    </div>
</div>
<!-- Breadcrumb End -->

<!-- My Account Start -->
<div class="my-account">
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-3">
                @include('layouts.front.profile.navbar')
            </div>
            <div class="col-md-9">
                <div class="tab-content">
                    <h4>Account Details</h4>
                    <form action="{{ route('ecommerce.profile.account.detail.update', $member->id) }}" method="post">
                        @csrf
                        @method('PATCH')
                        <div class="row">
                            <div class="col-md-6">
                                <input class="form-control @error('first_name') is-invalid @enderror" type="text" name="first_name" value="{{ $member->first_name }}" placeholder="First Name" required>
                                <div class="invalid-feedback mb-3">
                                    {{ $errors->has('first_name') ? $errors->first('first_name') : "" }}
                                </div>
                            </div>
                            <div class="col-md-6">
                                <input class="form-control" type="text" name="last_name" value="{{ $member->last_name }}" placeholder="Last Name" required>
                            </div>
                            <div class="col-md-6">
                                <input class="form-control @error('phone') is-invalid @enderror" type="text" name="phone" value="{{ $member->phone }}" oninput="this.value = this.value.replace(/[^0-9]/g, '').replace(/(\..*)\./g, '$1');" placeholder="Mobile" required>
                                <div class="invalid-feedback mb-3">
                                    {{ $errors->has('phone') ? $errors->first('phone') : "" }}
                                </div>
                            </div>
                            <div class="col-md-6">
                                <input class="form-control @error('email') is-invalid @enderror" type="email" name="email" value="{{ $member->email }}" placeholder="Email" required>
                                <div class="invalid-feedback mb-3">
                                    {{ $errors->has('email') ? $errors->first('email') : "" }}
                                </div>
                            </div>
                            <div class="col-md-12">
                                <button class="btn">Update Account</button>
                                <br><br>
                            </div>
                        </div>
                    </form>
                    <h4>Password change</h4>
                    <form action="{{ route('ecommerce.profile.account.password.update', $member->id) }}" method="post">
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
                                <button class="btn">Save Changes</button>
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
