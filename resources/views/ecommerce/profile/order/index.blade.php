@extends('layouts.front.app')

@section('css')
<link href="https://cdn.datatables.net/1.10.16/css/jquery.dataTables.min.css" rel="stylesheet">
<link href="https://cdn.datatables.net/1.10.19/css/dataTables.bootstrap4.min.css" rel="stylesheet">
@endsection

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
                    <div class="table-responsive">
                        <table class="table table-bordered" id="data-table">
                            <thead class="thead-dark">
                                <tr>
                                    <th>No</th>
                                    <th>Order ID</th>
                                    <th>Date</th>
                                    <th>Total Price</th>
                                    <th>Status</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                {{-- @php $i = 1; @endphp
                                @forelse($orders as $order)
                                    <tr>
                                        <td>{{ $i++ }}</td>
                                        <td>{{ $order->code }}</td>
                                        <td>{{ getDateTimeIndo($order->order_date) }}</td>
                                        <td>Rp. {{ number_format($order->total_price, 0) }}</td>
                                        <td>{!! getOrderStatusMember($order->status) !!}</td>
                                        <td><a href="{{ route('ecommerce.profile.orders.detail', $order->id) }}" class="btn">Details</a></td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6">No. Order Available</td>
                                    </tr>
                                @endforelse --}}
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- My Account End -->
@endsection

@section('scripts')
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.0/jquery.validate.js"></script>
<script src="https://cdn.datatables.net/1.10.16/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.10.19/js/dataTables.bootstrap4.min.js"></script>

<script>
$(document).ready(function() {
    var table = $('#data-table').DataTable({
        processing: true,
        serverSide: true,
        ajax: "{{ route('ecommerce.profile.orders') }}",
        columns: [
            {
                data: 'DT_RowIndex',
                name: 'DT_RowIndex',
                width: "5%"
            },
            {
                data: 'code',
                name: 'code',
                width: "12%"
            },
            {
                data: 'order_date',
                name: 'order_date',
                width: "30%"
            },
            {
                data: 'total_price',
                name: 'total_price',
                width: "15%"
            },
            {
                data: 'status',
                name: 'status',
                width: "10%"
            },
            {
                data: 'action',
                name: 'action',
                width: "8%",
                orderable: false,
                searchable: false
            },
        ]
    });
});
</script>
@endsection
