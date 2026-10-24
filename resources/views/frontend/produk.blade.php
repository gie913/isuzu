@extends('frontend.layouts.app')

@section('css')
<style>
    .product-promo-badge {
        position: absolute;
        top: 15px;
        left: 15px;
        background: #e31e24; /* Isuzu Red */
        color: #fff;
        font-size: 11px;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 1px;
        padding: 4px 12px;
        border-radius: 4px;
        z-index: 2;
        box-shadow: 0 2px 8px rgba(227, 30, 36, 0.4);
        display: inline-flex;
        align-items: center;
        gap: 5px;
    }
    .product-promo-badge i {
        font-size: 10px;
    }
    .product-sidebar-filter {
        background: #fff;
        border-radius: 6px;
        border: 1px solid #e1e6eb;
        padding: 20px;
        box-shadow: 0 5px 20px rgba(0,0,0,0.03);
    }
    .product-sidebar-filter .filter-title {
        font-size: 18px;
        font-weight: 700;
        text-transform: uppercase;
        padding-bottom: 12px;
        border-bottom: 2px solid #e31e24;
        margin-bottom: 20px;
    }
    .product-sidebar-filter .form-group {
        margin-bottom: 16px;
    }
    .product-sidebar-filter label {
        font-size: 13px;
        font-weight: 600;
        color: #333;
        margin-bottom: 6px;
        display: block;
    }
    .product-sidebar-filter .price input.amount {
        border: none !important;
        outline: none !important;
        box-shadow: none !important;
        background: transparent !important;
        padding: 0 !important;
        margin-bottom: 10px !important;
        font-weight: 700 !important;
        color: #e31e24 !important;
        width: 100% !important;
        cursor: default;
    }
</style>
@endsection

