@extends('layouts.app')

@section('content')
    <section class="section">
        <div class="section-header">
            <h3 class="page__heading">Manage Payment Methods</h3>
            <div class="section-header-breadcrumb">
                <div class="breadcrumb-item active"><a href="#">Dashboard</a></div>
                <div class="breadcrumb-item"><a href="#">Manage Payments</a></div>
                <div class="breadcrumb-item">Payment Methods</div>
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
                            <h4 class="page__heading">List Payment Methods</h4>
                            <a href="{{ route('payment-methods.create') }}" class="btn btn-success">Add New</a>
                        </div>
                          <div class="card-body">
                            <div class="table-responsive">
                              <table class="table table-bordered table-hovered table-striped table-md">
                                <tr>
                                  <th width="4%">#</th>
                                  <th>Name</th>
                                  <th width="15%">Action</th>
                                </tr>
                                @php $i = ($paymentMethods->currentPage() - 1) * $paymentMethods->perpage() + 1; @endphp
                                @forelse($paymentMethods as $pay)
                                <tr>
                                    <td>{{ $i++ }}</td>
                                    <td>{{ $pay->name }}</td>
                                    <td>
                                        <form action="{{ route('payment-methods.destroy', $pay->id) }}" method="POST">
                                            @csrf
                                            @method('DELETE')
                                            <a href="{{ route('payment-methods.edit', $pay->id) }}" class="btn btn-success" >Edit</a>
                                            <button class="btn btn-danger" onClick="return confirm('Are you sure?')">Delete</button>
                                        </form>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="4" class="text-center">No Data Available</td>
                                </tr>
                                @endforelse
                              </table>
                            </div>
                          </div>
                          <div class="card-footer text-right">
                            <nav class="d-inline-block">

                          {!!$paymentMethods->links() !!}
                            </nav>
                          </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection

