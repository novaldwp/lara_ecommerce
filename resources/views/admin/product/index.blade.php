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
                                  <th width="20%">Name</th>
                                  <th width="12%">Kategori</th>
                                  <th width="12%">Price</th>
                                  <th width="5%">Status</th>
                                  <th width="10%">Image</th>
                                  <th width="15%">Action</th>
                                </tr>
                                @php $i = ($products->currentPage() - 1) * $products->perpage() + 1; @endphp
                                @forelse($products as $prod)
                                <tr>
                                    <td>{{ $i++ }}</td>
                                    <td>{{ $prod->name }}</td>
                                    <td>{{ $prod->categories->name }}</td>
                                    <td class="text-right">Rp. {{ number_format($prod->price, 0) }}</td>
                                    <td class="text-center"><div class="badge {{ $prod->status == "Active" ? "badge-success":"badge-danger"}}">{{ $prod->status }}</div></td>
                                    <td>
                                        <a href="{{ asset($prod->productimages->path.$prod->productimages->image1) }}" data-lightbox="{{ $prod->slug }}" alt="{{ $prod->name }}">
                                            <img src="{{ asset($prod->productimages->thumb.$prod->productimages->image1) }}" data-lightbox="{{ $prod->slug }}" alt="{{ $prod->name }}" width="80px" height="40px">
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
                                    <td colspan="8" class="text-center">No Data Available</td>
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
<style>
    .lb-nav {
        visibility: hidden;
    }
    .lb-dataContainer {
        visibility: hidden;
    }
    td {
        text-align: center;
        vertical-align: middle;
    }

    tr {
        text-align: center;
    }
</style>
@endsection

@section('css')
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
        'alwaysShowNavOnTouchDevices': false
    })
</script>
@endsection
