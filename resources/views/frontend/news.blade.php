@extends('frontend.layouts.app')

@section('content')
    <!-- Content -->
    <div class="page-content bg-white">
        <!-- Inner Banner -->
        <div class="dlab-bnr-inr overlay-black-middle" style="background-image:url({{ asset('assets/images/banner/banner1.jpeg') }});">
            <div class="container">
                <div class="dlab-bnr-inr-entry">
                    <h1 class="text-white">BERITA & INFORMASI</h1>
                    <!-- Breadcrumb row -->
                    <div class="breadcrumb-row">
                        <ul class="list-inline">
                            <li><a href="{{ route('index') }}">Beranda</a></li>
                            <li>Berita</li>
                        </ul>
                    </div>
                    <!-- Breadcrumb row END -->
                </div>
            </div>
        </div>
        <!-- Inner Banner END -->

        <!-- News Section -->
        <div class="section-full content-inner bg-white">
            <div class="container">
                <div class="section-head text-center">
                    <h2 class="text-uppercase">KABAR TERKINI <span class="text-primary">ISUZU</span></h2>
                    <p>Informasi terbaru seputar perkembangan teknologi kendaraan niaga Isuzu, tips operasional logistik, dan kabar seputar industri otomotif komersial.</p>
                </div>

                @php
                    $allNews = [
                        [
                            'title' => 'Isuzu Pastikan Kendaraan Tetap Aman Pakai B50, Lolos Pengetesan 60.000 Km',
                            'date' => '11 September 2026',
                            'author' => 'lukman',
                            'excerpt' => 'PT Isuzu Astra Motor Indonesia (IAMI) memastikan kendaraan komersial Isuzu yang beredar di pasar Indonesia tetap aman dan handal menggunakan bahan bakar biodiesel B50. Hal ini dibuktikan melalui serangkaian pengetesan intensif sejauh 60.000 Km di berbagai kontur jalan.',
                            'image' => asset('assets/images/news/info1.jpeg'),
                        ],
                        [
                            'title' => 'Gali Jurus-Jurus Jitu Berniaga di Tengah Pandemi',
                            'date' => '11 September 2026',
                            'author' => 'lukman',
                            'excerpt' => 'Kita sama-sama menyadari bahwa saat ini adalah fase bangkit dari kondisi akibat pandemi lalu. Berbagai inovasi strategi armada serta efisiensi konsumsi bahan bakar kendaraan Isuzu terbukti menjadi penopang utama efisiensi bisnis logistik.',
                            'image' => asset('assets/images/news/info2.jpg'),
                        ],
                        [
                            'title' => '"Uang Jalan Masih Sisa, Lumayan Buat Beli Baju"',
                            'date' => '25 Juni 2021',
                            'author' => 'lukman',
                            'excerpt' => 'Dalam sekali jalan, kami melihat bagaimana sebuah truk membutuhkan bermacam komponen biaya. Keiritan mesin diesel Isuzu membuat pengemudi dan pengusaha armada menikmati penghematan uang jalan yang nyata.',
                            'image' => asset('assets/images/news/info3.jpg'),
                        ],
                        [
                            'title' => 'TEST COMMON-RAIL DIESEL, ANDALAN TEKNOLOGI ISUZU',
                            'date' => '21 Januari 2019',
                            'author' => 'lukmanbudi',
                            'excerpt' => 'Isuzu merupakan manufaktur yang bisa dibilang sebagai pioner dalam pemakaian teknologi common-rail pada mesin kendaraan komersial, memberikan semburan tenaga tinggi namun tetap ramah lingkungan dan hemat bahan bakar.',
                            'image' => asset('assets/images/news/info4.jpg'),
                        ],
                        [
                            'title' => '2018 TAHUN INFRASTRUKTUR ISUZU OPTIMIS PENJUALAN KENDARAAN KOMERSIAL MENINGKAT',
                            'date' => '09 Januari 2019',
                            'author' => 'lukmanbudi',
                            'excerpt' => 'Tahun 2017 kemarin adalah tahun yang menggembirakan untuk penjualan kendaraan komersial, di mana proyek pembangunan infrastruktur nasional turut memacu lonjakan kebutuhan truk Isuzu Giga dan Elf.',
                            'image' => asset('assets/images/news/info5.jpg'),
                        ],
                    ];
                @endphp

                <div class="row">
                    @foreach($allNews as $news)
                    <div class="col-lg-4 col-md-6 col-sm-12 m-b30">
                        <div class="blog-post blog-grid date-style-2" style="border: 1px solid #eee; border-radius: 6px; overflow: hidden; background: #fff; height: 100%; display: flex; flex-direction: column;">
                            <div class="dlab-post-media dlab-img-effect zoom-slow">
                                <a href="javascript:void(0);"><img src="{{ $news['image'] }}" alt="{{ $news['title'] }}" style="height: 220px; width: 100%; object-fit: cover;"></a>
                            </div>
                            <div class="dlab-post-info p-a20" style="flex: 1; display: flex; flex-direction: column;">
                                <div class="dlab-post-meta m-b10">
                                    <ul style="padding: 0; list-style: none; display: flex; gap: 15px; font-size: 13px; color: #888;">
                                        <li><i class="far fa-calendar-alt text-primary m-r5"></i> {{ $news['date'] }}</li>
                                        <li><i class="far fa-user text-primary m-r5"></i> {{ $news['author'] }}</li>
                                    </ul>
                                </div>
                                <div class="dlab-post-title m-b10">
                                    <h4 class="post-title font-weight-600" style="font-size: 17px; line-height: 24px; min-height: 48px;">
                                        <a href="javascript:void(0);">{{ $news['title'] }}</a>
                                    </h4>
                                </div>
                                <div class="dlab-post-text m-b15" style="flex: 1;">
                                    <p style="color: #666; font-size: 14px; line-height: 22px;">{{ Str::limit($news['excerpt'], 140) }}</p>
                                </div>
                                <div class="dlab-post-readmore">
                                    <a href="javascript:void(0);" class="site-button-link font-weight-600">BACA SELENGKAPNYA <i class="fas fa-arrow-right"></i></a>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
        <!-- News Section END -->
    </div>
    <!-- Content END -->
@endsection
