@extends('layouts.front.app')

@section('content')
<!-- Breadcrumb Start -->
<div class="breadcrumb-wrap">
    <div class="container-fluid">
        <ul class="breadcrumb">
            <li class="breadcrumb-item"><a href="#">Home</a></li>
            <li class="breadcrumb-item"><a href="#">Address</a></li>
            <li class="breadcrumb-item active">Add New Address</li>
        </ul>
    </div>
</div>
<!-- Breadcrumb End -->
<div class="my-account">
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <h4>Add New Address</h4>
                <form action="{{ route('ecommerce.address.update', $address->id) }}" method="post">
                    @csrf
                    @method('PATCH')
                    <div class="row">
                        <div class="col-md-12">
                            <input type="hidden" name="member_id" id="member_id" value="{{ $address->member_id }}">
                            <input type="hidden" name="city_ids" id="city_ids" value="{{ $address->city_id }}">
                            <input class="form-control @error('name') is-invalid @enderror" type="text" name="name"
                            value="{{ $address->name }}" placeholder="Name Address (e.g Home)" required>
                            <div class="invalid-feedback mb-3">
                                {{ $errors->has('name') ? $errors->first('name') : "" }}
                            </div>
                        </div>
                        <div class="col-md-6">
                            <select name="province_id" id="province_id" class="form-control @error('province_id') is-invalid @enderror">
                                <option value="" selected disabled>Select Province</option>
                                @forelse ($provinces as $row)
                                    <option value="{{ $row->id }}" {{ ($row->id == $address->id) ? "selected":"" }}>{{ $row->name }}</option>
                                @empty
                                    <option disabled> -- Data Provinces Not Found --</option>
                                @endforelse
                            </select>
                            <div class="invalid-feedback mb-3">
                                {{ $errors->has('province_id') ? $errors->first('province_id') : "" }}
                            </div>
                        </div>
                        <div class="col-md-6">
                            <select name="city_id" id="city_id" class="form-control  @error('city_id') is-invalid @enderror" disabled>
                                <option value="" selected disabled>Select City</option>
                            </select>
                            <div class="invalid-feedback mb-3">
                                {{ $errors->has('city_id') ? $errors->first('city_id') : "" }}
                            </div>
                        </div>
                        <div class="col-md-12">
                            <input class="form-control @error('postcode') is-invalid @enderror" type="text" name="postcode"
                            value="{{ $address->postcode }}" placeholder="Postal Code" required>
                            <div class="invalid-feedback mb-3">
                                {{ $errors->has('postcode') ? $errors->first('postcode') : "" }}
                            </div>
                        </div>
                        <div class="col-md-12">
                            <textarea class="form-control @error('street') is-invalid @enderror" name="street" id="street" rows="5" required>{{ $address->street }}</textarea>
                            <div class="invalid-feedback mb-3">
                                {{ $errors->has('street') ? $errors->first('street') : "" }}
                            </div>
                        </div>
                        <div class="col-md-12 ml-4 mb-3">
                            <input class="form-check-input" type="checkbox" value="1" name="is_default" id="is_default" {{ ($address->is_default == 1) ? "checked":"" }}>
                            <label class="form-check-label" for="is_default">
                            Main Address
                            </label>
                        </div>
                        <div class="col-md-12">
                            <button class="btn">Submit</button>
                            <br><br>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    $(document).ready(function() {

        getCityValue();

        function getCityValue()
        {
            let province = $('#province_id').val();
            let city = $('#city_ids').val();
            let member = $('#member_id').val();

            $.ajax({
                url: "{{ url('/api/get-city/selected-province') }}",
                method: "GET",
                dataType: "JSON",
                data: {province:province, city:city, member:member},
                success: function(res)
                {
                    $('#city_id').empty();
                    $('#city_id').append('<option disabled>Select City</option>');
                    $('#city_id').prop('disabled', false);

                    $.each(res.cities, function(key, item) {
                        $('#city_id').append('<option value="'+item.id+'" '+(item.id == res.address.city_id ? "selected":"")+'>'+item.name+'</option>');
                    })
                }
            })
        }

        $('#province_id').on('change', function(e) {
            e.preventDefault();
            let id = $(this).val();

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
                        $('#city_id').append('<option selected disabled>Select City</option>');
                        $('#city_id').prop('disabled', false);

                        $.each(res, function(key, item) {
                            $('#city_id').append('<option value="'+item.id+'">'+item.name+'</option>')
                        })
                    }
                }
            })
        })
    })
</script>
@endsection
