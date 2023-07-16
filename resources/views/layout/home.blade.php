<!DOCTYPE html>
<html lang="en">
<head>
	<title>@yield('title', 'Beranda | Warung')</title>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
	<meta name="description" content="">
	<meta name="author" content="">

	<!-- <meta charset="utf-8"> -->
	<!--[if IE]><meta http-equiv='X-UA-Compatible' content='IE=edge,chrome=1'><![endif]-->
	<!-- <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1"> -->
	<!-- <meta name="description" content=""> -->

	<meta name="csrf-token" content="{{csrf_token()}}"><!--csrfToken-->
	<!-- Hanya untuk https, jika menggunakan http, silahkan matikan tag html ini-->
	<!-- <meta http-equiv="Content-Security-Policy" content="upgrade-insecure-requests"/> -->



	<!-- Google Fonts -->
	<link href='https://fonts.googleapis.com/css?family=Montserrat:400,700%7COpen+Sans:400,400i,600,700' rel='stylesheet'>
	<!-- Css -->
	<link rel="stylesheet" href="{{asset('front/css/bootstrap.min.css')}}" />
	<link rel="stylesheet" href="{{asset('front/css/magnific-popup.css')}}" />
	<link rel="stylesheet" href="{{asset('front/css/font-icons.css')}}" />
	<link rel="stylesheet" href="{{asset('front/css/sliders.css')}}" />
	<link rel="stylesheet" href="{{asset('front/css/style.css')}}" />
	<!-- Favicons -->
	{{-- <link rel="shortcut icon" href="/front/img/favicon.ico"> --}}
	<link rel="apple-touch-icon" href="/front/img/apple-touch-icon.png">
	<link rel="apple-touch-icon" sizes="72x72" href="/front/img/apple-touch-icon-72x72.png">
	<link rel="apple-touch-icon" sizes="114x114" href="/front/img/apple-touch-icon-114x114.png">
	<!-- icon web -->
	<!-- <link rel="icon" href="/uploads/icon.png"> -->
	<link rel="icon" href="{{ url('uploads/icon.png') }}" type="image/png" />
	

	<style>
		.menu{
			padding:1rem 1rem 1rem 55rem;
		}
	</style>
