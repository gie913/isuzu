@extends('frontend.layouts.app')

@section('content')
    <!-- Content -->
    <div class="page-content bg-white">
        <!-- Inner Banner -->
        <div class="dlab-bnr-inr overlay-black-middle" style="background-image:url({{ asset('assets/images/banner/banner1.jpeg') }});">
            <div class="container">
                <div class="dlab-bnr-inr-entry">
                    <h1 class="text-white">SUKU CADANG & SPARE PART</h1>
                    <!-- Breadcrumb row -->
                    <div class="breadcrumb-row">
                        <ul class="list-inline">
                            <li><a href="{{ route('index') }}">Beranda</a></li>
                            <li>Sparepart</li>
                        </ul>
                    </div>
                    <!-- Breadcrumb row END -->
                </div>
            </div>
        </div>
        <!-- Inner Banner END -->

        <!-- Section Detail Sparepart -->
        <div class="section-full content-inner bg-white">
            <div class="container">
                <div class="row">
                    <!-- Kolom Gambar & Quick Info -->
                    <div class="col-lg-5 col-md-12 m-b30">
                        <div class="dlab-box" style="border: 1px solid #e1e6eb; border-radius: 8px; overflow: hidden; box-shadow: 0 4px 15px rgba(0,0,0,0.05);">
                            <div class="dlab-media">
                                <img src="{{ asset('assets/images/sparepart.jpg') }}" alt="Suku Cadang Asli Isuzu" style="width: 100%; height: auto; display: block;">
                            </div>
                            <div class="p-a25 bg-gray">
                                <h4 class="font-weight-700 m-b15 text-uppercase">Isuzu Genuine Parts</h4>
                                <ul class="list-check primary" style="font-size: 14px; line-height: 24px;">
                                    <li>Suku cadang 100% orisinal dan bergaransi resmi</li>
                                    <li>Terhubung langsung ke jaringan agen terdekat</li>
                                    <li>Konsultasi nama suku cadang & cek ketersediaan stok</li>
                                    <li>Dukungan Asisten Digital "Rangga" via WhatsApp</li>
                                </ul>
                                <div class="m-t20">
                                    <a href="https://wa.me/6289501701700?text=Halo%20Rangga,%20saya%20butuh%20informasi%20sparepart%20Isuzu" target="_blank" class="site-button btn-block" style="background: #25D366; border-color: #25D366; color: #fff; font-weight: 600; padding: 12px; display: flex; align-items: center; justify-content: center; gap: 8px;">
                                        <i class="fab fa-whatsapp fa-lg"></i> Cek Sparepart via WhatsApp (Rangga)
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Kolom Konten Artikel/Penjelasan Sparepart -->
                    <div class="col-lg-7 col-md-12 m-b30">
                        <div class="p-l20 p-r10">
                            <h2 class="text-uppercase font-weight-700 m-b15">Kemudahan Pemesanan <span class="text-primary">Spare Part Isuzu</span></h2>
                            <div class="dlab-separator bg-primary m-b25"></div>

                            <div style="font-size: 15px; line-height: 28px; color: #444;">
                                <p>Tanya spare part Isuzu? langsung saja Whatsapp ke Isuzu Contact Center. Ini adalah kemudahan baru yang diberikan Isuzu Astra Motor Indonesia. Para Isuzu Partner tinggal menghubungi nomor kontak Whatsapp Isuzu Contact Center di nomor <strong>089501701700</strong>.</p>

                                <p>Satu nomor bisa menjawab semua spare part? Bagaimana kalau dari luar pulau? Whatsapp Isuzu Contact Center 089501701700 tersebut sudah dirancang untuk terhubung dengan jaringan terdekat.</p>

                                <p>Misalnya, Isuzu Partner sedang membutuhkan suku cadang filter Isuzu Elf dan mencari lokasi terdekat; atau misalnya bingung mengenai nama spare part yang dibutuhkan, contohnya mungkin karet per belakang Isuzu Elf atau Isuzu Traga.</p>

                                <p>Cukup dengan mengetik "halo" atau sapaan lainnya di nomor Whatsapp tersebut, maka Rangga yang merupakan Asisten Digital akan menjawab dan mengarahkan sesuai kebutuhan pengguna.</p>

                                <div class="alert alert-secondary" style="background-color: #f7f9fc; border-left: 4px solid #e31e24; border-radius: 4px; padding: 18px 20px; margin: 25px 0;">
                                    <p class="font-italic m-b15" style="color: #333;">
                                        <em>"Hai Isuzu Partner! Selamat pagi Bapak/Ibu (menyapa nama sesuai kontak yang terdata). Perkenalkan saya Rangga, Asisten Digital Anda,"</em> demikian pesan awal dari nomor Whatsapp tersebut.
                                    </p>
                                    <p class="font-weight-600 m-b10" style="color: #222;">Selanjutnya, pengguna akan diberikan pilihan informasi yang dibutuhkan:</p>
                                    <ol style="margin-bottom: 0; padding-left: 20px; line-height: 26px;">
                                        <li>Informasi Umum, Produk, dan Jaringan.</li>
                                        <li>Keluhan Pelanggan.</li>
                                        <li>Bantuan Kondisi Darurat.</li>
                                        <li>Booking Layanan Isuzu, perawatan & perbaikan kendaraan.</li>
                                        <li><strong>Kebutuhan Spare Parts.</strong></li>
                                    </ol>
                                </div>

                                <p>Rangga kemudian akan mengarahkan agar pengguna mengetik angka dari salah satu menu di atas untuk mendapatkan informasi yang diinginkan.</p>

                                <p>Untuk kebutuhan spare part, maka ketik angka <strong>"5"</strong>. Selanjutnya, Rangga akan meminta pengguna untuk membagikan titik lokasi atau <em>share location</em>.</p>

                                <p>Tujuannya agar Asisten Digital Isuzu Contact Center ini menghubungkan pengguna dengan agen Isuzu terdekat dari lokasi. Selanjutnya, kebutuhan spare part yang diinginkan akan dikomunikasikan lebih lanjut melalui agen tersebut.</p>

                                <p class="m-b0">Jadi, tidak perlu bingung cari spare part ke mana-mana dan tanya stok sana-sini. Cukup ketik di Whatsapp. Dengan cara ini, maka para pengguna diharapkan semakin dimudahkan dalam memiliki dan merawat Isuzu.</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- Section Detail Sparepart END -->
    </div>
    <!-- Content END -->
@endsection
