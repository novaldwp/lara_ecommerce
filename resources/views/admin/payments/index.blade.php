@extends('layouts.app')

@section('content')
    <section class="section">
        <div class="section-header">
            <h3 class="page__heading">Manage Payments</h3>
            <div class="section-header-breadcrumb">
                <div class="breadcrumb-item active"><a href="#">Dashboard</a></div>
                <div class="breadcrumb-item"><a href="#">Manage Products</a></div>
                <div class="breadcrumb-item">Payments</div>
            </div>
        </div>
        <div class="section-body">
            <div class="row">
                <div class="card">

                </div>
            </div>
        </div>
        <div class="section-body">
            <div class="row">
                <div class="col-lg-12">
                    <div class="card">
                        <div class="card-header">
                            <h4 class="page__heading">List Payments</h4>
                            <a href="{{ route('payments.create') }}" class="btn btn-success">Add New</a>
                        </div>
                          <div class="card-body">
                            <div class="table-responsive">
                              <table class="table table-bordered table-hovered table-striped table-md">
                                <tr>
                                  <th width="4%">#</th>
                                  <th>Account Name</th>
                                  <th>Account Number</th>
                                  <th>Bank</th>
                                  <th>Method</th>
                                  <th width="20%">Action</th>
                                </tr>
                                @php $i = ($payments->currentPage() - 1) * $payments->perpage() + 1; @endphp
                                @forelse($payments as $pay)
                                <tr>
                                    <td>{{ $i++ }}</td>
                                    <td>{{ $pay->name }}</td>
                                    <td>{{ $pay->number }}</td>
                                    <td>{{ $pay->banks->name }}</td>
                                    <td>{{ $pay->payment_methods->name }}</td>
                                    <td>
                                        <form action="{{ route('payments.destroy', $pay->id) }}" method="POST">
                                            @csrf
                                            @method('DELETE')
                                            <a href="{{ route('payments.edit', $pay->id) }}" class="btn btn-success" >Edit</a>
                                            <button class="btn btn-danger" onClick="return confirm('Are you sure?')">Delete</button>
                                        </form>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="5" class="text-center">No Data Available</td>
                                </tr>
                                @endforelse
                              </table>
                            </div>
                          </div>
                          <div class="card-footer text-right">
                            <nav class="d-inline-block">

                          {!!$payments->links() !!}
                            </nav>
                          </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection

