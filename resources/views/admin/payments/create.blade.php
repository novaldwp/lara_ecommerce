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
                        <form class="needs-validation" method="POST" action="{{ route('payments.store') }}" novalidate="">
                            @csrf
                            <div class="card-header">
                                <h4>Add New Payments</h4>
                            </div>
                            <div class="card-body">
                                <div class="form-group row">
                                    <label class="col-sm-2 text-right col-form-label">Payment Method : </label>
                                    <div class="col-sm-9">
                                        <select name="payment_method_id" id="payment_method_id" class="select2 form-control" required>
                                            <option value="" disabled selected>-- --</option>
                                            @foreach($paymentMethods as $pay)
                                                <option value="{{ $pay->id }}" {{ old('payment_method_id') == $pay->id ? "selected":"" }}>{{ $pay->name }}</option>
                                            @endforeach
                                        </select>
                                        <div class="invalid-feedback">
                                            {{ $errors->has('payment_method_id') ? $errors->first('payment_method_id'):"Please select the options above" }}
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-sm-2 text-right col-form-label">Bank : </label>
                                    <div class="col-sm-9">
                                        <select name="bank_id" id="bank_id" class="select2 form-control" required>
                                            <option value="" disabled selected>-- --</option>
                                            @foreach($banks as $bank)
                                                <option value="{{ $bank->id }}" {{ old('bank_id') == $bank->id ? "selected":"" }}>{{ $bank->name }}</option>
                                            @endforeach
                                        </select>
                                        <div class="invalid-feedback">
                                            {{ $errors->has('bank_id') ? $errors->first('bank_id'):"Please select the options above" }}
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-sm-2 text-right col-form-label">Account Name : </label>
                                    <div class="col-sm-9">
                                        <input type="text" class="form-control @error('name') is-invalid @enderror" name="name" value="{{ old('name') }}" required="">
                                        <div class="invalid-feedback">
                                            {{ $errors->has('name') ? $errors->first('name'):"Please enter name of option value" }}
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-sm-2 text-right col-form-label">Account Number : </label>
                                    <div class="col-sm-9">
                                        <input type="text" class="form-control @error('number') is-invalid @enderror" name="number" value="{{ old('number') }}" oninput="this.value = this.value.replace(/[^0-9]/g, '').replace(/(\..*)\./g, '$1');" required="">
                                        <div class="invalid-feedback">
                                            {{ $errors->has('number') ? $errors->first('number'):"Please enter number of option value" }}
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group row has-error">
                                    <label class="col-sm-2 text-right col-form-label">QR Code : </label>
                                    <div class="col-sm-9">
                                        <input type="file" class="form-control @error('qr_code') is-invalid @enderror" name="image">
                                        <div class="invalid-feedback">
                                            {{ $errors->has('qr_code') ? $errors->first('qr_code'):"Please select the valid image" }}
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

@section('scripts')
<script>
    $(document).ready(function(){
        $("#bank_id").select2({ width: '100%' });
        $("#payment_method_id").select2({ width: '100%' });
        $('#payment_method_id').focus();
    });
</script>
@endsection
