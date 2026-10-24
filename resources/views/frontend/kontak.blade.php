@extends('frontend.layouts.app')

@section('content')
    <!-- Content -->
    <div class="page-content bg-white">
        <!-- Inner Banner -->
        <div class="dlab-bnr-inr overlay-black-middle" style="background-image:url({{ asset('assets/images/banner/banner1.jpeg') }});">
            <div class="container">
                <div class="dlab-bnr-inr-entry">
                    <h1 class="text-white">HUBUNGI KAMI</h1>
                    <!-- Breadcrumb row -->
                    <div class="breadcrumb-row">
                        <ul class="list-inline">
                            <li><a href="{{ route('index') }}">Beranda</a></li>
                            <li>Kontak</li>
                        </ul>
                    </div>
                    <!-- Breadcrumb row END -->
                </div>
            </div>
        </div>
        <!-- Inner Banner END -->

        <!-- Contact Section -->
        <div class="section-full content-inner bg-white">
            <div class="container">
                <div class="row">
                    <!-- Left: Contact Info -->
                    <div class="col-lg-5 col-md-5 m-b30">
                        <div class="p-a30 bg-gray clearfix" style="border-radius: 6px; border: 1px solid #e1e6eb; height: 100%;">
                            <h3 class="m-b20 text-uppercase font-weight-700">Dealer Isuzu <span class="text-primary">Jakarta</span></h3>
                            <p class="m-b30">Siap melayani kebutuhan unit baru, konsultasi karoseri niaga, simulasi pembiayaan, dan layanan purna jual kendaraan Isuzu Anda.</p>
                            
                            <div class="icon-bx-wraper bx-style-1 p-a20 m-b20 bg-white" style="border-radius: 4px; border: 1px solid #eee;">
                                <div class="icon-sm text-primary m-b10"><i class="ti-location-pin"></i></div>
                                <div class="icon-content">
                                    <h5 class="dlab-tilte text-uppercase">Alamat Showroom & Kantor</h5>
                                    <p class="m-b0" style="font-size: 14px; line-height: 22px;">
                                        Greenland Square, Jl. Deltamas Boulevard Kota Deltamas Blok BA no 8, Kel. Sukamahi, Kec. Cikarang Pusat, Kabupaten Bekasi, Jawa Barat 17530
                                    </p>
                                </div>
                            </div>

                            <div class="icon-bx-wraper bx-style-1 p-a20 m-b20 bg-white" style="border-radius: 4px; border: 1px solid #eee;">
                                <div class="icon-sm text-primary m-b10"><i class="ti-mobile"></i></div>
                                <div class="icon-content">
                                    <h5 class="dlab-tilte text-uppercase">Phone / WhatsApp</h5>
                                    <p class="m-b0" style="font-size: 14px; line-height: 22px;">
                                        <strong>0813 1894 1813</strong> / <strong>0818 0670 5871</strong><br>
                                        <span class="text-muted">(Lukman Setya Budi)</span>
                                    </p>
                                </div>
                            </div>

                            <div class="icon-bx-wraper bx-style-1 p-a20 bg-white" style="border-radius: 4px; border: 1px solid #eee;">
                                <div class="icon-sm text-primary m-b10"><i class="ti-email"></i></div>
                                <div class="icon-content">
                                    <h5 class="dlab-tilte text-uppercase">Email Resmi</h5>
                                    <p class="m-b0" style="font-size: 14px;">
                                        <a href="mailto:admisuzujakarta@gmail.com" class="text-primary">admisuzujakarta@gmail.com</a>
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Right: Contact Form (nama, email, tipe, pesan, kirim) -->
                    <div class="col-lg-7 col-md-7 m-b30">
                        <div class="p-a30 bg-white clearfix" style="border-radius: 6px; border: 1px solid #e1e6eb;">
                            <h3 class="m-b10 text-uppercase font-weight-700">Kirim <span class="text-primary">Pesan</span></h3>
                            <p class="m-b30">Silakan lengkapi formulir di bawah ini untuk menghubungi tim kami.</p>

                            <form method="POST" action="javascript:void(0);">
                                <div class="row">
                                    <div class="col-md-12 m-b20">
                                        <div class="form-group">
                                            <label>Nama <span class="text-danger">*</span></label>
                                            <input type="text" name="nama" class="form-control" placeholder="Nama Lengkap / Perusahaan" required>
                                        </div>
                                    </div>
                                    <div class="col-md-12 m-b20">
                                        <div class="form-group">
                                            <label>Email <span class="text-danger">*</span></label>
                                            <input type="email" name="email" class="form-control" placeholder="alamat@email.com" required>
                                        </div>
                                    </div>
                                    <div class="col-md-12 m-b20">
                                        <div class="form-group">
                                            <label>Tipe <span class="text-danger">*</span></label>
                                            <input type="text" name="tipe" class="form-control" placeholder="Contoh: Isuzu Elf NLR, Isuzu Giga, Traga, Servis, dll" required>
                                        </div>
                                    </div>
                                    <div class="col-md-12 m-b25">
                                        <div class="form-group">
                                            <label>Pesan <span class="text-danger">*</span></label>
                                            <textarea name="pesan" rows="5" class="form-control" placeholder="Tuliskan pesan atau kebutuhan Anda di sini..." required></textarea>
                                        </div>
                                    </div>
                                    <div class="col-md-12">
                                        <button type="submit" class="site-button btn-block font-weight-600" style="padding: 12px; font-size: 16px;">
                                            <i class="fas fa-paper-plane m-r5"></i> Kirim
                                        </button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>

                <!-- Google Maps Section -->
                <div class="row m-t20">
                    <div class="col-12">
                        <div class="p-a10 bg-white" style="border: 1px solid #e1e6eb; border-radius: 8px; overflow: hidden; box-shadow: 0 4px 15px rgba(0,0,0,0.03);">
                            <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d5305.131013851206!2d107.17363111663452!3d-6.370034058797621!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x2e6999ad87e5517b%3A0x660b7c38846c2f8b!2sISUZU%20Astrido%20Deltamas%20Cikarang!5e0!3m2!1sid!2sid!4v1744988009196!5m2!1sid!2sid" width="100%" height="350" frameborder="0" style="border:0" allowfullscreen></iframe>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- Contact Section END -->
    </div>
    <!-- Content END -->
@endsection
