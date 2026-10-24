@extends('frontend.layouts.app')

@section('content')
    <!-- Content -->
    <div class="page-content bg-white">
        <!-- Inner Banner -->
        <div class="dlab-bnr-inr overlay-black-middle" style="background-image:url({{ asset('assets/images/banner/banner1.jpeg') }});">
            <div class="container">
                <div class="dlab-bnr-inr-entry">
                    <h1 class="text-white">BROSUR & PRICELIST</h1>
                    <!-- Breadcrumb row -->
                    <div class="breadcrumb-row">
                        <ul class="list-inline">
                            <li><a href="{{ route('index') }}">Beranda</a></li>
                            <li>Informasi</li>
                            <li>Brosur</li>
                        </ul>
                    </div>
                    <!-- Breadcrumb row END -->
                </div>
            </div>
        </div>
        <!-- Inner Banner END -->

        <!-- Section Brosur Table -->
        <div class="section-full content-inner bg-white">
            <div class="container">
                <div class="section-head text-center">
                    <h2 class="text-uppercase">UNDUH <span class="text-primary">BROSUR RESMI ISUZU</span></h2>
                    <p>Dapatkan informasi lengkap mengenai syarat pengajuan kredit, brosur spesifikasi unit, dan daftar harga resmi kendaraan komersial Isuzu.</p>
                </div>

                <div class="row justify-content-center">
                    <div class="col-lg-10 col-md-12">
                        <div class="table-responsive p-a20 bg-white" style="border: 1px solid #e1e6eb; border-radius: 8px; box-shadow: 0 4px 15px rgba(0,0,0,0.03);">
                            <table class="table table-hover tabelbrosur align-middle m-b0">
                                <thead class="bg-gray" style="border-bottom: 2px solid #e31e24;">
                                    <tr>
                                        <th style="width: 80px;" class="text-center font-weight-700">No</th>
                                        <th class="font-weight-700">Nama Brosur</th>
                                        <th class="font-weight-700">Tipe</th>
                                        <th style="width: 160px;" class="text-center font-weight-700">Download</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td class="text-center font-weight-600">1</td>
                                        <td class="font-weight-600">
                                            <i class="far fa-file-pdf text-danger m-r10 fa-lg"></i>
                                            PERSYARATAN KREDIT KENDARAAN
                                        </td>
                                        <td><span class="badge badge-secondary" style="background: #6c757d; color: #fff; padding: 4px 10px; border-radius: 4px;">Brosur Pricelist</span></td>
                                        <td class="text-center">
                                            <a class="site-button btn-sm" href="https://isuzujakarta.com/assets/files/brosur/brosur_PERSYARATAN_KREDIT_KENDARAAN_ISUZU.pdf" target="_blank" download style="display: inline-flex; align-items: center; gap: 6px;">
                                                <i class="fas fa-download"></i> Download
                                            </a>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="text-center font-weight-600">2</td>
                                        <td class="font-weight-600">
                                            <i class="far fa-file-pdf text-danger m-r10 fa-lg"></i>
                                            PRICE LIST HARGA NIK 2026
                                        </td>
                                        <td><span class="badge badge-secondary" style="background: #6c757d; color: #fff; padding: 4px 10px; border-radius: 4px;">Brosur Pricelist</span></td>
                                        <td class="text-center">
                                            <a class="site-button btn-sm" href="https://isuzujakarta.com/assets/files/brosur/PRICE_LIST_UPDATE_-_AGUSTUS_2026.pdf" target="_blank" download style="display: inline-flex; align-items: center; gap: 6px;">
                                                <i class="fas fa-download"></i> Download
                                            </a>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- Section Brosur Table END -->
    </div>
    <!-- Content END -->
@endsection
