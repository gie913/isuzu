<!DOCTYPE html>
<html lang="en">
<head>
	<base href="https://autocare-html.vercel.app/">
	<!-- META -->
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="keywords" content="">
	<meta name="author" content="">
	<meta name="robots" content="">
	<meta name="description" content="AutoCare is well designed creating websites of automotive repair shops, stores with spare parts and accessories for car repairs, car washes, car danting and panting, service stations, car showrooms painting, major auto centers and other sites related to cars and car services.">
	<meta property="og:title" content="Auto Care - Car Services Template">
	<meta property="og:description" content="AutoCare is well designed creating websites of automotive repair shops, stores with spare parts and accessories for car repairs, car washes, car danting and panting, service stations, car showrooms painting, major auto centers and other sites related to cars and car services.">
	<meta property="og:image" content="http://autocare.dexignlab.com/xhtml/social-image.png">
	<meta name="format-detection" content="telephone=no">

	<!-- FAVICONS ICON -->
	<link rel="icon" href="{{ asset('assets/images/logo/x-icon.jpeg') }}" type="image/jpeg">
	<link rel="shortcut icon" type="image/jpeg" href="{{ asset('assets/images/logo/x-icon.jpeg') }}">
	
	<!-- PAGE TITLE HERE -->
	<title>Isuzu Jakarta</title>
	
	<!-- MOBILE SPECIFIC -->
	<meta name="viewport" content="width=device-width, initial-scale=1">

	<!--[if lt IE 9]>
		<script src="js/html5shiv.min.js"></script>
		<script src="js/respond.min.js"></script>
	<![endif]-->
		
	<!-- STYLESHEETS -->
	<link rel="stylesheet" type="text/css" href="css/style.min.css">
	<link rel="stylesheet" type="text/css" href="css/templete.min.css">
	<link class="skin"  rel="stylesheet" type="text/css" href="css/skin/skin-1.css">
	<link class="skin"  rel="stylesheet" type="text/css" href="plugins/rangeslider/rangeslider.css">
	<link rel="stylesheet" type="text/css" href="plugins/lightgallery/css/lightgallery.min.css">
	<!-- Revolution Slider Css -->
	<link rel="stylesheet" type="text/css" href="plugins/revolution/css/settings.css">
	<link rel="stylesheet" type="text/css" href="plugins/revolution/css/navigation.css">
	
	<style>
		#dzSwitcher-right,
		.styleswitcher,
		.styleswitcher-right,
		.DZ-bt-buy-now,
		.DZ-theme-btn,
		.theme-btn,
		.bt-buy-now,
		.bt-support-now {
			display: none !important;
		}

		.header-nav .nav.nav-style > li.active:after {
			content: none !important;
			background-image: none !important;
		}

		/* LightGallery controls fallback & visibility */
		.lg-outer {
			z-index: 9999999 !important;
		}
		.lg-backdrop {
			z-index: 9999998 !important;
		}
		.lg-toolbar {
			z-index: 9999999 !important;
		}
		.lg-toolbar .lg-icon {
			cursor: pointer !important;
		}
		.lg-toolbar .lg-close:after {
			font-family: lg, "Font Awesome 5 Free", sans-serif !important;
		}

		/* Global Inner Banner & Breadcrumb Styling */
		.dlab-bnr-inr-entry .breadcrumb-row {
			display: inline-block;
			padding: 8px 24px !important;
			border-radius: 30px !important;
			background: linear-gradient(135deg, rgba(0, 0, 0, 0.75) 0%, rgba(0, 0, 0, 0.35) 100%) !important;
			backdrop-filter: blur(4px);
			-webkit-backdrop-filter: blur(4px);
			border: 1px solid rgba(255, 255, 255, 0.15) !important;
			margin-top: 15px;
		}
		.dlab-bnr-inr-entry .breadcrumb-row ul {
			margin: 0;
			padding: 0;
		}
		.dlab-bnr-inr-entry .breadcrumb-row ul li {
			color: rgba(255, 255, 255, 0.85);
			font-weight: 500;
		}
		.dlab-bnr-inr-entry .breadcrumb-row ul li a {
			color: #fff;
		}
		.dlab-bnr-inr-entry .breadcrumb-row ul li a:hover {
			color: #e31e24;
		}

		/* Floating WhatsApp Button */
		.float {
			position: fixed;
			width: 60px;
			height: 60px;
			bottom: 40px;
			right: 40px;
			background-color: #25d366;
			color: #fff !important;
			border-radius: 50px;
			text-align: center;
			font-size: 32px;
			box-shadow: 2px 2px 8px rgba(0, 0, 0, 0.25);
			z-index: 999999;
			display: flex;
			align-items: center;
			justify-content: center;
			text-decoration: none;
			transition: all 0.3s ease;
		}
		.float:hover {
			background-color: #1ebe5d;
			color: #fff !important;
			transform: scale(1.08);
			box-shadow: 2px 4px 14px rgba(37, 211, 102, 0.45);
		}
		.my-float {
			margin-top: 0px;
		}
		@media (max-width: 767px) {
			.float {
				width: 50px;
				height: 50px;
				bottom: 25px;
				right: 25px;
				font-size: 26px;
			}
		}
		#loading-area,
		.loading-area,
		.loader1 {
			display: none !important;
		}
	</style>
    @yield('css')
