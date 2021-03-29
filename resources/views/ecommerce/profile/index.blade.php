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
                <div class="nav flex-column nav-pills" role="tablist" aria-orientation="vertical">
                    <a class="nav-link {{ !session()->has('nav-account') ? "active":"" }}" id="dashboard-nav" data-toggle="pill" href="#dashboard-tab" role="tab"><i class="fa fa-tachometer-alt"></i>Dashboard</a>
                    <a class="nav-link" id="orders-nav" data-toggle="pill" href="#orders-tab" role="tab"><i class="fa fa-shopping-bag"></i>Orders</a>
                    <a class="nav-link {{ session()->get('nav-account') == "account" ? "active":"" }}" id="account-nav" data-toggle="pill" href="#account-tab" role="tab"><i class="fa fa-user"></i>Account Details</a>
                    <a class="nav-link {{ session()->get('nav-account') == "address" ? "active":"" }}" id="address-nav" data-toggle="pill" href="#address-tab" role="tab"><i class="fa fa-map-marker-alt"></i>Address</a>
                </div>
            </div>
            <div class="col-md-9">
                <div class="tab-content">

                    <div class="tab-pane fade {{ !session()->has('nav-account') ? "show active":"" }}" id="dashboard-tab" role="tabpanel" aria-labelledby="dashboard-nav">
                        <h4>Dashboard</h4>
                        <p>
                            Lorem ipsum dolor sit amet, consectetur adipiscing elit. In condimentum quam ac mi viverra dictum. In efficitur ipsum diam, at dignissim lorem tempor in. Vivamus tempor hendrerit finibus. Nulla tristique viverra nisl, sit amet bibendum ante suscipit non. Praesent in faucibus tellus, sed gravida lacus. Vivamus eu diam eros. Aliquam et sapien eget arcu rhoncus scelerisque.
                        </p>
                    </div>
                    <div class="tab-pane fade {{ session()->get('nav-account') == "order" ? "show active":"" }}" id="orders-tab" role="tabpanel" aria-labelledby="orders-nav">
                        <div class="table-responsive">
                            <table class="table table-bordered">
                                <thead class="thead-dark">
                                    <tr>
                                        <th>No</th>
                                        <th>Product</th>
                                        <th>Date</th>
                                        <th>Price</th>
                                        <th>Status</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td>1</td>
                                        <td>Product Name</td>
                                        <td>01 Jan 2020</td>
                                        <td>$99</td>
                                        <td>Approved</td>
                                        <td><button class="btn">View</button></td>
                                    </tr>
                                    <tr>
                                        <td>2</td>
                                        <td>Product Name</td>
                                        <td>01 Jan 2020</td>
                                        <td>$99</td>
                                        <td>Approved</td>
                                        <td><button class="btn">View</button></td>
                                    </tr>
                                    <tr>
                                        <td>3</td>
                                        <td>Product Name</td>
                                        <td>01 Jan 2020</td>
                                        <td>$99</td>
                                        <td>Approved</td>
                                        <td><button class="btn">View</button></td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="tab-pane fade {{ session()->get('nav-account') == "account" ? "show active":"" }}" id="account-tab" role="tabpanel" aria-labelledby="account-nav">
                        <h4>Account Details</h4>
                        <form action="{{ route('ecommerce.profile.detail', $member->id) }}" method="post">
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
                        <form action="{{ route('ecommerce.profile.password', $member->id) }}" method="post">
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
                    <div class="tab-pane fade {{ session()->get('nav-account') == "address" ? "show active":"" }}" id="address-tab" role="tabpanel" aria-labelledby="address-nav">
                        <div class="row mb-3">
                            <div class="col-md-12">
                                <a href="{{ route('ecommerce.address.create') }}" class="btn">Add New Address</a>
                            </div>
                        </div>
                        <div class="row">
                            @forelse ($addresses as $address)
                                <div class="col-md-6 mb-3">
                                    <h5>{{ $address->name }} ({{ $address->is_default == 1 ? "Main":"Optional"}})</h5>
                                    <p>{{ $address->street }}, {{ $address->cities->name }}, {{ $address->provinces->name }}</p>
                                    <form action="{{ route('ecommerce.address.delete', $address->id) }}" method="POST">
                                        @csrf
                                        @method('DELETE')
                                        <a href="{{ route('ecommerce.address.edit', $address->id) }}" class="btn">Edit Address</a>
                                        <button class="btn btn-danger" onClick="return confirm('Are you sure?')">Delete Address</button>
                                    </form>
                                </div>
                            @empty
                                <div class="col-md-6">
                                    <h5>No Address Available</h5>
                                </div>
                            @endforelse
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- My Account End -->
@endsection
