@extends('layouts.app')

@section('content')
    <section class="section">
        <div class="section-header">
            <h3 class="page__heading">Category</h3>
            <div class="section-header-breadcrumb">
                <div class="breadcrumb-item active"><a href="#">Dashboard</a></div>
                <div class="breadcrumb-item"><a href="#">Manage Products</a></div>
                <div class="breadcrumb-item">Categories</div>
            </div>
        </div>
        <div class="section-body">
            <div class="row">
                <div class="col-lg-12">
                    <div class="card">
                        <form class="needs-validation" method="POST" action="{{ route('categories.store') }}" novalidate="">
                            @csrf
                            <div class="card-header">
                                <h4>Add New Category</h4>
                            </div>
                            <div class="card-body">
                                <div class="form-group row">
                                    <label class="col-sm-2 text-right col-form-label">Name : </label>
                                    <div class="col-sm-9">
                                        <input type="text" class="form-control @error('name') is-invalid @enderror" name="name" required="" autofocus>
                                        <div class="invalid-feedback">
                                            {{ $errors->has('name') ? $errors->first('name'):"Please enter name of category" }}
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-sm-2 text-right col-form-label">Parent : </label>
                                    <div class="col-sm-9">
                                        <select name="parent_id" id="parent_id" class="select2 form-control">
                                            <option value="" disabled selected>-- --</option>
                                            @foreach($categories as $cat)
                                                <option value="{{ $cat->id }}">{{ $cat->name }}</option>
                                                @foreach($cat->child as $catt)
                                                    <option value="{{ $catt->id }}">-- {{ $catt->name }}</option>
                                                    @foreach($catt->child as $cattt)
                                                        <option value="{{ $cattt->id }}">---- {{ $cattt->name }}</option>
                                                    @endforeach
                                                @endforeach
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="card-footer offset-md-2 text-left">
                                <input type="submit" class="btn btn-primary" name="submitButton">
                                <a href="{{ route('categories.index') }}" class="btn btn-default">Back</a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