@section('content')
    <!-- Content -->
    <div class="page-content bg-white">
        <!-- Inner Banner -->
        <div class="dlab-bnr-inr overlay-black-middle" style="background-image:url({{ asset('assets/images/banner/banner1.jpeg') }});">
            <div class="container">
                <div class="dlab-bnr-inr-entry">
                    <h1 class="text-white">PRODUK ISUZU</h1>
                    <!-- Breadcrumb row -->
                    <div class="breadcrumb-row">
                        <ul class="list-inline">
                            <li><a href="{{ route('index') }}">Beranda</a></li>
                            <li>Produk</li>
                        </ul>
                    </div>
                    <!-- Breadcrumb row END -->
                </div>
            </div>
        </div>
        <!-- Inner Banner END -->

        <!-- Section Produk -->
        <div class="section-full bg-white content-inner recent-vehicle">
            <div class="container">
                <div class="section-head text-center">
                    <h2 class="text-uppercase">DAFTAR <span class="text-primary">PRODUK ISUZU</span></h2>
                    <p>Temukan rangkaian kendaraan niaga dan komersial terbaik dari Isuzu dengan performa mesin tangguh, irit bahan bakar, dan siap mendukung segala kebutuhan bisnis Anda.</p>
                </div>
                <div class="section-content">
                    <div class="row">
                        <!-- Filter Sidebar (Left Filter) -->
                        <div class="col-lg-4 col-md-5 m-b30">
                            <div class="product-sidebar-filter">
                                <h4 class="filter-title"><i class="fas fa-filter text-primary m-r10"></i> Filter Kendaraan</h4>
                                <form action="{{ route('produk') }}" method="GET">
                                    <!-- 1. Jenis Kendaraan -->
                                    <div class="form-group">
                                        <label>Jenis Kendaraan</label>
                                        <div class="selected-box">
                                            <select name="jenis_kendaraan" class="selectpicker form-control" data-live-search="true">
                                                <option value="">Semua Jenis Kendaraan</option>
                                                <option value="cv" {{ request('jenis_kendaraan') == 'cv' ? 'selected' : '' }}>Kendaraan Komersial (CV - Commercial Vehicle)</option>
                                                <option value="lcv" {{ request('jenis_kendaraan') == 'lcv' ? 'selected' : '' }}>Kendaraan Penumpang / Light Commercial Vehicle (LCV)</option>
                                            </select>
                                        </div>
                                    </div>

                                    <!-- 2. Kategori -->
                                    <div class="form-group">
                                        <label>Kategori</label>
                                        <div class="selected-box">
                                            <select name="kategori" class="selectpicker form-control" data-live-search="true">
                                                <option value="">Semua Kategori</option>
                                                <option value="suv_double_cabin" {{ request('kategori') == 'suv_double_cabin' ? 'selected' : '' }}>Kendaraan Penumpang / SUV & Double Cabin (MU-X, D-Max)</option>
                                                <option value="light_truck" {{ request('kategori') == 'light_truck' ? 'selected' : '' }}>Truk Ringan / Light Truck (Elf / N-Series)</option>
                                                <option value="medium_truck" {{ request('kategori') == 'medium_truck' ? 'selected' : '' }}>Truk Sedang / Medium Truck (Giga / F-Series)</option>
                                                <option value="tractor_head" {{ request('kategori') == 'tractor_head' ? 'selected' : '' }}>Truk Berat / Tractor Head (Giga Tractor Head / C&E Series)</option>
                                                <option value="bus_microbus" {{ request('kategori') == 'bus_microbus' ? 'selected' : '' }}>Bus / Microbus (Elf Bus, Traga Bus)</option>
                                                <option value="pickup_logistik" {{ request('kategori') == 'pickup_logistik' ? 'selected' : '' }}>Pick-up / Logistik Ringan (Traga)</option>
                                            </select>
                                        </div>
                                    </div>

                                    <!-- 3. Tipe Karoseri / Aplikasi Bisnis -->
                                    <div class="form-group">
                                        <label>Tipe Karoseri / Aplikasi Bisnis</label>
                                        <div class="selected-box">
                                            <select name="karoseri" class="selectpicker form-control" data-live-search="true">
                                                <option value="">Semua Tipe Karoseri</option>
                                                <option value="box" {{ request('karoseri') == 'box' ? 'selected' : '' }}>Box (Besi/Aluminium)</option>
                                                <option value="bak" {{ request('karoseri') == 'bak' ? 'selected' : '' }}>Bak (Besi/Kayu/Drop Side)</option>
                                                <option value="dump_truck" {{ request('karoseri') == 'dump_truck' ? 'selected' : '' }}>Dump Truck</option>
                                                <option value="tangki" {{ request('karoseri') == 'tangki' ? 'selected' : '' }}>Tangki</option>
                                                <option value="wingbox" {{ request('karoseri') == 'wingbox' ? 'selected' : '' }}>Wingbox</option>
                                                <option value="flat_deck" {{ request('karoseri') == 'flat_deck' ? 'selected' : '' }}>Flat Deck / Logging</option>
                                                <option value="microbus_ambulance" {{ request('karoseri') == 'microbus_ambulance' ? 'selected' : '' }}>Microbus / Ambulance, Crane / Towing, Garbage / Compactor</option>
                                            </select>
                                        </div>
                                    </div>

                                    <!-- 4. Konfigurasi Roda & Penggerak (Axle & Drive) -->
                                    <div class="form-group">
                                        <label>Konfigurasi Roda & Penggerak</label>
                                        <div class="selected-box">
                                            <select name="konfigurasi_roda" class="selectpicker form-control" data-live-search="true">
                                                <option value="">Semua Konfigurasi Roda</option>
                                                <option value="4_roda_4x2" {{ request('konfigurasi_roda') == '4_roda_4x2' ? 'selected' : '' }}>4 Roda (4x2 / Engkel)</option>
                                                <option value="6_roda_4x2" {{ request('konfigurasi_roda') == '6_roda_4x2' ? 'selected' : '' }}>6 Roda (4x2)</option>
                                                <option value="6x2" {{ request('konfigurasi_roda') == '6x2' ? 'selected' : '' }}>6x2 (Tronton banci)</option>
                                                <option value="6x4" {{ request('konfigurasi_roda') == '6x4' ? 'selected' : '' }}>6x4 (Tronton Heavy Duty / Off-road)</option>
                                                <option value="4x4" {{ request('konfigurasi_roda') == '4x4' ? 'selected' : '' }}>4x4 (Rigid / Double Cabin)</option>
                                            </select>
                                        </div>
                                    </div>

                                    <!-- 5. Gross Vehicle Weight (GVW) / Bobot Muatan -->
                                    <div class="form-group">
                                        <label>GVW / Bobot Muatan</label>
                                        <div class="selected-box">
                                            <select name="gvw" class="selectpicker form-control" data-live-search="true">
                                                <option value="">Semua GVW / Bobot Muatan</option>
                                                <option value="5-10" {{ request('gvw') == '5-10' ? 'selected' : '' }}>5 – 10 Ton</option>
                                                <option value="10-16" {{ request('gvw') == '10-16' ? 'selected' : '' }}>10 – 16 Ton</option>
                                                <option value="gt16" {{ request('gvw') == 'gt16' ? 'selected' : '' }}>&gt; 16 Ton</option>
                                            </select>
                                        </div>
                                    </div>

                                    <!-- 6. Panjang Sasis / Jarak Sumbu Roda (Wheelbase) -->
                                    <div class="form-group">
                                        <label>Panjang Sasis (Wheelbase)</label>
                                        <div class="selected-box">
                                            <select name="wheelbase" class="selectpicker form-control" data-live-search="true">
                                                <option value="">Semua Jarak Sumbu Roda</option>
                                                <option value="swb" {{ request('wheelbase') == 'swb' ? 'selected' : '' }}>Pendek (Short Wheelbase / SWB)</option>
                                                <option value="mwb" {{ request('wheelbase') == 'mwb' ? 'selected' : '' }}>Sedang (Medium Wheelbase / MWB)</option>
                                                <option value="lwb" {{ request('wheelbase') == 'lwb' ? 'selected' : '' }}>Panjang (Long Wheelbase / LWB / Extra Long)</option>
                                            </select>
                                        </div>
                                    </div>

                                    <!-- 7. Rentang Tenaga -->
                                    <div class="form-group">
                                        <label>Rentang Tenaga</label>
                                        <div class="selected-box">
                                            <select name="tenaga" class="selectpicker form-control" data-live-search="true">
                                                <option value="">Semua Rentang Tenaga</option>
                                                <option value="100-150" {{ request('tenaga') == '100-150' ? 'selected' : '' }}>100 – 150 PS</option>
                                                <option value="150-240" {{ request('tenaga') == '150-240' ? 'selected' : '' }}>150 – 240 PS</option>
                                                <option value="240-285" {{ request('tenaga') == '240-285' ? 'selected' : '' }}>240 – 285 PS</option>
                                                <option value="300+" {{ request('tenaga') == '300+' ? 'selected' : '' }}>300+ PS</option>
                                            </select>
                                        </div>
                                    </div>

                                    <!-- 8. Kapasitas Penumpang -->
                                    <div class="form-group">
                                        <label>Kapasitas Penumpang</label>
                                        <div class="selected-box">
                                            <select name="kapasitas_penumpang" class="selectpicker form-control" data-live-search="true">
                                                <option value="">Semua Kapasitas</option>
                                                <option value="2" {{ request('kapasitas_penumpang') == '2' ? 'selected' : '' }}>2 Seater</option>
                                                <option value="5" {{ request('kapasitas_penumpang') == '5' ? 'selected' : '' }}>5 Seater</option>
                                                <option value="7" {{ request('kapasitas_penumpang') == '7' ? 'selected' : '' }}>7 Seater (MU-X)</option>
                                            </select>
                                        </div>
                                    </div>

                                    <!-- 9. Rentang Harga (Slider) -->
                                    <div class="form-group">
                                        <label>Rentang Harga</label>
                                        <div class="price-slide-2 range-slider">
                                            <div class="price">
                                                <input type="text" id="amount-2" name="rentang_harga" class="amount" readonly="" value="Rp 200 Juta - Rp 1.5 Milyar" />
                                                <div id="slider-range-2"></div>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Submit & Reset -->
                                    <div class="m-t20">
                                        <button type="submit" class="site-button w-100 m-b10" style="display: flex; align-items: center; justify-content: center; gap: 8px;">
                                            <i class="fa fa-search"></i> Terapkan Filter
                                        </button>
                                        <a href="{{ route('produk') }}" class="site-button-secondry outline w-100 text-center">Reset Filter</a>
                                    </div>
                                </form>
                            </div>
                        </div>

                        <!-- Product List (Masonry Grid) -->
                        <div class="col-lg-8 col-md-7">
                            @php
                                $products = [
                                    [
                                        'name' => 'ISUZU ELF MICROBUS NEW ARMADA 20 SEAT',
                                        'power' => '120 PS',
                                        'price' => 'Rp 654.000.000',
                                        'promo' => true,
                                        'image' => asset('assets/images/product/product1.jpg'),
                                        'category' => 'Bus / Microbus',
                                    ],
                                    [
                                        'name' => 'ISUZU ELF NMR L',
                                        'power' => '150 PS',
                                        'price' => 'Rp 522.000.000',
                                        'promo' => true,
                                        'image' => asset('assets/images/product/product2.jpg'),
                                        'category' => 'Light Truck',
                                    ],
                                    [
                                        'name' => 'ISUZU TRAGA FD - WHITE',
                                        'power' => '80 PS',
                                        'price' => 'Rp 297.000.000',
                                        'promo' => false,
                                        'image' => asset('assets/images/product/product3.jpg'),
                                        'category' => 'Pick-up / Logistik',
                                    ],
                                    [
                                        'name' => 'ISUZU ELF NLR',
                                        'power' => '120 PS',
                                        'price' => 'Rp 433.000.000',
                                        'promo' => false,
                                        'image' => asset('assets/images/product/product4.jpg'),
                                        'category' => 'Light Truck',
                                    ],
                                    [
                                        'name' => 'ISUZU ELF NLR L',
                                        'power' => '120 PS',
                                        'price' => 'Rp 450.000.000',
                                        'promo' => false,
                                        'image' => asset('assets/images/product/product5.jpg'),
                                        'category' => 'Light Truck',
                                    ],
                                    [
                                        'name' => 'ISUZU TRAGA BLACK PREMIUM AC',
                                        'power' => '80 PS',
                                        'price' => 'Rp 308.000.000',
                                        'promo' => false,
                                        'image' => asset('assets/images/product/product6.jpg'),
                                        'category' => 'Pick-up / Logistik',
                                    ],
                                ];
                            @endphp

                            <ul id="masonry" class="row dlab-gallery-listing gallery-grid-4 gallery lightgallery m-b0">
                                @foreach($products as $item)
                                <li class="card-container col-lg-6 col-md-12 col-sm-6 col-12 m-b30">
                                    <div class="dlab-box dlab-gallery-box" style="position: relative; border: 1px solid #eee; border-radius: 4px; overflow: hidden; background: #fff;">
                                        @if(!empty($item['promo']))
                                            <span class="product-promo-badge"><i class="fas fa-tag"></i> Promo</span>
                                        @endif
                                        <div class="dlab-media dlab-img-overlay1 dlab-img-effect zoom-slow"> 
                                            <a href="{{ route('detail-produk') }}"><img src="{{ $item['image'] }}" alt="{{ $item['name'] }}" style="height: 230px; width: 100%; object-fit: cover;"></a>
                                            <div class="overlay-bx">
                                                <div class="overlay-icon"> 
                                                    <a href="{{ route('detail-produk') }}">
                                                        <i class="fas fa-link icon-bx-xs"></i> 
                                                    </a> 
                                                    <span data-exthumbimage="{{ $item['image'] }}" data-src="{{ $item['image'] }}" class="far fa-image icon-bx-xs check-km" title="{{ $item['name'] }}"></span>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="ow-entry-content text-center p-a20">
                                            <h4 class="font-weight-600 m-b15" style="min-height: 48px; font-size: 15px; line-height: 22px;">
                                                <a href="{{ route('detail-produk') }}">{{ $item['name'] }}</a>
                                            </h4>
                                            <div class="car-part-list m-b15">
                                                <ul class="list-inline" style="margin-bottom: 0;">
                                                    <li><i class="fas fa-tachometer-alt text-primary m-r5"></i> {{ $item['power'] }}</li>
                                                    <li><i class="fas fa-truck text-primary m-r5"></i> Euro 4</li>
                                                </ul>
                                            </div>
                                            <div class="service-price p-t10" style="border-top: 1px dashed #eee;">
                                                <span class="service-new-price text-primary font-weight-700" style="font-size: 18px;">{{ $item['price'] }}</span>
                                            </div>
                                        </div>								
                                    </div>
                                </li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- Section Produk END -->
    </div>
    <!-- Content END -->
@endsection

@section('js')
<script>
jQuery(document).ready(function($) {
    if ($("#slider-range-2").length > 0) {
        function formatRupiah(val) {
            if (val >= 1000) {
                var milyar = (val / 1000).toFixed(val % 1000 === 0 ? 0 : 1);
                return "Rp " + milyar + " Milyar";
            }
            return "Rp " + val + " Juta";
        }

        $("#slider-range-2").slider({
            range: true,
            min: 150,    // 150 Juta
            max: 2000,   // 2 Milyar
            step: 25,    // Kelipatan 25 Juta
            values: [200, 1500],
            slide: function(event, ui) {
                $("#amount-2").val(formatRupiah(ui.values[0]) + " - " + formatRupiah(ui.values[1]));
            }
        });

        $("#amount-2").val(formatRupiah(200) + " - " + formatRupiah(1500));
    }
});
</script>
@endsection
