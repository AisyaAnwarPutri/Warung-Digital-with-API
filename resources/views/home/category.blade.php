@extends('layout.home')

@section('title', 'Kategori')
@section('content')
@php
	function rupiah($angka){
		$hasil_rupiah = "Rp. " . number_format((int)$angka);
		$hasil_rupiah = str_replace(',', '.', $hasil_rupiah);
		return $hasil_rupiah;
	}
@endphp
	<!-- Page Title -->
	<section class="page-title text-center bg-light">
		<div class="container relative clearfix">
			<div class="title-holder">
				<div class="title-text">
				<h1 class="uppercase">{{$category->nama_kategori}}</h1>
				<ol class="breadcrumb">
					<li>
						<a href="/">Beranda</a>
					</li>
					<li class="active">
						{{$category->nama_kategori}}
					</li>
				</ol>
				</div>
			</div>
		</div>
	</section>

	<div class="content-wrapper oh">
	<!-- Catalogue -->
	<section class="section-wrap pt-80 pb-40 catalogue">
		<div class="container relative">
			<!-- Filter -->
			<div class="shop-filter">
				<div class="view-mode hidden-xs">
					<span>Lihat:</span>
					<a class="grid grid-active" id="grid"></a>
					<a class="list" id="list"></a>
				</div>
				{{-- <div class="filter-show hidden-xs">
					<span>Tampilkan:</span>
					<a href="javascript:void(0)" class="active">12</a>
					<a href="javascript:void(0)">24</a>
					<a href="javascript:void(0)">Semua</a>
				</div> --}}
				{{-- <form class="ecommerce-ordering">
					<select>
						<option value="default-sorting">Default Sorting</option>
						<option value="price-low-to-high">Price: high to low</option>
						<option value="price-high-to-low">Price: low to high</option>
						<option value="by-popularity">By Popularity</option>
						<option value="date">By Newness</option>
						<option value="rating">By Rating</option>
					</select>
				</form> --}}
			</div>

			<div class="row">
				<div class="col-md-12 catalogue-col right mb-50">
					<div class="shop-catalogue grid-view">
						<div class="row items-grid">
							@if(count($produk)>0)
							@foreach($produk as $key => $value)
							<div class="col-md-4 col-xs-6 product product-grid">
								<div class="product-item clearfix">
									<div class="product-img hover-trigger">
										<a href="javascript:void(0)">
											@if(file_exists(public_path().'/storage/'.$value->gambar))
											{{-- <img src="/storage/{{$value->gambar}}" alt="" width="300" height="300" style="width:auto; height:30%;"> --}}
											{{-- <img src="/storage/{{$value->gambar}}" alt="" class="back-img" width="300" height="300" style="width:auto; height:30%;"> --}}
											<img src="/storage/{{$value->gambar}}" alt="" width="200" height="100" style="width: auto; min-height:25rem;">
											@else
											<img src="{{ asset('assets/default.png') }}" alt="" width="200" height="100" style="width: auto; min-height:25rem;">
											@endif
										</a>
										{{-- <div class="product-label">
											<span class="sale">sale</span>
										</div> --}}
										<div class="hover-2">
											<div class="product-actions">
												<a href="javascript:void(0)" class="product-add-to-wishlist">
												<i class="fa fa-heart"></i>
												</a>
											</div>
										</div>
										<a href="/product/{{$value->id}}" class="product-quickview">Detail Produk</a>
									</div>

									<div class="product-details">
										<h3 class="product-title">
											<a href="javascript:void(0)">{{$value->nama_produk}}</a>
										</h3>
										<span class="category">
											<a href="javascript:void(0)">{{$value->category->nama_kategori}}</a>
										</span>
									</div>

									<span class="price">
										{{-- <del>
											<span>$730.00</span>
										</del> --}}
										<ins>
											<span class="amount">{{rupiah($value->harga)}}</span>
										</ins>
									</span>

									<div class="product-description">
										<h3 class="product-title">
											{{-- <a href="javascript:void(0)">Drawstring Dress</a> --}}
										</h3>
										<span class="price">
											{{-- <del>
												<span>$730.00</span>
											</del> --}}
											<ins>
												<span class="amount">{{rupiah($value->harga)}}</span>
											</ins>
										</span>
										<div class="clear"></div>
										<p>{{$value->deskripsi}}</p>
										<a href="javascript:void(0)" class="btn btn-dark btn-md left"><span>Tambah Keranjang</span></a>
									</div>
								</div>
							</div>
							@endforeach
							@endif
						</div> <!-- end product -->
					</div> <!-- end row -->
				</div> <!-- end grid mode -->
				
				{{-- <!-- Pagination -->
				<div class="pagination-wrap clearfix">
					<p class="result-count">Showing: 12 of 80 results</p>       
					<nav class="pagination right clearfix">
					<a href="javascript:void(0)"><i class="fa fa-angle-left"></i></a>
					<span class="page-numbers current">1</span>
					<a href="javascript:void(0)">2</a>
					<a href="javascript:void(0)">3</a>
					<a href="javascript:void(0)">4</a>
					<a href="javascript:void(0)"><i class="fa fa-angle-right"></i></a>
					</nav>
				</div> --}}

			</div> <!-- end col -->

			</div> <!-- end row -->
		</div> <!-- end container -->
	</section> <!-- end catalog -->
	<div id="back-to-top">
		<a href="javascript:void(0)top"><i class="fa fa-angle-up"></i></a>
	</div>
	
	  @endsection