</head>
<body class="relative">
	<div class="loader-mask">
		<div class="loader">
			<div></div>
			<div></div>
		</div>
	</div>
	<main class="main-wrapper">
		<header class="nav-type-1">
			<div class="search-wrap">
				<div class="search-inner">
					<div class="search-cell">
						<form method="get">
							<div class="search-field-holder">
								<input type="search" class="form-control main-search-input" placeholder="Search for">
								<i class="ui-close search-close" id="search-close"></i>
							</div>
						</form>
					</div>
				</div>
			</div> <!-- end fullscreen search -->

			<nav class="navbar navbar-static-top">
				<div class="navigation" id="sticky-nav">
					<div class="container relative">
						<div class="row flex-parent">
							<div class="navbar-header flex-child">
								<div class="logo-container">
									<div class="logo-wrap">
										<a href="/">
											<img class="logo-dark2" src="/uploads/Logo.png" alt="logo">
										</a>
									</div>
								</div>
								<button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#navbar-collapse">
									<span class="sr-only">Toggle navigation</span>
									<span class="icon-bar"></span>
									<span class="icon-bar"></span>
									<span class="icon-bar"></span>
								</button>
								<div class="nav-cart mobile-cart hidden-lg hidden-md">
									<div class="nav-cart-outer">
										<div class="nav-cart-inner">
												@if(!empty($user = Auth::guard('webmember')->user()) && isset($order))
												<a href="/cart?id={{$order?$order->id:''}}" class="nav-cart-icon" id="link-keranjang"><span style="font-size:15px;" id="keranjang">{{$order?$order->order_detail_count:0}}</span></a>
												@endif
											<!-- <a href="  -->
										</div>
									</div>
								</div>
							</div> <!-- end navbar-header -->
							<div class="nav-wrap flex-child">
								<div class="collapse navbar-collapse text-center" id="navbar-collapse">
									<ul class="nav navbar-nav">
										<li class="dropdown">
											<a href="/">Beranda</a>
										</li>
										<li class="dropdown">
											<a href="/about">Tentang</a>
										</li>
										<li class="dropdown">
											<a href="javascript:void(0)">Kategori</a>
											<i class="fa fa-angle-down dropdown-trigger"></i>
											<ul class="dropdown-menu megamenu-wide">
												<li>
													<div class="megamenu-wrap container">
														<div class="menu">
															<!-- <div class="row"> -->
																<!-- <div class="col-md-3 megamenu-item"> -->
																	<ul class="menu-list">
																		@php
																			$kategori = \App\Models\Category::get();
																		@endphp
																		@if(count($kategori)>0)
																		@foreach($kategori as $key => $item)
																		<li>
																			<a href="/category/{{$item->id}}">{{$item->nama_kategori}}</a>
																		</li>
																		@endforeach
																		@endif
																	</ul>
																<!-- </div> -->
															<!-- </div> -->
														</div>
													</div>
												</li>
											</ul>
										</li>
										<li class="dropdown">
											<a href="/faq">F.A.Q</a>
										</li>
										<li class="dropdown">
											<a href="/contact">Kontak Kami</a>
										</li>
										<!-- Mobile search -->
										<li id="mobile-search" class="hidden-lg hidden-md">
											<form method="get" class="mobile-search">
												<input type="search" class="form-control" placeholder="Search...">
												<button type="submit" class="search-button">
													<i class="fa fa-search"></i>
												</button>
											</form>
										</li>
									</ul> <!-- end menu -->
								</div> <!-- end collapse -->
							</div> <!-- end col -->
							<div class="flex-child flex-right nav-right hidden-sm hidden-xs">
								<ul>
									@if(!empty($user = Auth::guard('webmember')->user()))
									<li class="nav-register">
										<span style="font-size:17px; font-weight:700;">{{ucwords($user->nama_member)}}</span>
									</li>
									<li class="nav-register">
										<a href="/logout_member">LogOut</a>
									</li>
									@else
									<li class="nav-register">
										<a href="/login_member">Masuk</a>
									</li>
									@endif
									<li class="nav-search-wrap style-2 hidden-sm hidden-xs">
										<a href="#" class="nav-search search-trigger">
											<i class="fa fa-search"></i>
										</a>
									</li>
									<li class="nav-cart">
										<div class="nav-cart-outer">
											<div class="nav-cart-inner">
												@if(!empty($user = Auth::guard('webmember')->user()) && isset($order))
												<a href="/cart?id={{$order?$order->id:''}}" class="nav-cart-icon" id="link-keranjang"><span style="font-size:15px;" id="keranjang">{{$order?$order->order_detail_count:0}}</span></a>
												@endif
											</div>
										</div>
									</li>
								</ul>
							</div>
						</div> <!-- end row -->
					</div> <!-- end container -->
				</div> <!-- end navigation -->
			</nav> <!-- end navbar -->
		</header>
		<div class="content-wrapper oh">
			@yield('content')
			<!-- <section class="newsletter" id="subscribe">
				<div class="container">
					<div class="row">
						<div class="col-sm-12 text-center">
							<h4>Get the latest updates</h4>
							<form class="relative newsletter-form">
								<input type="email" class="newsletter-input" placeholder="Enter your email">
								<input type="submit" class="btn btn-lg btn-dark newsletter-submit" value="Subscribe">
							</form>
						</div>
					</div>
				</div>
			</section> -->

			<footer class="footer footer-type-1">
				<div class="container">
					<div class="footer-widgets">
						<div class="row">
							<div class="col-md-3 col-sm-12 col-xs-12">
								<div class="widget footer-about-us">
									<img src="/uploads/Logo.png" alt=""  style="height:68px; width:370px;">
									<p class="mb-30">Warung Bumdes adalah platform yang menyediakan produk bumdes Kuala Alam.</p>
									<div class="footer-socials">
										<div class="social-icons nobase">
											<!-- <a href="/front/#"><i class="fa fa-twitter"></i></a>
											<a href="/front/#"><i class="fa fa-facebook"></i></a>
											<a href="/front/#"><i class="fa fa-google-plus"></i></a> -->
										</div>
									</div>
								</div>
							</div> <!-- end about us -->
							<div class="col-md-2 col-md-offset-1 col-sm-6 col-xs-12">
								<div class="widget footer-links">
									<h5 class="widget-title bottom-line left-align grey">Informasi</h5>
									<ul class="list-no-dividers">
										<li><a href="/front/#">Toko</a></li>
										<li><a href="/front/#">Tentang</a></li>
										<!-- <li><a href="/front/#">Business with us</a></li> -->
										<li><a href="/front/#">Informasi Pengiriman</a></li>
									</ul>
								</div>
							</div>
							<div class="col-md-2 col-sm-6 col-xs-12">
								<div class="widget footer-links">
									<h5 class="widget-title bottom-line left-align grey">Akun</h5>
									<ul class="list-no-dividers">
										<li><a href="/front/#">Akun Saya</a></li>
										<!-- <li><a href="/front/#">Wishlist</a></li> -->
										<!-- <li><a href="/front/#">Order history</a></li> -->
										<!-- <li><a href="/front/#">Specials</a></li> -->
									</ul>
								</div>
							</div>
							<div class="col-md-2 col-sm-6 col-xs-12">
								<div class="widget footer-links">
									<h5 class="widget-title bottom-line left-align grey">Link</h5>
									<ul class="list-no-dividers">
										<!-- <li><a href="/front/#">Shipping Policy</a></li> -->
										<!-- <li><a href="/front/#">Stores</a></li> -->
										<!-- <li><a href="/front/#">Returns</a></li> -->
										<li><a href="/front/#">Syarat &amp; Kondisi</a></li>
									</ul>
								</div>
							</div>
							<div class="col-md-2 col-sm-6 col-xs-12">
								<div class="widget footer-links">
									<h5 class="widget-title bottom-line left-align grey">Layanan</h5>
									<ul class="list-no-dividers">
										<!-- <li><a href="/front/#">Support</a></li> -->
										<!-- <li><a href="/front/#">Warranty</a></li> -->
										<li><a href="/front/#">F.A.Q</a></li>
										<li><a href="/front/#">Kontak</a></li>
									</ul>
								</div>
							</div>
						</div>
					</div>
				</div> <!-- end container -->
				<div class="bottom-footer">
					<div class="container">
						<div class="row">
							<div class="col-sm-6 copyright sm-text-center">
								<span>
									&copy; 2023 Warung Digital</a>
								</span>
							</div>
							<div class="col-sm-6 col-xs-12 footer-payment-systems text-right sm-text-center mt-sml-10">
								<i class="fa fa-cc-paypal"></i>
								<i class="fa fa-cc-visa"></i>
								<i class="fa fa-cc-mastercard"></i>
								<i class="fa fa-cc-discover"></i>
								<i class="fa fa-cc-amex"></i>
							</div>
						</div>
					</div>
				</div> <!-- end bottom footer -->
			</footer> <!-- end footer -->

			<div id="back-to-top">
					<a href="#top"><i class="fa fa-angle-up"></i></a>
			</div>
		</div> <!-- end content wrapper -->
	</main> <!-- end main wrapper -->

	<!-- jQuery Scripts -->
	<script type="text/javascript" src="/front/js/jquery.min.js"></script>
	<script type="text/javascript" src="/front/js/bootstrap.min.js"></script>
	<script type="text/javascript" src="/front/js/plugins.js"></script>
	<script type="text/javascript" src="/front/js/scripts.js"></script>
	<script type="text/javascript">
		$.ajaxSetup({
			headers: {
				'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
			},
			beforeSend: function (xhr) {
				xhr.setRequestHeader('X-Requested-With', 'XMLHttpRequest');
			},
		})
	</script>
	@stack('js')
</body>
</html>
