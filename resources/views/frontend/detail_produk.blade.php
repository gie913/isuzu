@extends('frontend.layouts.app')

@section('content')
    <!-- Content -->
    <div class="page-content bg-white">
        <!-- Inner Banner -->
        <div class="dlab-bnr-inr overlay-black-middle" style="background-image:url({{ asset('assets/images/banner/banner1.jpeg') }});">
            <div class="container">
                <div class="dlab-bnr-inr-entry">
                    <h1 class="text-white">DETAIL PRODUK</h1>
                    <!-- Breadcrumb row -->
                    <div class="breadcrumb-row">
                        <ul class="list-inline">
                            <li><a href="{{ route('index') }}">Beranda</a></li>
                            <li><a href="{{ route('produk') }}">Produk</a></li>
                            <li>Detail Produk</li>
                        </ul>
                    </div>
                    <!-- Breadcrumb row END -->
                </div>
            </div>
        </div>
        <!-- Inner Banner END -->

        <!-- Product Detail Section -->
        <div class="section-full content-inner bg-white">
            <div class="container">
                <div class="row m-b40">
                    <!-- Product Image -->
                    <div class="col-lg-6 col-md-12 m-b30">
                        <div class="dlab-box" style="border: 1px solid #e1e6eb; border-radius: 8px; overflow: hidden; box-shadow: 0 4px 15px rgba(0,0,0,0.05);">
                            <div class="dlab-media">
                                <img src="{{ asset('assets/images/product/elf-nlr-detail.jpg') }}" alt="Isuzu ELF NLR & NLR L" style="width: 100%; height: auto; display: block;">
                            </div>
                        </div>
                    </div>

                    <!-- Product Header & Quick Action -->
                    <div class="col-lg-6 col-md-12 m-b30 align-self-center">
                        <div class="p-l15">
                            <span class="badge badge-danger m-b10" style="background: #e31e24; color: #fff; font-size: 12px; padding: 5px 12px; border-radius: 4px;">EURO 4 READY</span>
                            <h2 class="text-uppercase font-weight-700 m-b10" style="font-size: 28px; line-height: 36px;">ISUZU ELF NLR / NLR L</h2>
                            <h5 class="text-muted font-weight-400 m-b20">Untung Maksimal: Lebih Irit dan Lebih Bertenaga untuk Logistik Andal</h5>
                            
                            <div class="m-b25 p-a20" style="background: #fdf5f5; border-left: 4px solid #e31e24; border-radius: 4px;">
                                <div class="text-muted" style="font-size: 13px; text-transform: uppercase; font-weight: 600;">Harga Mulai Dari</div>
                                <div class="text-primary font-weight-700" style="font-size: 32px; line-height: 40px;">
                                    Rp 433.000.000 <span style="font-size: 14px; color: #777; font-weight: 400;">(Off / On The Road)</span>
                                </div>
                            </div>

                            <div class="row g-2 m-b20">
                                <div class="col-sm-6 m-b10">
                                    <a href="https://wa.me/6281318941813?text=Halo%20saya%20tertarik%20dengan%20Isuzu%20ELF%20NLR,%20mohon%20info%20simulasi%20kredit%20dan%20penawaran" target="_blank" class="site-button btn-block" style="padding: 12px; font-weight: 600; display: flex; align-items: center; justify-content: center; gap: 8px;">
                                        <i class="fab fa-whatsapp fa-lg"></i> Konsultasi via WhatsApp
                                    </a>
                                </div>
                                <div class="col-sm-6 m-b10">
                                    <a href="https://isuzujakarta.com/assets/files/brosur/PRICE_LIST_UPDATE_-_AGUSTUS_2026.pdf" target="_blank" download class="site-button-secondry outline btn-block" style="padding: 12px; font-weight: 600; display: flex; align-items: center; justify-content: center; gap: 8px;">
                                        <i class="fas fa-file-pdf"></i> Unduh Brosur PDF
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Spesifikasi Teknis (Berdasarkan Kategori Filter) -->
                <div class="row m-b40">
                    <div class="col-12">
                        <div class="section-head text-left m-b25">
                            <h3 class="text-uppercase font-weight-700">Spesifikasi Kendaraan</h3>
                            <div class="dlab-separator bg-primary"></div>
                        </div>

                        <div class="table-responsive bg-white" style="border: 1px solid #e1e6eb; border-radius: 8px; overflow: hidden;">
                            <table class="table table-bordered table-striped m-b0" style="font-size: 14px;">
                                <tbody>
                                    <tr>
                                        <th style="width: 35%; background: #f8f9fa;">Jenis Kendaraan</th>
                                        <td>Kendaraan Komersial (CV - Commercial Vehicle)</td>
                                    </tr>
                                    <tr>
                                        <th style="background: #f8f9fa;">Kategori</th>
                                        <td>Truk Ringan / Light Truck (Elf / N-Series)</td>
                                    </tr>
                                    <tr>
                                        <th style="background: #f8f9fa;">Tipe Karoseri / Aplikasi Bisnis</th>
                                        <td>Box (Besi / Aluminium), Bak (Besi / Kayu / Drop Side), Flat Deck, Microbus / Ambulance</td>
                                    </tr>
                                    <tr>
                                        <th style="background: #f8f9fa;">Konfigurasi Roda & Penggerak (Axle & Drive)</th>
                                        <td>4 Roda (4x2 / Engkel)</td>
                                    </tr>
                                    <tr>
                                        <th style="background: #f8f9fa;">Gross Vehicle Weight (GVW) / Bobot Muatan</th>
                                        <td>5.1 Ton (5 – 10 Ton)</td>
                                    </tr>
                                    <tr>
                                        <th style="background: #f8f9fa;">Panjang Sasis / Jarak Sumbu Roda (Wheelbase)</th>
                                        <td>
                                            • NLR: 2.490 mm (Pendek / Short Wheelbase)<br>
                                            • NLR L: 3.360 mm (Panjang / Long Wheelbase - Ekstra Muatan)
                                        </td>
                                    </tr>
                                    <tr>
                                        <th style="background: #f8f9fa;">Rentang Tenaga & Mesin</th>
                                        <td>120 PS / 2.600 rpm (Rentang 100 – 150 PS) | Mesin 4JJ1-TC Common Rail Euro 4, Torsi 36 kg.m / 1.500 rpm</td>
                                    </tr>
                                    <tr>
                                        <th style="background: #f8f9fa;">Transmisi</th>
                                        <td>Manual 6 Tingkat Percepatan (MYY6S)</td>
                                    </tr>
                                    <tr>
                                        <th style="background: #f8f9fa;">Kapasitas Penumpang</th>
                                        <td>3 Seater (Termasuk Pengemudi)</td>
                                    </tr>
                                    <tr>
                                        <th style="background: #f8f9fa;">Fitur Keselamatan & Kenyamanan</th>
                                        <td>Kamera Mundur, APAR (Alat Pemadam Api Ringan), Cabin Lock Warning Buzzer, Slot USB Charger, Fan Blower</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <!-- Deskripsi & Keterangan Lengkap -->
                <div class="row m-b40">
                    <div class="col-12">
                        <div class="section-head text-left m-b25">
                            <h3 class="text-uppercase font-weight-700">Keterangan & Keunggulan</h3>
                            <div class="dlab-separator bg-primary"></div>
                        </div>

                        <div class="p-a30 bg-gray" style="border-radius: 8px; border: 1px solid #e1e6eb; line-height: 28px; font-size: 15px; color: #444;">
                            <div class="m-b25">
                                <h4 class="font-weight-600 text-uppercase" style="color: #222;">Isuzu ELF NLR, Untung Maksimal: Lebih Irit dan Lebih Bertenaga</h4>
                                <p>Isuzu ELF NLR dan Isuzu ELF NLR L kini menggunakan teknologi mesin Common Rail 4JJ1-TC yang dilengkapi Exhaust Gas Recirculation (EGR) dan Diesel Oxidation Catalyst (DOC), sehingga mampu menghasilkan emisi gas buang berstandar Euro4. Dengan Engine Control Unit (ECU) yang terdapat pada mesin common rail, kini Isuzu ELF NLR mampu menghasilkan tenaga dan torsi yang lebih besar (120 PS/2.600 rpm dan 36 kg.m/1.500 rpm) namun lebih irit dan mampu mengoptimalkan efisiensi bisnis Anda.</p>
                            </div>

                            <div class="m-b25">
                                <h4 class="font-weight-600 text-uppercase" style="color: #222;">Isuzu ELF NLR, Handal di Perkotaan</h4>
                                <p>Isuzu ELF NLR hadir dengan panjang yang optimal sehingga memiliki kapasitas dan daya angkut yang sesuai untuk penggunaan di perkotaan, cocok untuk aplikasi box alumunium & bak besi. Hal ini tentu menjadikan Isuzu ELF NLR unggul sebagai truk ringan 4 ban yang juga teruji hemat bahan bakar sehingga mampu meningkatkan keuntungan bisnis.</p>
                            </div>

                            <div class="m-b25">
                                <h4 class="font-weight-600 text-uppercase" style="color: #222;">Transmisi Baru dengan 6 Tingkat Percepatan</h4>
                                <p>Kini Isuzu ELF NLR dan Isuzu ELF NLR L menggunakan transmisi MYY6S dengan 6 tingkat percepatan yang mampu memaksimalkan efisiensi bahan bakar, serta memiliki first gear ratio yang lebih besar, sehingga menghasilkan tenaga lebih besar. Waktu tempuh lebih cepat, ritase meningkat dan tentunya biaya operasional makin efisien.</p>
                            </div>

                            <div>
                                <h4 class="font-weight-600 text-uppercase" style="color: #222;">Lebih Aman dan Nyaman Dengan Fitur-Fitur Fungsional</h4>
                                <p class="m-b0">Isuzu ELF NLR dan Isuzu ELF NLR L memiliki kaca depan yang lebih tegak dan besar, membuat jarak pandang lebih luas, siap memberikan rasa aman dan nyaman yang ekstra bagi pengemudi. Juga dilengkapi dengan sensor Cabin Lock Warning Buzzer yang memberikan tanda peringatan saat kabin belum terkunci, sehingga membantu memaksimalkan keselamatan pengemudi saat berkendara. Fan blower guna meningkatkan sirkulasi udara di dalam kabin, dengan slot USB charger pengemudi dapat menikmati perjalanan tanpa khawatir kondisi baterai ponsel. Isuzu ELF NLR dan Isuzu ELF NLR L juga dilengkapi dengan APAR (Alat Pemadam Api Ringan) untuk memaksimalkan keamanan pada kondisi genting (kebakaran) serta kamera mundur agar pengemudi dapat melihat kondisi dibagian belakang kendaraan, sehingga memudahkan pengemudi saat mundur atau memarkir kendaraan.</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Bagian Harga & Brosur di Bawah -->
                <div class="row">
                    <div class="col-lg-6 col-md-12 m-b20">
                        <div class="p-a30 bg-white" style="border: 2px dashed #e31e24; border-radius: 8px; height: 100%;">
                            <h4 class="font-weight-700 text-uppercase m-b15"><i class="fas fa-tags text-primary m-r10"></i> Informasi Harga & Varian</h4>
                            <p class="text-muted m-b15">Daftar estimasi harga unit sasis (Chassis Only) OTR DKI Jakarta & Sekitarnya:</p>
                            <ul class="list-unstyled" style="font-size: 15px; line-height: 32px;">
                                <li class="d-flex justify-content-between border-bottom p-b5">
                                    <span><strong>ISUZU ELF NLR (4 Ban)</strong></span>
                                    <span class="text-primary font-weight-700">Rp 433.000.000</span>
                                </li>
                                <li class="d-flex justify-content-between border-bottom p-t5 p-b5">
                                    <span><strong>ISUZU ELF NLR L (4 Ban Long)</strong></span>
                                    <span class="text-primary font-weight-700">Rp 450.000.000</span>
                                </li>
                            </ul>
                            <small class="text-muted">* Harga belum termasuk karoseri (Box/Bak/dll) dan dapat berubah sewaktu-waktu sesuai program diskon berjalan.</small>
                        </div>
                    </div>

                    <div class="col-lg-6 col-md-12 m-b20">
                        <div class="p-a30 bg-white" style="border: 1px solid #e1e6eb; border-radius: 8px; height: 100%; display: flex; flex-direction: column; justify-content: space-between;">
                            <div>
                                <h4 class="font-weight-700 text-uppercase m-b15"><i class="fas fa-file-download text-primary m-r10"></i> Brosur & Pricelist Resmi</h4>
                                <p class="text-muted m-b20">Unduh dokumen spesifikasi brosur resmi dan pricelist NIK 2026 langsung dalam format PDF:</p>
                            </div>
                            <div class="d-flex flex-wrap gap-2">
                                <a href="https://isuzujakarta.com/assets/files/brosur/PRICE_LIST_UPDATE_-_AGUSTUS_2026.pdf" target="_blank" download class="site-button" style="display: inline-flex; align-items: center; gap: 8px;">
                                    <i class="fas fa-file-pdf"></i> Unduh Brosur Isuzu ELF
                                </a>
                                <a href="{{ route('brosur') }}" class="site-button-secondry outline" style="display: inline-flex; align-items: center; gap: 8px;">
                                    <i class="fas fa-list"></i> Lihat Semua Brosur
                                </a>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
        <!-- Product Detail Section END -->
    </div>
    <!-- Content END -->
@endsection
