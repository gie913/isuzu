    <!-- Footer -->
    <footer class="site-footer ">
        <!-- footer top part -->
        <div class="footer-top">
            <div class="container">
                <div class="row">
                    <div class="col-lg-3 col-md-6 col-sm-6 footer-col-4">
                        <div class="widget widget_about">
                            <div class="logo-footer logo-white"><img src="{{ asset('assets/images/logo/logo_isuzu.jpg') }}" alt=""></div>
                            <p><strong>ISUZU Jakarta</strong> Solusi kendaraan niaga untuk mendukung bisnis anda</p>
                        </div>
                    </div>
                    <div class="col-lg-3 col-md-6 col-sm-6 footer-col-4">
                        <div class="widget recent-posts-entry">
                            <h4 class="m-b15 text-uppercase">Berita terbaru</h4>
                            <div class="dlab-separator-outer m-b10">
                                <div class="dlab-separator bg-white style-skew"></div>
                            </div>
                            <div class="widget-post-bx">
                                @php
                                    $footerNews = [
                                        [
                                            'title' => 'Isuzu Pastikan Kendaraan Tetap Aman Pakai B50, Lolos Pengetesan 60.000 Km',
                                            'date' => '11 Sep 2026',
                                            'author' => 'lukman',
                                            'image' => asset('assets/images/news/info1.jpeg'),
                                            'link' => '#',
                                        ],
                                        [
                                            'title' => 'Gali Jurus-Jurus Jitu Berniaga di Tengah Pandemi',
                                            'date' => '11 Sep 2026',
                                            'author' => 'lukman',
                                            'image' => asset('assets/images/news/info2.jpg'),
                                            'link' => '#',
                                        ],
                                        [
                                            'title' => '"Uang Jalan Masih Sisa, Lumayan Buat Beli Baju"',
                                            'date' => '25 Jun 2021',
                                            'author' => 'lukman',
                                            'image' => asset('assets/images/news/info3.jpg'),
                                            'link' => '#',
                                        ],
                                    ];
                                @endphp
                                @foreach($footerNews as $fNews)
                                <div class="widget-post clearfix">
                                    <div class="dlab-post-media"> 
                                        <img src="{{ $fNews['image'] }}" alt="{{ $fNews['title'] }}" width="200" height="143" style="object-fit: cover; height: 50px;"> 
                                    </div>
                                    <div class="dlab-post-info">
                                        <div class="dlab-post-header">
                                            <h6 class="post-title text-uppercase" style="display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden; line-height: 1.3;">
                                                <a href="{{ $fNews['link'] }}">{{ $fNews['title'] }}</a>
                                            </h6>
                                        </div>
                                        <div class="dlab-post-meta">
                                            <ul>
                                                <li class="post-author"><i class="fas fa-calendar-alt"></i> {{ $fNews['date'] }}</li>
                                                <li class="post-comment"><i class="fas fa-user"></i> {{ $fNews['author'] }}</li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-3 col-md-6 col-sm-6 footer-col-4">
                        <div class="widget widget_services">
                            <h4 class="m-b15 text-uppercase">Promosi</h4>
                            <div class="dlab-separator-outer m-b10">
                                <div class="dlab-separator bg-white style-skew"></div>
                            </div>
                            @php
                                $footerPromos = [
                                    ['name' => 'ISUZU ELF MICROBUS NEW ARMADA 20 SEAT', 'price' => 'Rp 654.000.000', 'badge' => 'Promo'],
                                    ['name' => 'ISUZU ELF NMR L', 'price' => 'Rp 522.000.000', 'badge' => 'Promo'],
                                    ['name' => 'ISUZU TRAGA FD - WHITE', 'price' => 'Rp 297.000.000', 'badge' => null],
                                    ['name' => 'ISUZU ELF NLR', 'price' => 'Rp 433.000.000', 'badge' => null],
                                    ['name' => 'ISUZU ELF NLR L', 'price' => 'Rp 450.000.000', 'badge' => null],
                                    ['name' => 'ISUZU TRAGA BLACK PREMIUM AC', 'price' => 'Rp 308.000.000', 'badge' => null],
                                ];
                            @endphp
                            <ul>
                                @foreach($footerPromos as $fPromo)
                                <li>
                                    <a href="{{ route('detail-produk') }}" style="display: flex; justify-content: space-between; align-items: center; gap: 5px;">
                                        <span style="white-space: nowrap; overflow: hidden; text-overflow: ellipsis; max-width: 170px;">{{ $fPromo['name'] }}</span>
                                        @if($fPromo['badge'])
                                            <span class="badge badge-danger" style="background:#e31e24; color:#fff; font-size:9px; padding:2px 5px; border-radius:3px;">PROMO</span>
                                        @endif
                                    </a>
                                </li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                    <div class="col-lg-3 col-md-6 col-sm-6 footer-col-4">
                        <div class="widget widget_getintuch">
                            <h4 class="m-b15 text-uppercase">Contact us</h4>
                            <div class="dlab-separator-outer m-b10">
                                <div class="dlab-separator bg-white style-skew"></div>
                            </div>
                            <ul>
                                <li>
                                    <i class="ti-location-pin"></i>
                                    <strong>address</strong>
                                    Greenland Square, Jl. Deltamas Boulevard Kota Deltamas Blok BA no 8, Kel. Sukamahi, Kec. Cikarang Pusat, Kabupaten Bekasi, Jawa Barat 17530
                                </li>
                                <li>
                                    <i class="ti-mobile"></i>
                                    <strong>phone</strong>
                                    0813 1894 1813 / 0818 0670 5871 (Lukman Setya Budi)
                                </li>
                                <li>
                                    <i class="ti-email"></i>
                                    <strong>email</strong>
                                    admisuzujakarta@gmail.com
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- footer bottom part -->
        <div class="footer-bottom">
            <div class="container">
                <div class="row">
                    <div class="col-lg-4 col-md-4 text-left"> 
						<span>© Copyright <span class="current-year"> 2024</span></span>
					</div>
					<div class="col-lg-4 col-md-4 text-center"> 
						
					</div>
					<div class="col-lg-4 col-md-4 text-right"> 
						
					</div>
                </div>
            </div>
        </div>
    </footer>
    <!-- Footer END-->
