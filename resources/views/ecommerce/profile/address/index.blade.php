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

                    <div class="row mb-3">
                        <div class="col-md-12">
                            <a href="{{ route('ecommerce.profile.address.create') }}" class="btn">Add New Address</a>
                        </div>
                    </div>
                    <div class="row">
                        @forelse ($addresses as $address)
                            <div class="col-md-6 mb-3">
                                <h5>{{ $address->name }} ({{ $address->is_default == 1 ? "Main":"Optional"}})</h5>
                                <p>{{ $address->street }}, {{ $address->cities->name }}, {{ $address->provinces->name }}</p>
                                <form action="{{ route('ecommerce.profile.address.delete', $address->id) }}" method="POST">
                                    @csrf
                                    @method('DELETE')
                                    <a href="{{ route('ecommerce.profile.address.edit', $address->id) }}" class="btn">Edit Address</a>
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
<!-- My Account End -->
@endsection