</head>
<body id="bg">

<div class="page-wraper">

    @include('frontend.layouts.partials.header')

    @yield('content')

    @include('frontend.layouts.partials.footer')

    <!-- scroll top button -->
    <button class="scroltop fas fa-arrow-up style4" ></button>

    <!-- Floating WhatsApp Button -->
    <a href="https://api.whatsapp.com/send?phone=6281318941813&amp;text=Assalamu%20alaikum%2C%20saya%20ingin%20info%20lebih%20detail%20mengenai%20produk%20Isuzu%20yang%20anda%20tampilkan%20di%20website" class="float" target="_blank" rel="noopener noreferrer" title="Chat via WhatsApp">
        <i class="fab fa-whatsapp my-float"></i>
    </a>
</div>
<!-- JavaScript  files ========================================= -->
<script src="js/jquery.min.js"></script><!-- JQUERY.MIN JS -->
<script src="plugins/bootstrap/js/bootstrap.bundle.min.js"></script><!-- BOOTSTRAP.MIN JS -->
<script src="plugins/bootstrap-select/bootstrap-select.min.js"></script><!-- FORM JS -->
<script src="plugins/bootstrap-touchspin/jquery.bootstrap-touchspin.js"></script><!-- FORM JS -->
<script src="plugins/magnific-popup/magnific-popup.js"></script><!-- MAGNIFIC POPUP JS -->
<script src="plugins/counter/waypoints-min.js"></script><!-- WAYPOINTS JS -->
<script src="plugins/counter/counterup.min.js"></script><!-- COUNTERUP JS -->
<script src="plugins/imagesloaded/imagesloaded.js"></script><!-- IMAGESLOADED -->
<script src="plugins/masonry/masonry-3.1.4.js"></script><!-- MASONRY -->
<script src="plugins/masonry/masonry.filter.js"></script><!-- MASONRY -->
<script src="plugins/owl-carousel/owl.carousel.js"></script><!-- OWL SLIDER -->
<script src="plugins/rangeslider/rangeslider.js" ></script><!-- Rangeslider -->
<script src="js/custom.min.js"></script><!-- CUSTOM FUCTIONS  -->
<script src="js/dz.carousel.min.js"></script><!-- SORTCODE FUCTIONS  -->

<script src="plugins/lightgallery/js/lightgallery-all.js"></script><!-- LIGHT GALLERY -->
<script src="js/dz.ajax.js"></script><!-- CONTACT JS -->
<!-- SWITCHER REMOVED -->
<!-- REVOLUTION JS FILES -->
<script src="plugins/revolution/js/jquery.themepunch.tools.min.js"></script>
<script src="plugins/revolution/js/jquery.themepunch.revolution.min.js"></script>
<!-- Slider revolution 5.0 Extensions  (Load Extensions only on Local File Systems !  The following part can be removed on Server for On Demand Loading) -->
<script src="plugins/revolution/js/extensions/revolution.extension.actions.min.js"></script>
<script src="plugins/revolution/js/extensions/revolution.extension.carousel.min.js"></script>
<script src="plugins/revolution/js/extensions/revolution.extension.kenburn.min.js"></script>
<script src="plugins/revolution/js/extensions/revolution.extension.layeranimation.min.js"></script>
<script src="plugins/revolution/js/extensions/revolution.extension.migration.min.js"></script>
<script src="plugins/revolution/js/extensions/revolution.extension.navigation.min.js"></script>
<script src="plugins/revolution/js/extensions/revolution.extension.parallax.min.js"></script>
<script src="plugins/revolution/js/extensions/revolution.extension.slideanims.min.js"></script>
<script src="plugins/revolution/js/extensions/revolution.extension.video.min.js"></script>
<script src="js/rev.slider.js"></script>
<script>
jQuery(document).ready(function() {
	'use strict';
	dz_rev_slider_4();

	// Fallback handler for closing LightGallery on backdrop click, close button, or ESC key
	jQuery(document).on('click', '.lg-close, .lg-backdrop', function() {
		var $gallery = jQuery('#lightgallery, .lightgallery');
		if ($gallery.data('lightGallery')) {
			$gallery.data('lightGallery').destroy();
		} else {
			jQuery('.lg-outer').removeClass('lg-visible');
			jQuery('.lg-backdrop').removeClass('in');
			setTimeout(function() {
				jQuery('.lg-outer, .lg-backdrop').remove();
				jQuery('body').removeClass('lg-on lg-from-hash');
			}, 300);
		}
	});

	jQuery(document).on('keydown', function(e) {
		if (e.keyCode === 27 && jQuery('.lg-outer').length) { // Escape key
			var $gallery = jQuery('#lightgallery, .lightgallery');
			if ($gallery.data('lightGallery')) {
				$gallery.data('lightGallery').destroy();
			} else {
				jQuery('.lg-outer').removeClass('lg-visible');
				jQuery('.lg-backdrop').removeClass('in');
				setTimeout(function() {
					jQuery('.lg-outer, .lg-backdrop').remove();
					jQuery('body').removeClass('lg-on lg-from-hash');
				}, 300);
			}
		}
	});
});	/*ready*/
</script>
    @yield('js')
</body>
</html>
