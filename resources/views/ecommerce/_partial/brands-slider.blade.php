
<div class="brand">
    <div class="container-fluid">
        <div class="brand-slider">
            @forelse ($brandsSlider as $brand)
                <div class="brand-item"><img src="{{ asset('uploads/images/brands/thumb/'.$brand->image) }}" height="80" width="150" alt=""></div>
            @empty
                @for($i=0; $i<6; $i++)
                    <div class="brand-item"><img src="{{ asset('uploads/images/no_image.png') }}" height="80" width="150" alt=""></div>
                @endfor
            @endforelse
        </div>
    </div>
</div>
