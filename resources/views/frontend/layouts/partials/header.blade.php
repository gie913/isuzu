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
                            <li class="active"><a href="{{ url('/') }}">Beranda</a></li>
                            <li class="has-mega-menu">
                                <a href="javascript:void(0);">Produk <i class="fas fa-chevron-down"></i></a>
                                <ul class="mega-menu">
                                    <li>
                                        <a href="javascript:void(0);">Produk</a>
                                        <ul>
                                            <li><a href="javascript:void(0);">Isuzu Giga</a></li>
                                            <li><a href="javascript:void(0);">Isuzu Elf</a></li>
                                        </ul>
                                    </li>
                                    <li>
                                        <a href="javascript:void(0);">Service</a>
                                        <ul>
                                            <li><a href="javascript:void(0);">Area Service</a></li>
                                        </ul>
                                    </li>
                                    <li>
                                        <a href="javascript:void(0);">Sparepart</a>
                                        <ul>
                                            <li><a href="javascript:void(0);">Sparepart Isuzu</a></li>
                                        </ul>
                                    </li>
                                </ul>
                            </li>
                            <li><a href="javascript:void(0);">Informasi</a></li>
                            <li><a href="javascript:void(0);">Kontak</a></li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
        <!-- main header END -->
    </header>
    <!-- header END -->
