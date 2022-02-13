@php $string = ""; @endphp
        @if (request()->is('admin/manage-products*'))
            @php
                $string = "Kelola Produk";
            @endphp
        @elseif (request()->is('admin/manage-users*'))
            @php
                $string = "Kelola Pengguna";
            @endphp
        @elseif (request()->is('admin/manage-reports*'))
            @php
                $string = "Kelola Laporan";
            @endphp
        @elseif (request()->is('admin/manage-settings*'))
            @php
                $string = "Kelola Pengaturan";
            @endphp
        @elseif (request()->is('admin/manage-orders*'))
            @php
                $string = "Kelola Order";
            @endphp
        @elseif (request()->is('admin/manage-analyst*'))
            @php
                $string = "Kelola Analisis";
            @endphp
        @endif
        <div class="section-header">
            <h3 class="page__heading">{{ $string }}</h3>
            <div class="section-header-breadcrumb">
                <div class="breadcrumb-item active"><a href="#">Dashboard</a></div>

                {{-- parent breadcrumb --}}
                <div class="breadcrumb-item"><a href="#">{{ $string }}</a></div>
                {{-- end parent breadcrumb --}}

                {{-- child breadcrumb --}}
                    {{-- product --}}
                @if (request()->is('admin/manage-products/product*'))
                    <div class="breadcrumb-item">Produk</div>
                @elseif (request()->is('admin/manage-products/categories*'))
                    <div class="breadcrumb-item">Kategori</div>
                @elseif (request()->is('admin/manage-products/brands*'))
                    <div class="breadcrumb-item">Merk</div>
                    {{-- end product --}}

                    {{-- orders --}}
                @elseif (request()->is('admin/manage-orders/orders*'))
                <div class="breadcrumb-item">Order</div>
                    {{-- end orders --}}

                    {{-- analyst --}}
                @elseif (request()->is('admin/manage-analyst/sentiment-analyses*'))
                <div class="breadcrumb-item">Analisis Sentimen</div>
                @elseif (request()->is('admin/manage-analyst/data-trainings*'))
                <div class="breadcrumb-item">Data Latih</div>
                @elseif (request()->is('admin/manage-analyst/positive-words*'))
                <div class="breadcrumb-item">Kata Dasar Positif</div>
                @elseif (request()->is('admin/manage-analyst/negative-words*'))
                <div class="breadcrumb-item">Kata Dasar Negatif</div>
                    {{-- end analyst --}}

                    {{-- users --}}
                @elseif (request()->is('admin/manage-users/customers*'))
                    <div class="breadcrumb-item">Pembeli</div>
                @elseif (request()->is('admin/manage-users/admins*'))
                    <div class="breadcrumb-item">Petugas</div>
                    {{-- end users --}}

                    {{-- setting --}}
                @elseif (request()->is('admin/manage-settings/profiles*'))
                    <div class="breadcrumb-item">Profile</div>
                    @elseif (request()->is('admin/manage-settings/sliders*'))
                    <div class="breadcrumb-item">Slider</div>
                    {{-- end setting --}}
                @endif
                {{-- end child breadcrumb --}}
            </div>
        </div>
