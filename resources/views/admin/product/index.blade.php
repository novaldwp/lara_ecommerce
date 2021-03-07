@extends('layouts.app')

@section('content')
    <section class="section">
        <div class="section-header">
            <h3 class="page__heading">Manage Products</h3>
            <div class="section-header-breadcrumb">
                <div class="breadcrumb-item active"><a href="#">Dashboard</a></div>
                <div class="breadcrumb-item"><a href="#">Manage Products</a></div>
                <div class="breadcrumb-item">Products</div>
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
                            <h4 class="page__heading">List Products</h4>
                            <a href="{{ route('products.create') }}" class="btn btn-success">Add New</a>
                        </div>
                          <div class="card-body">
                            <div class="table-responsive">
                              <table class="table table-bordered table-hovered table-striped table-md">
                                <tr>
                                  <th width="4%">#</th>
                                  <th>Name</th>
                                  <th>Slug</th>
                                  <th width="10%">Image</th>
                                  <th width="15%">Action</th>
                                </tr>
                                @php $i = ($products->currentPage() - 1) * $products->perpage() + 1; @endphp
                                @forelse($products as $prod)
                                <tr>
                                    <td>{{ $i++ }}</td>
                                    <td>{{ $prod->name }}</td>
                                    <td>{{ $prod->slug }}</td>
                                    <td>
                                        <a href="{{ URL::to('uploads/images/'.($prod->image ? 'brands/'.$prod->image : 'no_image.png')) }}" data-lightbox="image-1" alt="{{ $prod->name }}">
                                            <img src="{{ URL::to('uploads/images/'.($prod->image ? 'brands/thumb/'.$prod->image : 'no_image.png')) }}" data-lightbox="image-1" alt="{{ $prod->name }}" width="80px" height="40px">
                                        </a>
                                    <td>
                                        <form action="{{ route('products.destroy', $prod->id) }}" method="POST">
                                            @csrf
                                            @method('DELETE')
                                            <a href="{{ route('products.edit', $prod->id) }}" class="btn btn-success" >Edit</a>
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

                          {!!$products->links() !!}
                            </nav>
                          </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection

@section('page_css')
<link href="{{ asset('vendor/lightbox2/dist/css/lightbox.css') }}" rel="stylesheet" type="text/css"/>
@endsection

@section('page_js')
<script src="{{ asset('vendor/lightbox2/dist/js/lightbox.js') }}"></script>
@endsection

@section('scripts')
<script>
    lightbox.option({
        'disableScrolling': true,
        'showImageNumberLabel': false,
    })
</script>
@endsection
