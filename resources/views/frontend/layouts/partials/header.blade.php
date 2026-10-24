    <!-- header -->
    <header class="site-header header header-style-1 mo-left">
        <!-- top bar -->
        <div class="top-bar no-skew">
            <div class="container">
                <div class="d-flex justify-content-between">
                    <div class="dlab-topbar-left">
                        <ul class="social-bx list-inline  pull-left">
                            <li><a href="javascript:void(0);" ><i class="ti-mobile"></i> 0813 1894 1813 / 0818 0670 5871</a></li>
                            <li><a href="javascript:void(0);" class="ti-email"></a> admisuzujakarta@gmail.com</li>
                        </ul>
                    </div>
                    <div class="dlab-topbar-right d-flex">
                        <ul class="social-bx list-inline float-end">
                            <li><a class="fab fa-facebook-f" href="https://www.facebook.com/" target="blank"></a></li>
                            <li><a class="fab fa-twitter" href="https://twitter.com/" target="blank"></a></li>
                            <li><a class="fab fa-linkedin-in" href="https://www.linkedin.com/" target="blank" ></a></li>
                            <li><a class="fab fa-google-plus-g" href="https://mail.google.com/" target="blank" ></a></li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
        <!-- top bar END-->
        <!-- main header -->
        <div class="sticky-header main-bar-wraper navbar-expand-lg">
            <div class="main-bar clearfix ">
                <div class="container clearfix">
                    <!-- website logo -->
                    <div class="logo-header mostion logo-dark"><a href="index.html"><img src="{{ asset('assets/images/logo/logo_isuzu.jpg') }}" width="193" height="89" alt="Isuzu Logo"></a></div>
                    <!-- nav toggle button -->
					<button class="navbar-toggler collapsed navicon justify-content-end" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNavDropdown" aria-controls="navbarNavDropdown" aria-expanded="false" aria-label="Toggle navigation">
						<span></span>
						<span></span>
						<span></span>
					</button>
                    <!-- extra nav -->
                    <div class="extra-nav">
                        <div class="extra-cell">
                            <button id="quik-search-btn" type="button" class="site-button"><i class="fas fa-search"></i></button>
                        </div>
                    </div>
                    <!-- Quik search -->
                    <div class="dlab-quik-search bg-primary">
                        <form action="#">
                            <input name="search" value="" type="text" class="form-control" placeholder="Type to search">
                            <span  id="quik-search-remove"><i class="fas fa-times"></i></span>
                        </form>
                    </div>
                    <!-- main nav -->
                    <div class="header-nav navbar-collapse collapse justify-content-end" id="navbarNavDropdown">
                        <ul class="nav navbar-nav nav-style">
                            <li class="{{ request()->is('/') || request()->is('mockup') ? 'active' : '' }}"><a href="{{ route('index') }}">Beranda</a></li>
                            <li class="{{ request()->is('produk*') ? 'active' : '' }}"><a href="{{ route('produk') }}">Produk</a></li>
                            <li class="{{ request()->is('layanan*') ? 'active' : '' }}"><a href="{{ route('layanan') }}">Layanan</a></li>
                            <li class="{{ request()->is('sparepart*') ? 'active' : '' }}"><a href="{{ route('sparepart') }}">Sparepart</a></li>
                            <li class="{{ request()->is('news*') || request()->is('brosur*') ? 'active' : '' }}">
                                <a href="{{ route('news') }}">Informasi <i class="fas fa-chevron-down"></i></a>
                                <ul class="sub-menu">
                                    <li><a href="{{ route('news') }}">Berita</a></li>
                                    <li><a href="{{ route('brosur') }}">Brosur</a></li>
                                </ul>
                            </li>
                            <li class="{{ request()->is('kontak*') ? 'active' : '' }}"><a href="{{ route('kontak') }}">Kontak</a></li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
        <!-- main header END -->
    </header>
    <!-- header END -->
