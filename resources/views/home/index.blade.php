@extends('layout.home');
@php
	function rupiah($angka){
		$hasil_rupiah = "Rp. " . number_format((int)$angka);
		$hasil_rupiah = str_replace(',', '.', $hasil_rupiah);
		return $hasil_rupiah;
	}
@endphp
@section('content')

	<!-- Hero Slider -->
	<section class="hero-wrap text-center relative">
		<div id="owl-hero" class="owl-carousel owl-theme light-arrows slider-animated">
			@if(count($slider)>0)
			@foreach ($slider as $item)
				<div class="hero-slide overlay" style="background-image:url('{{ asset('storage/'.$item->gambar) }}')">
					<div class="container">
					<div class="hero-holder">
						<div class="hero-message">
							<h1 class="hero-title nocaps">{{ $item->nama_slider }}</h1>
							<h2 class="hero-subtitle lines">{{ $item->deskripsi }}</h2>
							<div class="buttons-holder">
							<!-- <a href="/front/#" class="btn btn-lg btn-tra  nsparent"><span>Belanja Sekarang</span></a> -->
							</div>
						</div>
					</div>
					</div>
				</div>
			@endforeach
			@endif
		</div>
	</section> <!-- end hero slider -->

	<!-- Promo Banners -->
	<!-- <section class="section-wrap promo-banners pb-30">
		<div class="container">
			<div class="row">

			<div class="col-xs-4 col-xxs-12 mb-30 promo-banner">
				<a href="/front/#">
					<img src="/front/img/shop/collection_1.jpg" alt="">
					<div class="overlay"></div>
					<div class="promo-inner valign">
					<h2>for her</h2>
					<span>Best Selling Deals</span>
					</div>
				</a>
			</div>

			<div class="col-xs-4 col-xxs-12 mb-30 promo-banner">
				<a href="/front/#">
					<img src="/front/img/shop/collection_2.jpg" alt="">
					<div class="overlay"></div>
					<div class="promo-inner valign">
					<h2>accessories</h2>
					<span>Hot Trends</span>
					</div>
				</a>
			</div>

			<div class="col-xs-4 col-xxs-12 mb-30 promo-banner">
				<a href="/front/#">
					<img src="/front/img/shop/collection_3.jpg" alt="">
					<div class="overlay"></div>
					<div class="promo-inner valign">
					<h2>for him</h2>
					<span>New Collection</span>
					</div>
				</a>
			</div>
			
			</div>
		</div>
	</section> -->
	<!-- end promo banners -->

	<!-- Trendy Products -->
	<section class="section-wrap-sm new-arrivals pb-50 mt-30">
		<div class="container">
			<div class="row heading-row">
			<div class="col-md-12 text-center">
				<span class="subheading">Produk Terbaru</span>
				<h2 class="heading bottom-line">
					Produk Terlaris
				</h2>
			</div>
			</div>

			<div class="row items-grid">
				@if(count($product)>0)
				@php $i=0; @endphp
					@foreach($product as $item)
					<div class="col-md-3 col-xs-6">
						<div class="product-item hover-trigger">
							<div class="product-img">
								<a href="/front/shop-single.html">
                           @if(file_exists(public_path().'/storage/'.$item->gambar))
									<img src="{{ asset('storage/'.$item->gambar) }}" alt="" width="300" height="300" style="width:auto; height:30%;">
                           @else
									<img src="{{ asset('assets/default.png') }}" alt="" width="300" height="300" style="width:auto; height:30%;">
                           @endif
								</a>
								<!-- <div class="product-label">
									<span class="sale">Terjual</span>
								</div> -->
								<div class="hover-overlay">                    
									<div class="product-actions">
										<a href="/front" class="product-add-to-wishlist">
											<!-- <i class="fa fa-heart"></i> -->
										</a>
									</div>
									<div class="product-details valign">
										<!-- <span class="category">
											<a href="/front/catalogue-grid.html">Women</a>
										</span> -->
										<h3 class="product-title">
											<a href="/front/shop-single.html">{{$item->nama_produk}}</a>
										</h3>
										<span class="price">
											<!-- <del>
												<span>$730.00</span>
											</del> -->
											<ins>
												<span class="amount">{{$item->harga?rupiah($item->harga):'-'}}</span>
											</ins>
										</span>
										<div class="btn-quickview">
											<a href="/product/{{$item->id}}" class="btn btn-md btn-color">
												<span>Detail</span>
											</a>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
					@php $i++ @endphp
					@if($i%4==0)
					</div>
					<div class="row">
					@endif
					@endforeach
				@endif
			</div> <!-- end row -->
		</div>
	</section> <!-- end trendy products -->

@endsection