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
                <div class="col-lg-12">
                    <div class="card">
                        <form class="needs-validation" method="POST" action="{{ route('payments.update', $payment->id) }}" novalidate="">
                            @csrf
                            @method('PUT')
                            <div class="card-header">
                                <h4>Edit Option Value</h4>
                            </div>
                            <div class="card-body">
                                <div class="form-group row">
                                    <label class="col-sm-2 text-right col-form-label">Account Name : </label>
                                    <div class="col-sm-9">
                                        <input type="text" class="form-control @error('name') is-invalid @enderror" name="name" value="{{ $payment->name }}" required="">
                                        <div class="invalid-feedback">
                                            {{ $errors->has('name') ? $errors->first('name'):"Please enter name of payment" }}
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-sm-2 text-right col-form-label">Account Number : </label>
                                    <div class="col-sm-9">
                                        <input type="text" class="form-control @error('number') is-invalid @enderror" name="number" value="{{ $payment->number }}" required="">
                                        <div class="invalid-feedback">
                                            {{ $errors->has('number') ? $errors->first('number'):"Please enter number of payment" }}
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-sm-2 text-right col-form-label">Bank : </label>
                                    <div class="col-sm-9">
                                        <select name="bank_id" id="bank_id" class="select2 form-control">
                                            @foreach($banks as $bank)
                                                <option value="{{ $bank->id }}" {{ $bank->id == $payment->bank_id ? "selected":""}} >{{ $bank->name }}</option>
                                            @endforeach
                                        </select>
                                        <div class="invalid-feedback">
                                            {{ $errors->has('bank_id') ? $errors->first('bank_id'):"Please select the options above" }}
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="card-footer offset-md-2 text-left">
                                <input type="submit" class="btn btn-primary">
                                <a href="{{ route('payments.index') }}" class="btn btn-default">Back</a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection

