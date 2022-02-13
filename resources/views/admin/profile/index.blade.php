@extends('layouts.app')

@section('title')
    {{ $title }}
@endsection

@section('content')
    <section class="section">
        @include('admin._partial.breadcrumb')
        <div class="section-body">
            <div class="row">
                <div class="col-lg-12">
                    <div class="card">
                        <form class="needs-validation" method="POST" action="{{ route('admin.setting.profile.store') }}" novalidate="" enctype="multipart/form-data">
                            @csrf
                            <div class="card-header">
                                <h4>Profile Toko</h4>
                            </div>
                            <div class="card-body">
                                @include('admin._partial.session')
                                <div class="form-group row">
                                    <label class="col-sm-2 text-right col-form-label">Nama : </label>
                                    <div class="col-sm-9">
                                        <input type="text" class="form-control @error('name') is-invalid @enderror" value="{{ $profile->name ?? "" }}" name="name" required="" autofocus>
                                        <div class="invalid-feedback">
                                            {{ $errors->first('name') }}
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-sm-2 text-right col-form-label">No. Telepon : </label>
                                    <div class="col-sm-9">
                                        <input type="text" class="form-control @error('phone') is-invalid @enderror" value="{{ $profile->phone ?? "" }}" name="phone" required="" autofocus>
                                        <div class="invalid-feedback">
                                            {{ $errors->first('phone') }}
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-sm-2 text-right col-form-label">E-mail : </label>
                                    <div class="col-sm-9">
                                        <input type="text" class="form-control @error('email') is-invalid @enderror" value="{{ $profile->email ?? "" }}" name="email" required="" autofocus>
                                        <div class="invalid-feedback">
                                            {{ $errors->first('email') }}
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-sm-2 text-right col-form-label">Propinsi : </label>
                                    <div class="col-sm-9">
                                        <select name="province_id" id="province_id" class="select2 form-control">
                                            <option value="" disabled selected>Pilih Propinsi </option>
                                            @foreach($provinces as $item)
                                                <option value="{{ $item->id }}" {{ !empty($profile) ? (($profile->province_id == $item->id) ? "selected" : "") : "nay" }}>{{ $item->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-sm-2 text-right col-form-label">Kota : </label>
                                    <div class="col-sm-9">
                                        <select name="city_id" id="city_id" class="select2 form-control" {{ empty($profile) ? "disabled" : "" }}>
                                            <option value="" disabled selected>Pilih Kota</option>
                                            @if (!empty($profile))
                                                @foreach ($cities as $item)
                                                    <option value="{{ $item->id }}" {{ !empty($profile) ? (($profile->city_id == $item->id) ? "selected" : "") : "nay" }}>{{ $item->name }}</option>
                                                @endforeach
                                            @endif
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-sm-2 text-right col-form-label">Kode Pos : </label>
                                    <div class="col-sm-9">
                                        <input type="text" class="form-control @error('postcode') is-invalid @enderror" value="{{ $profile->postcode ?? "" }}" name="postcode" required="">
                                        <div class="invalid-feedback">
                                            {{ $errors->first('postcode') }}
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-sm-2 text-right col-form-label">Alamat : </label>
                                    <div class="col-sm-9">
                                        <textarea name="address" class="form-control @error('address') is-invalid @enderror" id="address" cols="30" rows="50" style="height: 120px;">{{ $profile->address ?? "" }}</textarea>
                                        <div class="invalid-feedback">
                                            {{ $errors->first('street') }}
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-sm-2 text-right col-form-label">Facebook : </label>
                                    <div class="col-sm-9">
                                        <input type="text" class="form-control @error('facebook') is-invalid @enderror" value="{{ $profile->facebook ?? "" }}" name="facebook" required="">
                                        <div class="invalid-feedback">
                                            {{ $errors->first('facebook') }}
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-sm-2 text-right col-form-label">Instagram : </label>
                                    <div class="col-sm-9">
                                        <input type="text" class="form-control @error('instagram') is-invalid @enderror" value="{{ $profile->instagram ?? "" }}" name="instagram" required="">
                                        <div class="invalid-feedback">
                                            {{ $errors->first('instagram') }}
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-sm-2 text-right col-form-label">Twitter : </label>
                                    <div class="col-sm-9">
                                        <input type="text" class="form-control @error('twitter') is-invalid @enderror" value="{{ $profile->twitter ?? "" }}" name="twitter" required="">
                                        <div class="invalid-feedback">
                                            {{ $errors->first('twitter') }}
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-sm-2 text-right col-form-label">Linked In : </label>
                                    <div class="col-sm-9">
                                        <input type="text" class="form-control @error('linkedin') is-invalid @enderror" value="{{ $profile->linkedin ?? "" }}" name="linkedin" required="">
                                        <div class="invalid-feedback">
                                            {{ $errors->first('linkedin') }}
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-sm-2 text-right col-form-label">Gambar : </label>
                                    <div class="col-sm-9">
                                        <div class="box-area @error('image') is-invalid @enderror" id="custom-btn" onclick="imageActive()">
                                            <div class="image">
                                                <img name="image" @if(!empty($profile)) src="{{ asset('uploads/images/profiles/thumb/'. $profile->image) }}" @endif>
                                            </div>
                                            <div class="box-area-icon">
                                                <a href="javscript:void(0);">
                                                    <i class="fa fa-image"></i>
                                                </a>
                                            </div>

                                            <input type="file" name="image" hidden>
                                        </div>
                                    </div>
                                    <div class="image-feedback">
                                        {{ $errors->first('image') }}
                                    </div>
                                </div>
                            </div>
                            <div class="card-footer offset-md-2 text-left">
                                <input type="submit" class="btn btn-primary" name="submitButton" value="Simpan">
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection

@section('page_css')
<link href="{{ asset('css/backend/product.css') }}" rel="stylesheet" type="text/css"/>
@endsection


@section('page_js')
<script src="{{ asset('js/backend/profile.js') }}"></script>
@endsection

@section('scripts')
<script>
    $(document).ready(function() {
        $('#province_id').on('change', function(e) {
            e.preventDefault();
            let id = $(this).val();

            console.log($(this).val());
            $.ajax({
                url: "{{ url('/api/get-city') }}",
                method: "GET",
                dataType: "JSON",
                data: { id:id },
                success: function(res)
                {
                    if(res)
                    {
                        $('#city_id').empty();
                        $('#city_id').append('<option selected disabled>Pilih Kota</option>');
                        $('#city_id').prop('disabled', false);

                        $.each(res, function(key, item) {
                            $('#city_id').append('<option value="'+item.id+'">'+item.name+'</option>')
                        })
                    }
                }
            });
        });
    });
</script>
@endsection
