@extends('layouts.app')

@section('css')
<link href="https://cdn.datatables.net/1.10.16/css/jquery.dataTables.min.css" rel="stylesheet">
<link href="https://cdn.datatables.net/1.10.19/css/dataTables.bootstrap4.min.css" rel="stylesheet">
@endsection

@php
    use App\Services\SentimenService;

    $test = SentimenService::tokenizing(SentimenService::stemming(SentimenService::stopword(SentimenService::casefolding("Laptopnya bagus banget dipake buat main game dan berfungsi dengan baik"))));

    $res = join(" ", $test);
@endphp
@section('content')
    <section class="section">
        <div class="section-body mt-4">
            <div class="row">
                <div class="col-lg-12">
                    <div class="card">
                        <div class="card-body">
                            @if (session('success'))
                                <div class="alert alert-success alert-dismissible fade show" role="alert">{{ session('success') }}</div>
                            @elseif (session('error'))
                                <div class="alert alert-error alert-dismissible fade show" role="alert">{{ session('error') }}</div>
                            @endif
                            <div class="table-responsive">
                                <table class="table table-bordered table-hovered table-striped table-md" id="data-table">
                                    <thead>
                                        <tr>
                                        <th width="5%">#</th>
                                        <th>Data Preprocessing</th>
                                        <th width="20%">Kelas</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td>1</td>
                                            {{-- <td>Produknya BAGUS dan berfungsi dengan sangat baik terima kasih !@#$!</td> --}}
                                            {{-- <td>{{ SentimenService::cleansing("Produknya BAGUS dan berfungsi dengan sangat baik terima kasih")}}</td> --}}
                                            {{-- <td>{{ SentimenService::casefolding("Produknya BAGUS BANGET dan berfungsi dengan sangat baik terima kasih") }}</td> --}}
                                            {{-- <td>{{ SentimenService::stopword(SentimenService::casefolding("Produknya BAGUS BANGET dan berfungsi dengan sangat baik terima kasih")) }}</td> --}}
                                            {{-- <td>{{ SentimenService::stemming(SentimenService::stopword(SentimenService::casefolding("Produknya BAGUS BANGET dan berfungsi dengan sangat baik terima kasih"))) }}</td> --}}
                                            <td>[{{ join("] [", SentimenService::tokenizing(SentimenService::stemming(SentimenService::stopword(SentimenService::casefolding("Produknya BAGUS BANGET dan berfungsi dengan sangat baik terima kasih"))))) }}]</td>
                                            <td><span class="badge badge-success">Positif</span></td>
                                        </tr>
                                        <tr>
                                            <td>2</td>
                                            {{-- <td>PESAN KAMERA yang dateng malah BARANG LAIN KECEWA PARAH</td> --}}
                                            {{-- <td>{{ SentimenService::cleansing("PESAN KAMERA yang dateng malah BARANG LAIN KECEWA PARAH")}}</td> --}}
                                            {{-- <td>{{ SentimenService::casefolding("PESAN KAMERA yang dateng malah BARANG LAIN KECEWA PARAH") }}</td> --}}
                                            {{-- <td>{{ SentimenService::stopword(SentimenService::casefolding("PESAN KAMERA yang dateng malah BARANG LAIN KECEWA PARAH")) }}</td> --}}
                                            {{-- <td>{{ SentimenService::stemming(SentimenService::stopword(SentimenService::casefolding("PESAN KAMERA yang dateng malah BARANG LAIN KECEWA PARAH"))) }}</td> --}}
                                            <td>[{{ join(" ] [", SentimenService::tokenizing(SentimenService::stemming(SentimenService::stopword(SentimenService::casefolding("PESAN KAMERA yang dateng malah BARANG LAIN KECEWA PARAH"))))) }}]</td>
                                            <td><span class="badge badge-danger">Negatif</span></td>
                                        </tr>
                                        <tr>
                                            <td>3</td>
                                            {{-- <td>Mantap gan produknya udah sampe dengan aman bagus pula hasilnya makasih ya gan</td> --}}
                                            {{-- <td>{{ SentimenService::cleansing("Mantap gan produknya udah sampe dengan aman bagus pula hasilnya makasih ya gan")}}</td> --}}
                                            {{-- <td>{{ SentimenService::casefolding("Mantap gan produknya udah sampe dengan aman bagus pula hasilnya makasih ya gan") }}</td> --}}
                                            {{-- <td>{{ SentimenService::stopword(SentimenService::casefolding("Mantap gan produknya udah sampe dengan aman bagus pula hasilnya makasih ya gan")) }}</td> --}}
                                            {{-- <td>{{ SentimenService::stemming(SentimenService::stopword(SentimenService::casefolding("Mantap gan produknya udah sampe dengan aman bagus pula hasilnya makasih ya gan"))) }}</td> --}}
                                            <td>[{{ join(" ] [", SentimenService::tokenizing(SentimenService::stemming(SentimenService::stopword(SentimenService::casefolding("Mantap gan produknya udah sampe dengan aman bagus pula hasilnya makasih ya gan"))))) }}]</td>
                                            <td><span class="badge badge-success">Positif</span></td>
                                        </tr>
                                        <tr>
                                            <td>4</td>
                                            {{-- <td>SSDnya berfungsi dengan baik kondisinya masih bagus dan mulus</td> --}}
                                            {{-- <td>{{ SentimenService::cleansing("SSDnya berfungsi dengan baik kondisinya masih bagus dan mulus")}}</td> --}}
                                            {{-- <td>{{ SentimenService::casefolding("SSDnya berfungsi dengan baik kondisinya masih bagus dan mulus") }}</td> --}}
                                            {{-- <td>{{ SentimenService::stopword(SentimenService::casefolding("SSDnya berfungsi dengan baik kondisinya masih bagus dan mulus")) }}</td> --}}
                                            {{-- <td>{{ SentimenService::stemming(SentimenService::stopword(SentimenService::casefolding("SSDnya berfungsi dengan baik kondisinya masih bagus dan mulus"))) }}</td> --}}
                                            <td>[{{  join(" ] [", SentimenService::tokenizing(SentimenService::stemming(SentimenService::stopword(SentimenService::casefolding("SSDnya berfungsi dengan baik kondisinya masih bagus dan mulus"))))) }}]</td>
                                            <td><span class="badge badge-success">Positif</span></td>
                                        </tr>
                                        <tr>
                                            <td>...</td>
                                            <td>...</td>
                                            <td>...</td>
                                        </tr>
                                        <tr>
                                            <td>32</td>
                                            {{-- <td>Sumpah kecewa banget kameranya buramm nyesal gue beli untung packingnya rapih mulus</td> --}}
                                            {{-- <td>{{ SentimenService::cleansing("Sumpah kecewa banget kameranya buramm nyesal gue beli untung packingnya rapih mulus")}}</td> --}}
                                            {{-- <td>{{ SentimenService::casefolding("Sumpah kecewa banget kameranya buramm nyesal gue beli untung packingnya rapih mulus") }}</td> --}}
                                            {{-- <td>{{ SentimenService::stopword(SentimenService::casefolding("Sumpah kecewa banget kameranya buramm nyesal gue beli untung packingnya rapih mulus")) }}</td> --}}
                                            {{-- <td>{{ SentimenService::stemming(SentimenService::stopword(SentimenService::casefolding("Sumpah kecewa banget kameranya buramm nyesal gue beli untung packingnya rapih mulus"))) }}</td> --}}
                                            <td>[{{  join(" ] [", SentimenService::tokenizing(SentimenService::stemming(SentimenService::stopword(SentimenService::casefolding("Sumpah kecewa banget kameranya buramm nyesal gue beli untung packingnya rapih mulus"))))) }}]</td>
                                            <td><span class="badge badge-danger">Negatif</span></td>
                                            {{-- <td>sumpah kecewa banget kamera buramm nyesal gue beli untung packing rapih mulus</td> --}}
                                        </tr>
                                        <tr>
                                            <td>33</td>
                                            {{-- <td>Wahhh laptopnya mantep banget bagus dan berfungsi dengan baik tapi bautnya ada yang rusak</td> --}}
                                            {{-- <td>{{ SentimenService::cleansing("Wahhh laptopnya mantep banget bagus dan berfungsi dengan baik tapi bautnya ada yang rusak")}}</td> --}}
                                            {{-- <td>{{ SentimenService::casefolding("Wahhh laptopnya mantep banget bagus dan berfungsi dengan baik tapi bautnya ada yang rusak") }}</td> --}}
                                            {{-- <td>{{ SentimenService::stopword(SentimenService::casefolding("Wahhh laptopnya mantep banget bagus dan berfungsi dengan baik tapi bautnya ada yang rusak")) }}</td> --}}
                                            {{-- <td>{{ SentimenService::stemming(SentimenService::stopword(SentimenService::casefolding("Wahhh laptopnya mantep banget bagus dan berfungsi dengan baik tapi bautnya ada yang rusak"))) }}</td> --}}
                                            <td>[{{  join(" ] [", SentimenService::tokenizing(SentimenService::stemming(SentimenService::stopword(SentimenService::casefolding("Wahhh laptopnya mantep banget bagus dan berfungsi dengan baik tapi bautnya ada yang rusak"))))) }}]</td>
                                            <td><span class="badge badge-success">Positif</span></td>
                                        </tr>
                                        <tr>
                                            <td>34</td>
                                            {{-- <td>bagus lah mantap kualitas nya canggih puas deh pokoknya</td> --}}
                                            {{-- <td>{{ SentimenService::cleansing("bagus lah mantap kualitas nya canggih puas deh pokoknya")}}</td> --}}
                                            {{-- <td>{{ SentimenService::casefolding("bagus lah mantap kualitas nya canggih puas deh pokoknya") }}</td> --}}
                                            {{-- <td>{{ SentimenService::stopword(SentimenService::casefolding("bagus lah mantap kualitas nya canggih puas deh pokoknya")) }}</td> --}}
                                            {{-- <td>{{ SentimenService::stemming(SentimenService::stopword(SentimenService::casefolding("bagus lah mantap kualitas nya canggih puas deh pokoknya"))) }}</td> --}}
                                            <td>[{{  join(" ] [", SentimenService::tokenizing(SentimenService::stemming(SentimenService::stopword(SentimenService::casefolding("bagus lah mantap kualitas nya canggih puas deh pokoknya"))))) }}]</td>
                                            <td><span class="badge badge-success">Positif</span></td>
                                        </tr>
                                        <tr>
                                            <td>35</td>
                                            {{-- <td>Laptopnya bagus banget dipake buat main game dan berfungsi dengan baik</td> --}}
                                            {{-- <td>{{ SentimenService::cleansing("Laptopnya bagus banget dipake buat main game dan berfungsi dengan baik")}}</td> --}}
                                            {{-- <td>{{ SentimenService::casefolding("Laptopnya bagus banget dipake buat main game dan berfungsi dengan baik") }}</td> --}}
                                            {{-- <td>{{ SentimenService::stopword(SentimenService::casefolding("Laptopnya bagus banget dipake buat main game dan berfungsi dengan baik")) }}</td> --}}
                                            {{-- <td>{{ SentimenService::stemming(SentimenService::stopword(SentimenService::casefolding("Laptopnya bagus banget dipake buat main game dan berfungsi dengan baik"))) }}</td> --}}
                                            <td>[{{  join(" ] [", SentimenService::tokenizing(SentimenService::stemming(SentimenService::stopword(SentimenService::casefolding("Laptopnya bagus banget dipake buat main game dan berfungsi dengan baik"))))) }}]</td>
                                            <td><span class="badge badge-success">Positif</span></td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
