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
</style>
@endsection

@section('content')
	<!-- Content -->
    <div class="page-content bg-white">	
		<!-- Main Slider -->
        <div class="rev-slider">
			<div id="rev_slider_265_1_wrapper" class="rev_slider_wrapper fullwidthbanner-container errow-style-1" data-alias="" data-source="gallery" style="margin:0px auto;background:transparent;padding:0px;margin-top:0px;margin-bottom:0px;">
			<!-- START REVOLUTION SLIDER 5.4.6.3 fullwidth mode -->
			<div id="rev_slider_265_1" class="rev_slider fullwidthabanner" style="display:none;" data-version="5.4.6.3">
				<ul>  <!-- SLIDE  -->
					<li data-index="rs-100" data-transition="fade" data-slotamount="default" data-hideafterloop="0" data-hideslideonmobile="off" data-easein="default" data-easeout="default" data-masterspeed="300" data-thumb="{{ asset('assets/images/banner/banner1.jpeg') }}" data-rotate="0" data-saveperformance="off" data-title="Slide" data-param1="" data-param2="" data-param3="" data-param4="" data-param5="" data-param6="" data-param7="" data-param8="" data-param9="" data-param10="" data-description="">
						<!-- MAIN IMAGE -->
						<img src="{{ asset('assets/images/banner/banner1.jpeg') }}" alt="" data-bgposition="center center" data-bgfit="cover" data-bgrepeat="no-repeat" class="rev-slidebg" data-no-retina>
						 <div class="tp-caption tp-shape tp-shapewrapper " 
							id="slide-100-layer-1" 
							data-x="['center','center','center','center']" data-hoffset="['0','0','0','0']" 
							data-y="['middle','middle','middle','middle']" data-voffset="['0','0','0','0']" 
							data-width="full"
							data-height="full"
							data-whitespace="nowrap"
							data-type="shape" 
							data-basealign="slide" 
							data-responsive_offset="off" 
							data-responsive="off"
							data-frames='[{"from":"opacity:0;","speed":1000,"to":"o:1;","delay":0,"ease":"Power4.easeOut"},{"delay":"wait","speed":1000,"to":"opacity:0;","ease":"Power4.easeOut"}]'
							data-textAlign="['left','left','left','left']"
							data-paddingtop="[0,0,0,0]"
							data-paddingright="[0,0,0,0]"
							data-paddingbottom="[0,0,0,0]"
							data-paddingleft="[0,0,0,0]"
							
							style="z-index: 5;background-color:rgba(0, 0, 0, 0.50);border-color:rgba(0, 0, 0, 0);border-width:0px;"> </div>
						<div class="tp-caption tp-shape d-lg-block d-none tp-shapewrapper bg-primary tp-resizeme" 
							id="slide-100-layer-1" 
							data-x="380" 
							data-y="295" 
							data-width="100"
							data-height="5"
							data-whitespace="nowrap"
							data-type="shape" 
							data-responsive_offset="on" 
							data-frames='[{"from":"x:[-100%];z:0;rX:0deg;rY:0;rZ:0;sX:1;sY:1;skX:0;skY:0;","mask":"x:0px;y:0px;","speed":1500,"to":"o:1;","delay":1000,"ease":"Power4.easeInOut"},{"delay":"wait","speed":1000,"to":"y:[100%];","mask":"x:inherit;y:inherit;","ease":"Power2.easeInOut"}]'
							data-textAlign="['left','left','left','left']"
							data-paddingtop="[0,0,0,0]"
							data-paddingright="[0,0,0,0]"
							data-paddingbottom="[0,0,0,0]"
							data-paddingleft="[0,0,0,0]" style="z-index: 6;border-color:rgba(0, 0, 0, 0.50); border-width:0px;"> </div>
						<!-- LAYER NR. 3 -->
						<div class="tp-caption tp-resizeme d-lg-block d-none text-white" 
							id="slide-100-layer-2" 
							data-x="380" 
							data-y="180" 
							data-width="['auto']"
							data-height="['auto']"
							data-type="text" 
							data-responsive_offset="on" 
							data-frames='[{"delay":"+500","split":"chars","splitdelay":0.05000000000000000277555756156289135105907917022705078125,"speed":2000,"split_direction":"forward","frame":"0","from":"opacity:0;","color":"#000000","to":"o:1;","ease":"Power4.easeInOut"},{"delay":"wait","speed":2000,"frame":"999","color":"transparent","to":"opacity:0;","ease":"Power3.easeInOut"}]'
							data-textAlign="['left','left','left','left']"
							data-paddingtop="[0,0,0,0]"
							data-paddingright="[0,0,0,0]"
							data-paddingbottom="[0,0,0,0]"
							data-paddingleft="[0,0,0,0]"
							style="z-index: 7; white-space: nowrap; font-size: 65px; line-height: 80px; font-weight: 700; letter-spacing: 0px; font-family:'Poppins',sans-serif;">Dukung Kelancaran Bisnis Anda</div>
						<div class="tp-caption tp-resizeme d-lg-block d-none text-primary" 
							id="slide-100-layer-3" 
							data-x="380" 
							data-y="260" 
							data-width="['auto']"
							data-height="['auto']"
							data-type="text" 
							data-responsive_offset="on" 
							data-frames='[{"delay":"+500","split":"chars","splitdelay":0.05000000000000000277555756156289135105907917022705078125,"speed":2000,"split_direction":"forward","frame":"0","from":"opacity:0;","color":"#000000","to":"o:1;","ease":"Power4.easeInOut"},{"delay":"wait","speed":2000,"frame":"999","color":"transparent","to":"opacity:0;","ease":"Power3.easeInOut"}]'
							data-textAlign="['left','left','left','left']"
							data-paddingtop="[0,0,0,0]"
							data-paddingright="[0,0,0,0]"
							data-paddingbottom="[0,0,0,0]"
							data-paddingleft="[110,110,110,110]"
							style="z-index: 7; white-space: nowrap; font-size: 65px; line-height: 80px; font-weight: 700; letter-spacing: 0px; font-family:'Poppins',sans-serif;">Dengan Isuzu</div>
						<!-- LAYER NR. 2 -->
						<div class="tp-caption   tp-resizeme d-lg-block d-none" 
							id="slide-100-layer-4" 
							data-x="380" 
							data-y="360" 
							data-width="[700,700,700,700]"
							data-height="['auto']"
							data-type="text" 
							data-responsive_offset="on" 
							data-frames='[{"delay":"+1990","speed":2000,"frame":"0","from":"opacity:0;","color":"#e5452b","to":"o:1;","ease":"Power4.easeInOut"},{"delay":"wait","speed":1000,"frame":"999","color":"transparent","to":"opacity:0;","ease":"Power3.easeInOut"}]'
							data-textAlign="['inherit','inherit','inherit','inherit']"
							data-paddingtop="[0,0,0,0]"
							data-paddingright="[0,0,0,0]"
							data-paddingbottom="[0,0,0,0]"
							data-paddingleft="[0,0,0,0]"
							style="z-index: 6; font-size: 18px; line-height: 28px; font-weight: 500; color: #fff; white-space: inherit; font-family:'Poppins',sans-serif;">Selamat Jalan menuju kesuksesan
						</div>	
						<!-- LAYER NR. 6 -->
						<div class="tp-caption tp-resizeme d-lg-block d-none" 
							id="slide-100-layer-5" 
							data-x="380" 
							data-y="485" 
							data-width="['auto']"
							data-height="['auto']"
							data-type="button" 
							data-actions=''
							data-responsive_offset="on" 
							data-frames='[{"delay":2000,"speed":1500,"frame":"0","from":"y:[100%];z:0;rX:0deg;rY:0;rZ:0;sX:1;sY:1;skX:0;skY:0;opacity:0;","to":"o:1;","ease":"Power4.easeInOut"},{"delay":"wait","speed":300,"frame":"999","to":"opacity:0;","ease":"Power3.easeInOut"},{"frame":"hover","speed":"300","ease":"Power0.easeInOut","to":"o:1;rX:0;rY:0;rZ:0;z:0;","style":"c:rgba(0,0,0,1);bg:rgba(255,255,255,1);bs:solid;bw:0 0 0 0;"}]'
							data-textAlign="['inherit','inherit','inherit','inherit']"
							data-paddingtop="[0]"
							data-paddingright="[0]"
							data-paddingbottom="[0]"
							data-paddingleft="[0]"
							style="z-index: 6; white-space: nowrap; font-size: 16px; line-height: 30px; font-weight: 600; font-family:'Poppins',sans-serif;border-radius:3px 3px 3px 3px;outline:none;box-shadow:none;box-sizing:border-box;-moz-box-sizing:border-box;-webkit-box-sizing:border-box;cursor:pointer;text-decoration: none;">	<a href="#" class="site-button">Get A Quote</a>
						</div>
						<div class="tp-caption tp-resizeme d-lg-block d-none" 
							id="slide-100-layer-6" 
							data-x="530" 
							data-y="485" 
							data-width="['auto']"
							data-height="['auto']"
							data-type="button" 
							data-actions=''
							data-responsive_offset="on" 
							data-frames='[{"delay":2500,"speed":1500,"frame":"0","from":"y:[100%];z:0;rX:0deg;rY:0;rZ:0;sX:1;sY:1;skX:0;skY:0;opacity:0;","to":"o:1;","ease":"Power4.easeInOut"},{"delay":"wait","speed":300,"frame":"999","to":"opacity:0;","ease":"Power3.easeInOut"},{"frame":"hover","speed":"300","ease":"Power0.easeInOut","to":"o:1;rX:0;rY:0;rZ:0;z:0;","style":"c:rgba(0,0,0,1);bg:rgba(255,255,255,1);bs:solid;bw:0 0 0 0;"}]'
							data-textAlign="['inherit','inherit','inherit','inherit']"
							data-paddingtop="[0]"
							data-paddingright="[0]"
							data-paddingbottom="[0]"
							data-paddingleft="[0]"
							style="z-index: 11; white-space: nowrap; font-size: 16px; line-height: 30px; font-weight: 600; font-family:'Poppins',sans-serif;border-radius:3px 3px 3px 3px;outline:none;box-shadow:none;box-sizing:border-box;-moz-box-sizing:border-box;-webkit-box-sizing:border-box;cursor:pointer;text-decoration: none;">	<a href="#" class="site-button-secondry">Talk To US</a>
						</div>
					</li>
					<li data-index="rs-200" data-transition="fade" data-slotamount="default" data-hideafterloop="0" data-hideslideonmobile="off" data-easein="default" data-easeout="default" data-masterspeed="300" data-thumb="{{ asset('assets/images/banner/banner2.jpeg') }}" data-rotate="0" data-saveperformance="off" data-title="Slide" data-param1="" data-param2="" data-param3="" data-param4="" data-param5="" data-param6="" data-param7="" data-param8="" data-param9="" data-param10="" data-description="">
						<!-- MAIN IMAGE -->
						<img src="{{ asset('assets/images/banner/banner2.jpeg') }}" alt="" data-bgposition="center center" data-bgfit="cover" data-bgrepeat="no-repeat" class="rev-slidebg" data-no-retina>
						 <div class="tp-caption tp-shape tp-shapewrapper " 
							id="slide-200-layer-1" 
							data-x="['center','center','center','center']" data-hoffset="['0','0','0','0']" 
							data-y="['middle','middle','middle','middle']" data-voffset="['0','0','0','0']" 
							data-width="full"
							data-height="full"
							data-whitespace="nowrap"
							data-type="shape" 
							data-basealign="slide" 
							data-responsive_offset="off" 
							data-responsive="off"
							data-frames='[{"from":"opacity:0;","speed":1000,"to":"o:1;","delay":0,"ease":"Power4.easeOut"},{"delay":"wait","speed":1000,"to":"opacity:0;","ease":"Power4.easeOut"}]'
							data-textAlign="['left','left','left','left']"
							data-paddingtop="[0,0,0,0]"
							data-paddingright="[0,0,0,0]"
							data-paddingbottom="[0,0,0,0]"
							data-paddingleft="[0,0,0,0]"
							
							style="z-index: 5;background-color:rgba(0, 0, 0, 0.50);border-color:rgba(0, 0, 0, 0);border-width:0px;"> </div>
						<div class="tp-caption tp-shape d-lg-block d-none tp-shapewrapper bg-primary tp-resizeme" 
							id="slide-200-layer-1" 
							data-x="380" 
							data-y="295" 
							data-width="100"
							data-height="5"
							data-whitespace="nowrap"
							data-type="shape" 
							data-responsive_offset="on" 
							data-frames='[{"from":"x:[-100%];z:0;rX:0deg;rY:0;rZ:0;sX:1;sY:1;skX:0;skY:0;","mask":"x:0px;y:0px;","speed":1500,"to":"o:1;","delay":1000,"ease":"Power4.easeInOut"},{"delay":"wait","speed":1000,"to":"y:[100%];","mask":"x:inherit;y:inherit;","ease":"Power2.easeInOut"}]'
							data-textAlign="['left','left','left','left']"
							data-paddingtop="[0,0,0,0]"
							data-paddingright="[0,0,0,0]"
							data-paddingbottom="[0,0,0,0]"
							data-paddingleft="[0,0,0,0]" style="z-index: 6;border-color:rgba(0, 0, 0, 0.50); border-width:0px;"> </div>
						<!-- LAYER NR. 3 -->
						<div class="tp-caption tp-resizeme d-lg-block d-none text-white" 
							id="slide-200-layer-2" 
							data-x="380" 
							data-y="180" 
							data-width="['auto']"
							data-height="['auto']"
							data-type="text" 
							data-responsive_offset="on" 
							data-frames='[{"delay":"+500","split":"chars","splitdelay":0.05000000000000000277555756156289135105907917022705078125,"speed":2000,"split_direction":"forward","frame":"0","from":"opacity:0;","color":"#000000","to":"o:1;","ease":"Power4.easeInOut"},{"delay":"wait","speed":2000,"frame":"999","color":"transparent","to":"opacity:0;","ease":"Power3.easeInOut"}]'
							data-textAlign="['left','left','left','left']"
							data-paddingtop="[0,0,0,0]"
							data-paddingright="[0,0,0,0]"
							data-paddingbottom="[0,0,0,0]"
							data-paddingleft="[0,0,0,0]"
							style="z-index: 7; white-space: nowrap; font-size: 65px; line-height: 80px; font-weight: 700; letter-spacing: 0px; font-family:'Poppins',sans-serif;">ISUZU ELF Box Besi</div>
						<!-- LAYER NR. 2 -->
						<div class="tp-caption   tp-resizeme d-lg-block d-none" 
							id="slide-200-layer-4" 
							data-x="380" 
							data-y="360" 
							data-width="[700,700,700,700]"
							data-height="['auto']"
							data-type="text" 
							data-responsive_offset="on" 
							data-frames='[{"delay":"+1990","speed":2000,"frame":"0","from":"opacity:0;","color":"#e5452b","to":"o:1;","ease":"Power4.easeInOut"},{"delay":"wait","speed":1000,"frame":"999","color":"transparent","to":"opacity:0;","ease":"Power3.easeInOut"}]'
							data-textAlign="['inherit','inherit','inherit','inherit']"
							data-paddingtop="[0,0,0,0]"
							data-paddingright="[0,0,0,0]"
							data-paddingbottom="[0,0,0,0]"
							data-paddingleft="[0,0,0,0]"
							style="z-index: 6; font-size: 18px; line-height: 28px; font-weight: 500; color: #fff; white-space: inherit; font-family:'Poppins',sans-serif;">Partner Andal Bisnis Logistik Anda
						</div>		
						<!-- LAYER NR. 6 -->
						<div class="tp-caption tp-resizeme d-lg-block d-none" 
							id="slide-200-layer-5" 
							data-x="380" 
							data-y="485" 
							data-width="['auto']"
							data-height="['auto']"
							data-type="button" 
							data-actions=''
							data-responsive_offset="on" 
							data-frames='[{"delay":2000,"speed":1500,"frame":"0","from":"y:[100%];z:0;rX:0deg;rY:0;rZ:0;sX:1;sY:1;skX:0;skY:0;opacity:0;","to":"o:1;","ease":"Power4.easeInOut"},{"delay":"wait","speed":300,"frame":"999","to":"opacity:0;","ease":"Power3.easeInOut"},{"frame":"hover","speed":"300","ease":"Power0.easeInOut","to":"o:1;rX:0;rY:0;rZ:0;z:0;","style":"c:rgba(0,0,0,1);bg:rgba(255,255,255,1);bs:solid;bw:0 0 0 0;"}]'
							data-textAlign="['inherit','inherit','inherit','inherit']"
							data-paddingtop="[0]"
							data-paddingright="[0]"
							data-paddingbottom="[0]"
							data-paddingleft="[0]"
							style="z-index: 6; white-space: nowrap; font-size: 16px; line-height: 30px; font-weight: 600; font-family:'Poppins',sans-serif;border-radius:3px 3px 3px 3px;outline:none;box-shadow:none;box-sizing:border-box;-moz-box-sizing:border-box;-webkit-box-sizing:border-box;cursor:pointer;text-decoration: none;">	<a href="#" class="site-button">Get A Quote</a>
						</div>
						<div class="tp-caption tp-resizeme d-lg-block d-none" 
							id="slide-200-layer-6" 
							data-x="530" 
							data-y="485" 
							data-width="['auto']"
							data-height="['auto']"
							data-type="button" 
							data-actions=''
							data-responsive_offset="on" 
							data-frames='[{"delay":2500,"speed":1500,"frame":"0","from":"y:[100%];z:0;rX:0deg;rY:0;rZ:0;sX:1;sY:1;skX:0;skY:0;opacity:0;","to":"o:1;","ease":"Power4.easeInOut"},{"delay":"wait","speed":300,"frame":"999","to":"opacity:0;","ease":"Power3.easeInOut"},{"frame":"hover","speed":"300","ease":"Power0.easeInOut","to":"o:1;rX:0;rY:0;rZ:0;z:0;","style":"c:rgba(0,0,0,1);bg:rgba(255,255,255,1);bs:solid;bw:0 0 0 0;"}]'
							data-textAlign="['inherit','inherit','inherit','inherit']"
							data-paddingtop="[0]"
							data-paddingright="[0]"
							data-paddingbottom="[0]"
							data-paddingleft="[0]"
							style="z-index: 11; white-space: nowrap; font-size: 16px; line-height: 30px; font-weight: 600; font-family:'Poppins',sans-serif;border-radius:3px 3px 3px 3px;outline:none;box-shadow:none;box-sizing:border-box;-moz-box-sizing:border-box;-webkit-box-sizing:border-box;cursor:pointer;text-decoration: none;">	<a href="#" class="site-button-secondry">Talk To US</a>
						</div>
					</li>
				</ul>
				<div class="tp-bannertimer tp-bottom" style="visibility: hidden !important;"></div> </div>
			</div>  
		</div>  
        <!-- Main Slider -->
		<!-- About Section -->
		<div class="section-full">
			<div class="container">
				<form action="{{ route('produk') }}" method="GET" class="row car-search-box m-lr0 p-lr15 p-b30 p-t15 bg-primary">
					<!-- 1. Jenis Kendaraan -->
					<div class="col-lg-3 col-md-4 col-sm-6 m-b15">
						<label class="text-white">Jenis Kendaraan</label>
						<div class="selected-box">
							<select name="jenis_kendaraan" class="selectpicker" data-live-search="true">
								<option value="">Semua Jenis Kendaraan</option>
								<option value="cv">Kendaraan Komersial (CV - Commercial Vehicle)</option>
								<option value="lcv">Kendaraan Penumpang / Light Commercial Vehicle (LCV)</option>
							</select>
						</div>
					</div>

					<!-- 2. Kategori -->
					<div class="col-lg-3 col-md-4 col-sm-6 m-b15">
						<label class="text-white">Kategori</label>
						<div class="selected-box">
							<select name="kategori" class="selectpicker" data-live-search="true">
								<option value="">Semua Kategori</option>
								<option value="suv_double_cabin">Kendaraan Penumpang / SUV & Double Cabin (MU-X, D-Max)</option>
								<option value="light_truck">Truk Ringan / Light Truck (Elf / N-Series)</option>
								<option value="medium_truck">Truk Sedang / Medium Truck (Giga / F-Series)</option>
								<option value="tractor_head">Truk Berat / Tractor Head (Giga Tractor Head / C&E Series)</option>
								<option value="bus_microbus">Bus / Microbus (Elf Bus, Traga Bus)</option>
								<option value="pickup_logistik">Pick-up / Logistik Ringan (Traga)</option>
							</select>
						</div>
					</div>

					<!-- 3. Tipe Karoseri / Aplikasi Bisnis -->
					<div class="col-lg-3 col-md-4 col-sm-6 m-b15">
						<label class="text-white">Tipe Karoseri / Aplikasi Bisnis</label>
						<div class="selected-box">
							<select name="karoseri" class="selectpicker" data-live-search="true">
								<option value="">Semua Tipe Karoseri</option>
								<option value="box">Box (Besi/Aluminium)</option>
								<option value="bak">Bak (Besi/Kayu/Drop Side)</option>
								<option value="dump_truck">Dump Truck</option>
								<option value="tangki">Tangki</option>
								<option value="wingbox">Wingbox</option>
								<option value="flat_deck">Flat Deck / Logging</option>
								<option value="microbus_ambulance">Microbus / Ambulance, Crane / Towing, Garbage / Compactor</option>
							</select>
						</div>
					</div>

					<!-- 4. Konfigurasi Roda & Penggerak (Axle & Drive) -->
					<div class="col-lg-3 col-md-4 col-sm-6 m-b15">
						<label class="text-white">Konfigurasi Roda & Penggerak</label>
						<div class="selected-box">
							<select name="konfigurasi_roda" class="selectpicker" data-live-search="true">
								<option value="">Semua Konfigurasi Roda</option>
								<option value="4_roda_4x2">4 Roda (4x2 / Engkel)</option>
								<option value="6_roda_4x2">6 Roda (4x2)</option>
								<option value="6x2">6x2 (Tronton banci)</option>
								<option value="6x4">6x4 (Tronton Heavy Duty / Off-road)</option>
								<option value="4x4">4x4 (Rigid / Double Cabin)</option>
							</select>
						</div>
					</div>

					<!-- 5. Gross Vehicle Weight (GVW) / Bobot Muatan -->
					<div class="col-lg-3 col-md-4 col-sm-6 m-b15">
						<label class="text-white">GVW / Bobot Muatan</label>
						<div class="selected-box">
							<select name="gvw" class="selectpicker" data-live-search="true">
								<option value="">Semua GVW / Bobot Muatan</option>
								<option value="5-10">5 – 10 Ton</option>
								<option value="10-16">10 – 16 Ton</option>
								<option value="gt16">&gt; 16 Ton</option>
							</select>
						</div>
					</div>

					<!-- 6. Panjang Sasis / Jarak Sumbu Roda (Wheelbase) -->
					<div class="col-lg-3 col-md-4 col-sm-6 m-b15">
						<label class="text-white">Panjang Sasis (Wheelbase)</label>
						<div class="selected-box">
							<select name="wheelbase" class="selectpicker" data-live-search="true">
								<option value="">Semua Jarak Sumbu Roda</option>
								<option value="swb">Pendek (Short Wheelbase / SWB)</option>
								<option value="mwb">Sedang (Medium Wheelbase / MWB)</option>
								<option value="lwb">Panjang (Long Wheelbase / LWB / Extra Long)</option>
							</select>
						</div>
					</div>

					<!-- 7. Rentang Tenaga -->
					<div class="col-lg-3 col-md-4 col-sm-6 m-b15">
						<label class="text-white">Rentang Tenaga</label>
						<div class="selected-box">
							<select name="tenaga" class="selectpicker" data-live-search="true">
								<option value="">Semua Rentang Tenaga</option>
								<option value="100-150">100 – 150 PS</option>
								<option value="150-240">150 – 240 PS</option>
								<option value="240-285">240 – 285 PS</option>
								<option value="300+">300+ PS</option>
							</select>
						</div>
					</div>

					<!-- 8. Kapasitas Penumpang -->
					<div class="col-lg-3 col-md-4 col-sm-6 m-b15">
						<label class="text-white">Kapasitas Penumpang</label>
						<div class="selected-box">
							<select name="kapasitas_penumpang" class="selectpicker" data-live-search="true">
								<option value="">Semua Kapasitas</option>
								<option value="2">2 Seater</option>
								<option value="5">5 Seater</option>
								<option value="7">7 Seater (MU-X)</option>
							</select>
						</div>
					</div>

					<!-- 10. Rentang Harga (Slider) -->
					<div class="col-lg-6 col-md-8 col-sm-12 m-b15">							
						<label class="text-white">Rentang Harga</label>
						<div class="price-slide-2 range-slider">
							<div class="price">
								<input type="text" id="amount-2" name="rentang_harga" class="amount" readonly="" value="Rp 200 Juta - Rp 1.5 Milyar" />
								<div id="slider-range-2"></div>
							</div>
						</div>
					</div>

					<!-- Submit Button -->
					<div class="col-lg-3 col-md-4 col-sm-12 m-b15 d-flex align-items-end">
						<button type="submit" class="site-button-secondry black w-100" style="height: 45px; display: flex; align-items: center; justify-content: center; gap: 8px;">
							<i class="fa fa-search"></i> Cari Kendaraan
						</button>
					</div>
				</form>
			</div>
		</div>	
	    <!-- About Section End -->
		<!-- Our Services -->
		<div class="section-full content-inner car-services" style="background-image:url(images/background/bg11.jpg); background-repeat:no-repeat; background-position:bottom;">
			<div class="container">
				<div class="row m-b40">
					<div class="col-lg-6 col-md-6 col-sm-12 section-head align-self-center">
						 <h2 class="text-uppercase">Selamat datang di <span class="text-primary">Isuzu Jakarta</span></h2>
						<h5 class="font-weight-400">Siap melayani kendaraan niaga untuk bisnis anda </h5>
						<p class="m-b0">Kami menyediakan solusi armada transportasi dan kendaraan niaga Isuzu terlengkap untuk mengoptimalkan operasional bisnis Anda. Mulai dari logistik perkotaan dengan Isuzu Traga dan Elf, hingga distribusi skala besar dan pertambangan bersama Isuzu Giga yang tangguh, efisien bahan bakar, serta didukung kemudahan pembuatan karoseri dan jaringan layanan purna jual terpercaya.</p>
					</div>
					<div class="col-lg-6 col-md-6 col-sm-12 align-self-center">
						<img src="{{ asset('assets/images/product/dmax.png') }}" alt="Isuzu D-Max">
					</div>
				</div>
				<div class="row">
					<div class="col-md-6 col-lg-3 m-b30">
						<div class="icon-bx-wraper p-a20">
							<div class="icon-md text-primary m-b15"> <span class="icon-cell"><i class="ti-headphone-alt"></i></span> </div>
							<div class="icon-content">
								<h5 class="dlab-tilte text-uppercase">Pelayanan Cepat</h5>
								<p>Respon cepat untuk pertanyaan, penawaran, dan kebutuhan servis kendaraan niaga Anda.</p>
							</div>
						</div>
					</div>
					<div class="col-md-6 col-lg-3 m-b30">
						<div class="icon-bx-wraper p-a20">
							<div class="icon-md text-primary m-b15"> <span class="icon-cell"><i class="ti-credit-card"></i></span> </div>
							<div class="icon-content">
								<h5 class="dlab-tilte text-uppercase">Pilihan Harga Terbaik</h5>
								<p>Angsuran Ringan, Gratis Karoseri, Bunga Leasing Yang bisa di negosiasi </p>
							</div>
						</div>
					</div>
					<div class="col-md-6 col-lg-3 m-b30">
						<div class="icon-bx-wraper p-a20">
							<div class="icon-md text-primary m-b15"> <span class="icon-cell"><i class="ti-map-alt"></i></span> </div>
							<div class="icon-content">
								<h5 class="dlab-tilte text-uppercase">Jaringan Distribusi</h5>
								<p>Menyediakan pengiriman ke seluruh wilayah di Indonesia</p>
							</div>
						</div>
					</div>
					<div class="col-md-6 col-lg-3 m-b30">
						<div class="icon-bx-wraper p-a20">
							<div class="icon-md text-primary m-b15"> <span class="icon-cell"><i class="ti-thumb-up"></i></span> </div>
							<div class="icon-content">
								<h5 class="dlab-tilte text-uppercase">Terpercaya</h5>
								<p>Dealer Resmi Isuzu dengan reputasi terpercaya dan pengalaman bertahun-tahun dalam melayani pelanggan.</p>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	    <!-- Our Services END-->
        <!-- Services -->
        <div class="section-full bg-img-fix content-inner-1 overlay-primary-dark" style="background-image:url(images/background/bg5.jpg); margin-top:-1px">
            <div class="container">
                <div class="section-head text-center text-white">
                    <h2 class="text-uppercase">Berita terbaru </h2>
					
                </div>
                <div class="section-content">
					@php
						$newsItems = [
							[
								'title' => 'Isuzu Pastikan Kendaraan Tetap Aman Pakai B50, Lolos Pengetesan 60.000 Km',
								'date_day' => '11 Sep',
								'date_year' => '2026',
								'author' => 'lukman',
								'comments' => '12',
								'excerpt' => 'PT Isuzu Astra Motor Indonesia (IAMI) memastikan kendaraan komersial Isuzu tetap aman dan tangguh saat menggunakan bahan bakar biodiesel B50 setelah lolos pengetesan intensif sejauh 60.000 Km.',
								'image' => asset('assets/images/news/info1.jpeg'),
								'link' => '#',
							],
							[
								'title' => 'Gali Jurus-Jurus Jitu Berniaga di Tengah Pandemi',
								'date_day' => '11 Sep',
								'date_year' => '2026',
								'author' => 'lukman',
								'comments' => '8',
								'excerpt' => 'Kita sama-sama menyadari bahwa saat ini adalah fase bangkit dari kondisi akibat pandemi lalu. Berbagai strategi bisnis dan efisiensi armada menjadi kunci utama keberhasilan berniaga.',
								'image' => asset('assets/images/news/info2.jpg'),
								'link' => '#',
							],
							[
								'title' => '"Uang Jalan Masih Sisa, Lumayan Buat Beli Baju"',
								'date_day' => '25 Jun',
								'date_year' => '2021',
								'author' => 'lukman',
								'comments' => '15',
								'excerpt' => 'Dalam sekali jalan, kami melihat bagaimana sebuah truk membutuhkan bermacam komponen biaya. Efisiensi bahan bakar Isuzu terbukti nyata menghemat pengeluaran operasional.',
								'image' => asset('assets/images/news/info3.jpg'),
								'link' => '#',
							],
							[
								'title' => 'TEST COMMON-RAIL DIESEL, ANDALAN TEKNOLOGI ISUZU',
								'date_day' => '21 Jan',
								'date_year' => '2019',
								'author' => 'lukmanbudi',
								'comments' => '10',
								'excerpt' => 'Isuzu merupakan manufaktur yang bisa dibilang sebagai pioner dalam pemakaian teknologi common-rail pada mesin diesel dengan performa bertenaga dan efisiensi konsumsi bahan bakar yang tinggi.',
								'image' => asset('assets/images/news/info4.jpg'),
								'link' => '#',
							],
							[
								'title' => '2018 TAHUN INFRASTRUKTUR ISUZU OPTIMIS PENJUALAN KENDARAAN KOMERSIAL MENINGKAT',
								'date_day' => '09 Jan',
								'date_year' => '2019',
								'author' => 'lukmanbudi',
								'comments' => '6',
								'excerpt' => 'Tahun 2017 kemarin adalah tahun yang menggembirakan untuk penjualan kendaraan komersial, di mana pertumbuhan proyek infrastruktur turut mendorong permintaan armada logistik Isuzu.',
								'image' => asset('assets/images/news/info5.jpg'),
								'link' => '#',
							],
						];
					@endphp
					<div class="latest-news owl-carousel owl-theme owl-dots-white-full owl-loaded owl-drag owl-btn-3 btn-white owl-btn-center-lr">
						@foreach($newsItems as $news)
						<div class="item">
							<div class="ow-carousel-entry">
								<div class="ow-entry-media dlab-img-effect zoom-slow">
									<a href="{{ $news['link'] }}"><img src="{{ $news['image'] }}" alt="{{ $news['title'] }}" style="height: 220px; object-fit: cover; width: 100%;"></a>
								</div>
								<div class="dlab-post-info p-lr20 p-b10 p-t20 bg-white">
									<div class="dlab-post-title ">
										<h3 class="m-t0 m-b5"><a href="{{ $news['link'] }}">{{ Str::limit($news['title'], 55) }}</a></h3>
									</div>
									<div class="dlab-post-meta ">
										<ul>
											<li class="post-date"> <i class="ti-calendar"></i><strong>{{ $news['date_day'] }}</strong> <span> {{ $news['date_year'] }}</span> </li>
											<li class="post-author"><i class="fas fa-user"></i>By <a href="#">{{ $news['author'] }}</a> </li>
											<li class="post-comment"><i class="fas fa-comments"></i> <a href="#">{{ $news['comments'] }}</a> </li>
										</ul>
									</div>
									<div class="dlab-post-text">
										<p>{{ Str::limit($news['excerpt'], 130) }}</p>
									</div>
									<div class="dlab-post-readmore"> 
										<a href="{{ $news['link'] }}" title="READ MORE" rel="bookmark" class="site-button-link">READ MORE<i class="fas fa-angle-double-right"></i></a> 
									</div>
								</div>
							</div>
						</div>
						@endforeach
					</div>
                </div>
            </div>
        </div>
        <!-- Services End -->
		<!-- Meet Our Team -->
        <div class="section-full bg-white content-inner recent-vehicle">
            <div class="container">
                <div class="section-head text-center ">
                    <h2 class="text-uppercase">Produk terbaru <span class="text-primary">Isuzu </span></h2>
                   
                </div>
                <div class="section-content ">
                    <div class="row">
						@php
							$featuredProducts = [
								[
									'name' => 'ISUZU ELF MICROBUS NEW ARMADA 20 SEAT',
									'power' => '120 PS',
									'price' => 'Rp 654.000.000',
									'promo' => true,
									'image' => asset('assets/images/product/product1.jpg'),
									'link' => route('detail-produk'),
								],
								[
									'name' => 'ISUZU ELF NMR L',
									'power' => '150 PS',
									'price' => 'Rp 522.000.000',
									'promo' => true,
									'image' => asset('assets/images/product/product2.jpg'),
									'link' => route('detail-produk'),
								],
								[
									'name' => 'ISUZU TRAGA FD - WHITE',
									'power' => '80 PS',
									'price' => 'Rp 297.000.000',
									'promo' => false,
									'image' => asset('assets/images/product/product3.jpg'),
									'link' => route('detail-produk'),
								],
								[
									'name' => 'ISUZU ELF NLR',
									'power' => '120 PS',
									'price' => 'Rp 433.000.000',
									'promo' => false,
									'image' => asset('assets/images/product/product4.jpg'),
									'link' => route('detail-produk'),
								],
								[
									'name' => 'ISUZU ELF NLR L',
									'power' => '120 PS',
									'price' => 'Rp 450.000.000',
									'promo' => false,
									'image' => asset('assets/images/product/product5.jpg'),
									'link' => route('detail-produk'),
								],
								[
									'name' => 'ISUZU TRAGA BLACK PREMIUM AC',
									'power' => '80 PS',
									'price' => 'Rp 308.000.000',
									'promo' => false,
									'image' => asset('assets/images/product/product6.jpg'),
									'link' => route('detail-produk'),
								],
							];
						@endphp
						<div class="col-lg-12 col-md-12">
							<ul id="masonry" class="row dlab-gallery-listing gallery-grid-4 gallery lightgallery m-b0">
								@foreach($featuredProducts as $item)
								<li class="card-container col-lg-6 col-xl-4 col-md-6 col-sm-6 col-12 m-b30">
									<div class="dlab-box dlab-gallery-box" style="position: relative; border: 1px solid #eee; border-radius: 4px; overflow: hidden; background: #fff;">
										@if(!empty($item['promo']))
											<span class="product-promo-badge"><i class="fas fa-tag"></i> Promo</span>
										@endif
										<div class="dlab-media dlab-img-overlay1 dlab-img-effect zoom-slow"> 
											<a href="{{ $item['link'] }}"><img src="{{ $item['image'] }}" alt="{{ $item['name'] }}" style="height: 240px; width: 100%; object-fit: cover;"></a>
											<div class="overlay-bx">
												<div class="overlay-icon"> 
													<a href="{{ $item['link'] }}">
														<i class="fas fa-link icon-bx-xs"></i> 
													</a> 
													<span data-exthumbimage="{{ $item['image'] }}" data-src="{{ $item['image'] }}" class="far fa-image icon-bx-xs check-km" title="{{ $item['name'] }}"></span>
												</div>
											</div>
										</div>
										<div class="ow-entry-content text-center p-a20">
											<h4 class="font-weight-600 m-b15" style="min-height: 48px; font-size: 16px; line-height: 24px;">
												<a href="{{ $item['link'] }}">{{ $item['name'] }}</a>
											</h4>
											<div class="car-part-list m-b15">
												<ul class="list-inline" style="margin-bottom: 0;">
													<li><i class="fas fa-tachometer-alt text-primary m-r5"></i> Tenaga: {{ $item['power'] }}</li>
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
        <!-- Meet Our Team END -->
        <!-- Testimoniyal -->
		<div class="section-full bg-img-fix content-inner overlay-white-dark" style="background-image:url(images/background/bg5.jpg); margin-top:-1px">
            <div class="container">
				<div class="section-head text-center ">
                    <h2 class="text-uppercase">Testimoni <span class="text-primary">Konsumen</span></h2>
					
                </div>
				<div class="section-content">
					<div class="testimonial-five owl-carousel owl-dots-none owl-theme owl-dots-white-full owl-loaded owl-drag owl-btn-3 owl-btn-center-lr">
						<div class="item">
							<div class="testimonial-6">
								<div class="testimonial-text bg-white quote-left quote-right">
									<p>Pelayanan sangat ramah dan humble </p>
								</div>
								<div class="testimonial-detail clearfix bg-primary text-white">
									<h4 class="testimonial-name m-tb0">David Matin</h4>
									<span class="testimonial-position">Pengusaha</span>
									<div class="testimonial-pic radius"><img src="images/testimonials/pic1.jpg" alt="" width="100" height="100"></div>
								</div>
							</div>
						</div>
						<div class="item">
							<div class="testimonial-6">
								<div class="testimonial-text bg-white quote-left quote-right">
									<p>Sat..set banget </p>
								</div>
								<div class="testimonial-detail clearfix bg-primary text-white">
									<h4 class="testimonial-name m-tb0">Salva Morina</h4>
									<span class="testimonial-position">Manajer Logistik</span>
									<div class="testimonial-pic radius"><img src="images/testimonials/pic2.jpg" alt="" width="100" height="100"></div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
		<!-- Testimoniyal End -->
        <!-- Client logo -->
        <div class="section-full dlab-we-find bg-img-fix p-t50 p-b50 ">
            <div class="container">
                <div class="section-content">
                    <div class="client-logo-carousel owl-carousel mfp-gallery gallery owl-btn-center-lr">
                        <div class="item">
                            <div class="ow-client-logo">
                                <div class="client-logo"><a href="#"><img src="images/client-logo/logo1.jpg" alt=""></a></div>
                            </div>
                        </div>
                        <div class="item">
                            <div class="ow-client-logo">
                                <div class="client-logo"> <a href="#"><img src="images/client-logo/logo2.jpg" alt=""></a> </div>
                            </div>
                        </div>
                        <div class="item">
                            <div class="ow-client-logo">
                                <div class="client-logo"> <a href="#"><img src="images/client-logo/logo1.jpg" alt=""></a> </div>
                            </div>
                        </div>
                        <div class="item">
                            <div class="ow-client-logo">
                                <div class="client-logo"> <a href="#"><img src="images/client-logo/logo3.jpg" alt=""></a> </div>
                            </div>
                        </div>
                        <div class="item">
                            <div class="ow-client-logo">
                                <div class="client-logo"> <a href="#"><img src="images/client-logo/logo4.jpg" alt=""></a> </div>
                            </div>
                        </div>
                        <div class="item">
                            <div class="ow-client-logo">
                                <div class="client-logo"> <a href="#"><img src="images/client-logo/logo3.jpg" alt=""></a> </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- Client logo END -->
    </div>
    <!-- Content END-->

@endsection

@section('js')
<script>
jQuery(document).ready(function($) {
    if ($("#slider-range-2").length > 0) {
        // Format Rupiah function
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

        // Set initial label
        $("#amount-2").val(formatRupiah(200) + " - " + formatRupiah(1500));
    }
});
</script>
@endsection
