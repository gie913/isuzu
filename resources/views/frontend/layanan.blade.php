@extends('frontend.layouts.app')

@section('content')
    <!-- Content -->
    <div class="page-content bg-white">
        <!-- Inner Banner -->
        <div class="dlab-bnr-inr overlay-black-middle" style="background-image:url({{ asset('assets/images/banner/banner1.jpeg') }});">
            <div class="container">
                <div class="dlab-bnr-inr-entry">
                    <h1 class="text-white">BENGKEL ISUZU BERJALAN</h1>
                    <!-- Breadcrumb row -->
                    <div class="breadcrumb-row">
                        <ul class="list-inline">
                            <li><a href="{{ route('index') }}">Beranda</a></li>
                            <li>Layanan</li>
                            <li>Bengkel Isuzu Berjalan</li>
                        </ul>
                    </div>
                    <!-- Breadcrumb row END -->
                </div>
            </div>
        </div>
        <!-- Inner Banner END -->

        <!-- Section Detail Layanan -->
        <div class="section-full content-inner bg-white">
            <div class="container">
                <div class="row">
                    <!-- Kolom Gambar & Quick Info -->
                    <div class="col-lg-5 col-md-12 m-b30">
                        <div class="dlab-box" style="border: 1px solid #e1e6eb; border-radius: 8px; overflow: hidden; box-shadow: 0 4px 15px rgba(0,0,0,0.05);">
                            <div class="dlab-media">
                                <img src="{{ asset('assets/images/bib.jpeg') }}" alt="Bengkel Isuzu Berjalan (BIB)" style="width: 100%; height: auto; display: block;">
                            </div>
                            <div class="p-a25 bg-gray">
                                <h4 class="font-weight-700 m-b15 text-uppercase">Layanan Darurat Isuzu</h4>
                                <ul class="list-check primary" style="font-size: 14px; line-height: 24px;">
                                    <li>Armada servis bergerak ke lokasi Anda</li>
                                    <li>Peralatan lengkap hingga turun mesin</li>
                                    <li>Respons darurat via WhatsApp Isuzu</li>
                                    <li>Teknisi bersertifikasi resmi Isuzu Astra</li>
                                </ul>
                                <div class="m-t20">
                                    <a href="https://wa.me/6289501701700?text=Halo%20Rangga,%20saya%20butuh%20bantuan%20Isuzu" target="_blank" class="site-button btn-block" style="background: #25D366; border-color: #25D366; color: #fff; font-weight: 600; padding: 12px; display: flex; align-items: center; justify-content: center; gap: 8px;">
                                        <i class="fab fa-whatsapp fa-lg"></i> Hubungi Rangga (0895-0170-1700)
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Kolom Konten Artikel/Penjelasan Layanan -->
                    <div class="col-lg-7 col-md-12 m-b30">
                        <div class="p-l20 p-r10">
                            <h2 class="text-uppercase font-weight-700 m-b15">Solusi Tanggap Darurat <span class="text-primary">Bengkel Isuzu Berjalan</span></h2>
                            <div class="dlab-separator bg-primary m-b25"></div>

                            <div style="font-size: 15px; line-height: 28px; color: #444;">
                                <p>Bengkel Isuzu Berjalan merupakan layanan yang diberikan oleh Isuzu Astra Motor Indonesia ketika pengguna Isuzu mengalami kendala dan tidak memungkinkan untuk mencari atau dalam kondisi tidak mengetahui posisi bengkel terdekat.</p>

                                <p>Sebagai layanan dalam bentuk sebuah kendaraan yang membawa perlengkapan perbaikan, Isuzu Bengkel Berjalan pun menjadi solusi untuk memberikan rasa lega dalam menghadapi kondisi darurat.</p>

                                <p>Yang lebih menarik adalah Isuzu Bengkel Berjalan bisa dipesan untuk datang ke lokasi, cukup melalui pesan Whatsapp.</p>

                                <div class="alert alert-info" style="background-color: #f7f9fc; border-left: 4px solid #e31e24; border-radius: 4px; padding: 18px 20px; margin: 25px 0;">
                                    <h5 class="m-t0 m-b10 font-weight-600" style="color: #222;"><i class="fab fa-whatsapp text-success m-r5"></i> Cara Mudah Memesan Layanan via WhatsApp:</h5>
                                    <ol style="margin-bottom: 0; padding-left: 20px; line-height: 26px;">
                                        <li>Nomor kontak Whatsapp tersebut adalah <strong>089501701700</strong> dan terhubung dengan Isuzu Contact Center yang akan diwakili persona bernama <strong>"Rangga"</strong>.</li>
                                        <li>Saat dalam kondisi darurat, pengguna cukup menyapa Rangga sebagai perwakilan Isuzu Contact Center. Selanjutnya, pesan akan dibalas dengan sejumlah pilihan.</li>
                                        <li>Di antara sekian pilihan tersebut, maka akan terdapat pilihan <strong>"Bantuan Kondisi Darurat"</strong> yakni di nomor <strong>3</strong>.</li>
                                        <li>Selanjutnya, Rangga akan meminta pengguna untuk membalas pesan dengan mengetik menu sesuai nomor urut yang tertera di pesan.</li>
                                        <li>Dengan demikian, untuk kondisi darurat yang membutuhkan layanan Bengkel Isuzu Berjalan, pengguna perlu membalas dengan angka <strong>"3"</strong>.</li>
                                        <li>Jika kita membalas dengan "3", maka Rangga akan meminta pengguna untuk menginformasikan lokasi pengguna saat itu melalui <strong>"Share Loc"</strong> yang tersedia di Whatsapp.</li>
                                        <li>Dengan adanya informasi mengenai lokasi terkini pengguna, sistem akan menunjuk agen Isuzu terdekat.</li>
                                    </ol>
                                </div>

                                <p>Untuk diketahui, Bengkel Isuzu Berjalan berisi peralatan lengkap yang bahkan memungkinkan perbaikan hingga turun mesin.</p>

                                <p>Isuzu sadar bahwa berbagai layanan dibuat untuk memudahkan para pengguna, termasuk dalam berkomunikasi menggunakan layanan berkirim pesan yang umum digunakan.</p>

                                <p class="m-b0">Dengan layanan terkoneksi ini, dukungan Isuzu bisa didapatkan, semudah berkirim pesan selayaknya menggunakan Whatsapp.</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- Section Detail Layanan END -->
    </div>
    <!-- Content END -->
@endsection
