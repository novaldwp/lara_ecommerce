@extends('layouts.app')

@section('content')
    <section class="section">
        <div class="section-header">
            <h3 class="page__heading">Manage Warranties</h3>
            <div class="section-header-breadcrumb">
                <div class="breadcrumb-item active"><a href="#">Dashboard</a></div>
                <div class="breadcrumb-item"><a href="#">Manage Products</a></div>
                <div class="breadcrumb-item">Warranties</div>
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
                            <h4 class="page__heading">List Warranties</h4>
                            <a href="{{ route('warranties.create') }}" class="btn btn-success">Add New</a>
                        </div>
                          <div class="card-body">
                            <div class="table-responsive">
                              <table class="table table-bordered table-hovered table-striped table-md">
                                <tr>
                                  <th width="4%">#</th>
                                  <th>Name</th>
                                  <th width="15%">Action</th>
                                </tr>
                                @php $i = ($warranties->currentPage() - 1) * $warranties->perpage() + 1; @endphp
                                @forelse($warranties as $war)
                                <tr>
                                    <td>{{ $i++ }}</td>
                                    <td>{{ $war->name }}</td>
                                    <td>
                                        <form action="{{ route('warranties.destroy', $war->id) }}" method="POST">
                                            @csrf
                                            @method('DELETE')
                                            <a href="{{ route('warranties.edit', $war->id) }}" class="btn btn-success" >Edit</a>
                                            <button class="btn btn-danger" onClick="return confirm('Are you sure?')">Delete</button>
                                        </form>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="3" class="text-center">No Data Available</td>
                                </tr>
                                @endforelse
                              </table>
                            </div>
                          </div>
                          <div class="card-footer text-right">
                            <nav class="d-inline-block">

                          {!!$warranties->links() !!}
                            </nav>
                          </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection

